<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Persona;
use App\Models\Pais;
use App\Models\Mandamiento;
use App\Models\Telefono;
use App\Models\Imei;
use App\Models\ImeiTelefono;
use App\Models\TipoMandamiento;
use App\Models\Juzgado;
use App\Models\Delito;
use App\Models\Vehiculo;
use App\Models\VehiculoCaso;
use App\Models\Cargio;
use App\Models\EstacionServicio;
use App\Models\InspeccionTecnica;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use OpenSpout\Reader\CSV\Reader as CSVReader;
use OpenSpout\Reader\XLSX\Reader as XLSXReader;
use Carbon\Carbon;
use Box\Spout\Reader\Common\Creator\ReaderEntityFactory;



class Importacion extends Controller
{

    public function index()
    {
        return view('importar.index');
    }

    /**
     * Descargar plantilla CSV
     */
    public function plantilla()
    {
        $filename = 'plantilla_personas_' . date('Y-m-d_His') . '.csv';

        $headers = ['N REGISTRO', 'NOMBRE', 'CI', 'DATOS_SEGIP', 'responsable', 'ESTADO'];

        $ejemplo = [
            [1, 'JUAN PEREZ GARCIA', '1234567', 'Nombres y Apellidos: JUAN PEREZ GARCIA
            Fecha de Nacimiento: 15/01/1990
            Domicilio: Calle Principal 123
            Género: MASCULINO
            Estado Civil: SOLTERO
            Profesión: Ingeniero
            País: BOLIVIA', 'JHONY', 'En investigación'],
            [2, 'MARIA LOPEZ QUISPE', '7654321', 'Nombres y Apellidos: MARIA LOPEZ QUISPE
            Fecha de Nacimiento: 22/03/1985
            Domicilio: Calle Secundaria 456
            Género: FEMENINO
            Estado Civil: CASADA
            Profesión: Abogada
            País: BOLIVIA', 'ENZO', 'Pendiente']
        ];

        $handle = fopen('php://memory', 'w');
        stream_context_set_default(['http' => ['method' => 'GET']]);

        // Escribir encabezados
        fputcsv($handle, $headers, ';');

        // Escribir ejemplo
        foreach ($ejemplo as $fila) {
            fputcsv($handle, $fila, ';');
        }

        rewind($handle);
        $contenido = stream_get_contents($handle);
        fclose($handle);

        return response($contenido, 200, [
            'Content-Type' => 'text/csv; charset=utf-8',
            'Content-Disposition' => "attachment; filename=$filename",
        ]);
    }

    /**
     * Procesar archivo CSV
     */
    public function store(Request $request)
    {

        $request->validate([
            'archivo' => 'required|file|extensions:csv,xlsx,xls|max:10240',
        ], [
            'archivo.required' => 'El archivo es obligatorio',
            'archivo.extensions' => 'El archivo debe ser CSV o Excel',
            'archivo.max' => 'El archivo no debe exceder 10MB',
        ]);

        try {
            $archivo = $request->file('archivo');
            $rutaTemporal = $archivo->store('temp');
            $rutaCompleta = Storage::disk('local')->path($rutaTemporal);

            // Detectar tipo de archivo y leer
            $extension = $archivo->getClientOriginalExtension();

            if ($extension === 'csv') {
                $datos = $this->leerCSV($rutaCompleta);
            } else {
                $datos = $this->leerExcel($rutaCompleta);
            }

            // Unlink temporal
            unlink($rutaCompleta);

            if (empty($datos)) {
                return response()->json([
                    'error' => 'El archivo está vacío o no contiene registros válidos'
                ], 400);
            }

            $resultado = $this->importarPersonas($datos);

            return response()->json($resultado, 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al procesar el archivo: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Leer archivo CSV
     */
    private function leerCSV($ruta)
    {
        $reader = new CSVReader();
        $reader->open($ruta);

        $datos = [];
        $encabezados = [];
        $esPrimera = true;

        // CSV también usa getSheetIterator() (aunque solo tiene 1 hoja)
        foreach ($reader->getSheetIterator() as $sheet) {
            foreach ($sheet->getRowIterator() as $indice => $fila) {
                $celdas = $fila->getCells();
                $valores = array_map(fn($celda) => $celda->getValue(), $celdas);

                if ($esPrimera) {
                    $encabezados = array_map('trim', $valores);
                    $esPrimera = false;
                } else {
                    // Normalizar: mismo número de valores que encabezados
                    $valores = array_pad($valores, count($encabezados), null);
                    $valores = array_slice($valores, 0, count($encabezados));

                    $fila_asociativa = array_combine($encabezados, $valores);
                    if (!empty(array_filter($fila_asociativa))) {
                        $datos[] = $fila_asociativa;
                    }
                }
            }
            break; // CSV solo tiene 1 hoja
        }

        $reader->close();
        return $datos;
    }

    /**
     * Leer archivo Excel
     */
    private function leerExcel($ruta)
    {
        $reader = new XLSXReader();
        $reader->open($ruta);

        $datos = [];
        $encabezados = [];
        $esPrimera = true;

        // Leer solo la primera hoja
        foreach ($reader->getSheetIterator() as $sheet) {
            foreach ($sheet->getRowIterator() as $indice => $fila) {
                $celdas = $fila->getCells();
                $valores = array_map(fn($celda) => $celda->getValue(), $celdas);

                if ($esPrimera) {
                    $encabezados = array_map('trim', $valores);
                    $esPrimera = false;
                } else {
                    // Normalizar: mismo número de valores que encabezados
                    $valores = array_pad($valores, count($encabezados), null);
                    $valores = array_slice($valores, 0, count($encabezados));

                    $fila_asociativa = array_combine($encabezados, $valores);
                    if (!empty(array_filter($fila_asociativa))) {
                        $datos[] = $fila_asociativa;
                    }
                }
            }
            break; // Solo primera hoja
        }

        $reader->close();
        return $datos;
    }

    /**
     * Importar personas
     */
    private function importarPersonas($datos)
    {

        $importadas = 0;
        $errores = [];
        $paisesCache = [];

        DB::beginTransaction();

        try {
            foreach ($datos as $indice => $fila) {
                try {
                    $personaData = $this->mapearDatos($fila, $paisesCache);

                    // Evitar duplicados por CI
                    if (!empty($personaData['ci'])) {
                        $existe = Persona::where('ci', $personaData['ci'])->first();
                        if ($existe) {
                            // Actualizar en lugar de crear
                            $existe->update($personaData);
                            $importadas++;
                            continue;
                        }
                    }

                    Persona::create($personaData);
                    $importadas++;
                } catch (\Exception $e) {
                    $errores[] = [
                        'fila' => $indice + 2, // +2 porque comienza en 1 y hay encabezado
                        'nombre' => $fila['NOMBRE'] ?? 'Sin nombre',
                        'error' => $e->getMessage()
                    ];
                }
            }

            DB::commit();

            $mensaje = "Se importaron $importadas registros correctamente.";
            if (!empty($errores)) {
                $mensaje .= " " . count($errores) . " registros tuvieron errores.";
            }

            return [
                'success' => $mensaje,
                'importadas' => $importadas,
                'errores' => $errores,
                'total' => count($datos)
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Mapear datos del CSV al modelo
     */
    private function mapearDatos($fila, &$paisesCache)
    {
        $datos = [];

        // Nombres y apellidos
        $nombreCompleto = $fila['NOMBRE'] ?? '';
        $partes = $this->separarNombreApellidos($nombreCompleto);
        $datos['nombres'] = $partes['nombres'] ?? '';
        $datos['apellidos'] = $partes['apellidos'] ?? '';

        // CI
        $datos['ci'] = !empty($fila['CI']) ? $this->limpiarCI($fila['CI']) : null;

        // Responsable
        $datos['responsable'] = $fila['responsable'] ?? null;

        // Datos SEGIP (valores completos)
        $datos['datos_segip'] = $fila['DATOS_SEGIP'] ?? null;


        // Estado investigación
        $datos['estado_investigacion'] = $fila['ESTADO'] ?? null;

        // Filtrar nulls y vacíos
        return array_filter($datos, fn($v) => $v !== null && $v !== '');
    }

    /**
     * Separar nombre y apellidos
     */
    private function separarNombreApellidos($nombreCompleto, $alterno = false)
    {
        if (empty($nombreCompleto) && !$alterno) {
            return ['nombres' => 'Sin', 'apellidos' => 'Nombre'];
        }
        $nombreCompleto = convertirMayusculas(eliminarEspaciosMultiples($nombreCompleto));
        $partes = explode(' ', $nombreCompleto);

        // Asumir que los últimos dos elementos son apellidos cuando son más de 2 palabras
        if (count($partes) > 2) {
            $apellidos = array_slice($partes, -2);
            $nombres = array_slice($partes, 0, -2);
        } else {
            $nombres = array_slice($partes, 0, 1);
            $apellidos = array_slice($partes, 1);
        }
        return [
            'nombres' => trim(implode(' ', $nombres)),
            'apellidos' => trim(implode(' ', $apellidos))
        ];
    }

    /**
     * Limpiar CI
     */
    private function limpiarCI($ci)
    {
        // Remover espacios en blanco y caracteres especiales
        return preg_replace('/[^0-9]/', '', trim($ci));
    }



    public function indexMandamientoImportar()
    {
        return view('importar.indexMandamientoImportar');
    }

    public function importarMandamientos(Request $request)
    {

        $request->validate([
            'archivo' => 'required|file|extensions:csv|max:10240',
        ], [
            'archivo.required' => 'El archivo es obligatorio',
            'archivo.extensions' => 'El archivo debe ser CSV',
            'archivo.max' => 'El archivo no debe exceder 10MB',
        ]);

        $path = $request->file('archivo')->storeAs(
            'csv_imports',
            "mandamiento_" . $request->file('archivo')->getClientOriginalName(),
            'local'
        );

        $reader = ReaderEntityFactory::createReaderFromFile(Storage::disk('local')->path($path));

        $delimiter = $this->detectDelimiter(Storage::disk('local')->path($path));
        $reader->setFieldDelimiter($delimiter);

        $reader->open(Storage::disk('local')->path($path));

        $data = [];
        $cabeceras = [];
        $filasVaciasConsecutivas = 0;
        $umbralFilasVacias = 20; // Si encuentra 20 filas vacías seguidas, se detiene

        foreach ($reader->getSheetIterator() as $sheet) {

            foreach ($sheet->getRowIterator() as $index => $row) {
                $cells = $row->toArray();

                if ($index === 1) {
                    $cabeceras = $this->convertirCabeceras($cells);
                    continue;
                }

                // Verificar si la fila está completamente vacía
                $filaVacia = empty(array_filter($cells, function ($celda) {
                    return $celda !== null && trim($celda) !== '';
                }));

                if ($filaVacia) {
                    $filasVaciasConsecutivas++;

                    // Si hay más de X filas vacías seguidas, detener
                    if ($filasVaciasConsecutivas > $umbralFilasVacias) {
                        break 2; // Rompe ambos foreach
                    }

                    continue; // Saltar fila vacía pero continuar leyendo
                }

                // Si encontramos datos, resetear el contador de filas vacías
                $filasVaciasConsecutivas = 0;

                $filaAsociativa = array_combine($cabeceras, $cells);
                $data[] = $filaAsociativa;
            }
            break; // Solo la primera hoja
        }

        $resultado = $this->registrarDatosMandamientos($data);

        if (!is_numeric($resultado)) {
            return response()->json([
                'message' => "No se pudieron importar los mandamientos. Error: { $resultado[error] }"
            ], 500);
        }


        return response()->json([
            'success' => 'Migración de mandamientos completada, Se importaron ' . $resultado . ' mandamientos',
            'data' => $resultado,
        ], 200);
    }

    private function detectDelimiter($filePath)
    {
        $file = fopen($filePath, 'r');
        $firstLine = fgets($file);
        fclose($file);

        if (!$firstLine) return ','; // Valor por defecto

        $delimiters = [
            ',' => substr_count($firstLine, ','),
            ';' => substr_count($firstLine, ';'),
            "\t" => substr_count($firstLine, "\t"),
        ];

        // Retorna el que tenga más apariciones
        return array_search(max($delimiters), $delimiters);
    }

    private function convertirCabeceras($cells)
    {
        $cabecerasNormalizadas = array_map(function ($celda) {
            return  str_replace([' ', '.', 'Ñ'], ['_', '', 'N'], convertirMayusculas(eliminarEspaciosMultiples($celda)));
        }, $cells);

        return $cabecerasNormalizadas;
    }


    private function registrarDatosMandamientos($data)
    {

        try {
            DB::beginTransaction();

            $contador = 0;

            foreach ($data as $index => $fila) {


                $nombreCompleto = $this->separarNombreApellidos($fila['NOMBRE'] ?? '', true);

                $datos  = [
                    'hoja_ruta' => campoDB($fila['HOJA_DE_RUTA_O_MEMORANDUM'] ?? null),
                    'id_persona' => Persona::idPersonaDatos(['nombres' => $nombreCompleto['nombres'], 'apellidos' => $nombreCompleto['apellidos'], 'ci' => campoDB($fila['CI'] ?? null)]),
                    'id_tipo_mandamiento' => TipoMandamiento::idtipoMandamientoNombre(campoDB($fila['TIPO_DE_MANDAMIENTO']) ?? null),
                    'tipo_documento' => campoDB($fila['ORIGINAL_O_FOTOCOPIA'] ?? null),
                    'id_delito' => Delito::idDelitoNombre(campoDB($fila['DELITO']) ?? null),
                    'id_juzgado' => Juzgado::idJuzgadoNombre(campoDB($fila['JUZGADO']) ?? null),
                    'estado' => campoDB($fila['ESTADO'] ?? null),
                    'domicilio' => $fila['DOMICILIO'] ?? null,
                    'vehiculos' => $fila['VEHICULOS'] ?? null,
                    'telefono' => $fila['TELEFONO'] ?? null,
                    'asignado' => campoDB($fila['ASIGNADO'] ?? null),
                    'actividades_realizadas' => $fila['ACTIVIDADES_REALIZADAS'] ?? null,
                    // 'fecha_ejecucion' => !empty($fila['FECHA_EJECUCION']) ? Carbon::parse(campoDB($fila['FECHA_EJECUCION'])) : null,
                    'detalle_ejecucion' => nuloSiVacio($fila['DETALLE_EJECUCION'] ?? null),
                ];

                $mandamient = Mandamiento::create($datos);

                if (!$mandamient) {
                    throw new \Exception("Error al crear mandamiento en fila " . ($index + 2));
                }
                $contador++;
            }


            DB::commit();

            return $contador;
        } catch (\Exception $e) {

            DB::rollBack();
            return ["error" => $e->getMessage()];
        }
    }

    public function indexTelefono()
    {
        return view('importar.indexTelefonoImportar');
    }


    public function storeTelefono(Request $request)
    {

        $request->validate([
            'archivo' => 'required|file|extensions:csv|max:10240',
        ], [
            'archivo.required' => 'El archivo es obligatorio',
            'archivo.extensions' => 'El archivo debe ser CSV',
            'archivo.max' => 'El archivo no debe exceder 10MB',
        ]);

        $path = $request->file('archivo')->storeAs(
            'csv_imports',
            'telefono_' . $request->file('archivo')->getClientOriginalName(),
            'local'
        );

        $reader = ReaderEntityFactory::createReaderFromFile(Storage::disk('local')->path($path));

        $delimiter = $this->detectDelimiter(Storage::disk('local')->path($path));
        $reader->setFieldDelimiter($delimiter);

        $reader->open(Storage::disk('local')->path($path));

        $data = [];
        $cabeceras = [];
        $filasVaciasConsecutivas = 0;
        $umbralFilasVacias = 20; // Si encuentra 20 filas vacías seguidas, se detiene
        $columnasRequeridas = ['NUMERO_DE_CELULAR', 'PERSONA_DEL_CASO', 'CASO', 'EMPRESA', 'RESP_A_REQ', 'CI', 'INFO', 'CALLAPP', 'TRUECALL', 'UNINET'];

        foreach ($reader->getSheetIterator() as $sheet) {

            foreach ($sheet->getRowIterator() as $index => $row) {
                $cells = $row->toArray();

                if ($index === 1) {
                    $cabeceras = $this->convertirCabeceras($cells);

                    // /verificar si el archivo tiene las columnas necesarias la indispensable es el numero de celular
                    $faltantes = array_diff($columnasRequeridas, $cabeceras);
                    if (in_array('NUMERO_DE_CELULAR', $faltantes)) {
                        return response()->json([
                            'errors' => 'El archivo debe contener al menos la columna NUMERO_DE_CELULAR. Columnas faltantes: ' . implode(', ', $faltantes),
                            'message' => 'El archivo debe contener al menos la columna NUMERO_DE_CELULAR. Columnas faltantes: ' . implode(', ', $faltantes)
                        ], 422);
                    }


                    continue;
                }

                // Verificar si la fila está completamente vacía
                $filaVacia = empty(array_filter($cells, function ($celda) {
                    return $celda !== null && trim($celda) !== '';
                }));

                if ($filaVacia) {
                    $filasVaciasConsecutivas++;

                    // Si hay más de X filas vacías seguidas, detener
                    if ($filasVaciasConsecutivas > $umbralFilasVacias) {
                        break 2; // Rompe ambos foreach
                    }

                    continue; // Saltar fila vacía pero continuar leyendo
                }

                // Si encontramos datos, resetear el contador de filas vacías
                $filasVaciasConsecutivas = 0;

                $filaAsociativa = array_combine($cabeceras, $cells);
                $data[] = $filaAsociativa;
            }
            break; // Solo la primera hoja
        }

        $resultado = $this->registrarDatosTelefonos($data);

        if (!is_numeric($resultado)) {
            return response()->json([
                'error' => "No se pudieron importar los telefonos. Error: { $resultado[error] }"
            ], 500);
        }

        return response()->json([
            'success' => 'Migración de telefonos completada, Se importaron ' . $resultado . ' telefonos',
            'total' => $resultado,
            'importadas' => $resultado,
            'errores' => []
        ], 200);

        return $path;
    }

    private function registrarDatosTelefonos($data)
    {


        try {
            DB::beginTransaction();

            $contador = 0;

            foreach ($data as $index => $fila) {


                $nombreCompleto = $this->separarNombreApellidos($fila['RESP_A_REQ'] ?? '', true);

                $datos  = [
                    'numero_celular' => campoDB($fila['NUMERO_DE_CELULAR'] ?? null),
                    'persona_caso' => campoDB($fila['PERSONA_DEL_CASO'] ?? null),
                    'caso' => campoDB($fila['CASO'] ?? null),
                    'empresa' => campoDB($fila['EMPRESA'] ?? null),
                    'respuesta_requerimiento' => campoDB($fila['RESP_A_REQ'] ?? null),
                    'persona_id' => Persona::idPersonaDatos(['nombres' => $nombreCompleto['nombres'], 'apellidos' => $nombreCompleto['apellidos'], 'ci' => limpiarCI($fila['CI'] ?? null)]),
                    'informacion' => campoDB($fila['INFO'] ?? null),
                    'callapp' => $fila['CALLAPP'] ?? null,
                    'truecall' => $fila['TRUECALL'] ?? null,
                    'uninet' => $fila['UNINET'] ?? null,
                ];

                $telefono = Telefono::create($datos);

                if (!$telefono) {
                    throw new \Exception("Error al crear teléfono en fila " . ($index + 2) . "Numero: " . $fila['NUMERO_DE_CELULAR'] ?? 'Sin número');
                }
                $contador++;
            }


            DB::commit();

            return $contador;
        } catch (\Exception $e) {

            DB::rollBack();
            return ["error" => $e->getMessage()];
        }
    }


    public function storeIMEI(Request $request)
    {

        $request->validate([
            'archivo' => 'required|file|extensions:csv|max:10240',
        ], [
            'archivo.required' => 'El archivo es obligatorio',
            'archivo.extensions' => 'El archivo debe ser CSV',
            'archivo.max' => 'El archivo no debe exceder 10MB',
        ]);

        $path = $request->file('archivo')->storeAs(
            'csv_imports',
            'imei_' . $request->file('archivo')->getClientOriginalName(),
            'local'
        );

        $reader = ReaderEntityFactory::createReaderFromFile(Storage::disk('local')->path($path));

        $delimiter = $this->detectDelimiter(Storage::disk('local')->path($path));
        $reader->setFieldDelimiter($delimiter);

        $reader->open(Storage::disk('local')->path($path));

        $data = [];
        $cabeceras = [];
        $filasVaciasConsecutivas = 0;
        $umbralFilasVacias = 20; // Si encuentra 20 filas vacías seguidas, se detiene
        $columnasRequeridas = ['NUMERO_ASOCIADO', 'NUMERO_IMEI'];

        foreach ($reader->getSheetIterator() as $sheet) {

            foreach ($sheet->getRowIterator() as $index => $row) {
                $cells = $row->toArray();

                if ($index === 1) {
                    $cabeceras = $this->convertirCabeceras($cells);

                    // /verificar si el archivo tiene las columnas necesarias la indispensable es el numero de celular
                    $faltantes = array_diff($columnasRequeridas, $cabeceras);
                    if (in_array(['NUMERO_ASOCIADO', 'NUMERO_IMEI'], $faltantes)) {
                        return response()->json([
                            'errors' => 'El archivo debe contener al menos la columna NUMERO_DE_CELULAR. Columnas faltantes: ' . implode(', ', $faltantes),
                            'message' => 'El archivo debe contener al menos la columna NUMERO_DE_CELULAR. Columnas faltantes: ' . implode(', ', $faltantes)
                        ], 422);
                    }
                    continue;
                }

                // Verificar si la fila está completamente vacía
                $filaVacia = empty(array_filter($cells, function ($celda) {
                    return $celda !== null && trim($celda) !== '';
                }));

                if ($filaVacia) {
                    $filasVaciasConsecutivas++;

                    // Si hay más de X filas vacías seguidas, detener
                    if ($filasVaciasConsecutivas > $umbralFilasVacias) {
                        break 2; // Rompe ambos foreach
                    }

                    continue; // Saltar fila vacía pero continuar leyendo
                }

                // Si encontramos datos, resetear el contador de filas vacías
                $filasVaciasConsecutivas = 0;

                $filaAsociativa = array_combine($cabeceras, $cells);
                $data[] = $filaAsociativa;
            }
            break; // Solo la primera hoja
        }

        $resultado = $this->vincularImeiTelefono($data);


        if (!is_numeric($resultado)) {
            return response()->json([
                'error' => "No se pudieron importar los telefonos. Error: { $resultado[error] }"
            ], 500);
        }

        return response()->json([
            'success' => 'Migración de telefonos completada, Se importaron ' . $resultado . ' telefonos',
            'total' => $resultado,
            'importadas' => $resultado,
            'errores' => []
        ], 200);

        return $path;
    }


    private function vincularImeiTelefono($data)
    {


        try {
            DB::beginTransaction();

            $contador = 0;

            foreach ($data as $index => $fila) {



                $datos  = [
                    'imei' => campoDB($fila['NUMERO_IMEI'] ?? null),
                ];

                $imei = Imei::firstOrCreate($datos);


                $telefono = Telefono::firstOrCreate([
                    'numero_celular' => campoDB($fila['NUMERO_ASOCIADO'] ?? null),
                ]);

                $datosVincular = [
                    'telefono_id' => $telefono->id,
                    'imei_id' => $imei->id
                ];

                // if($datosVincular['telefono_id'] === null){
                //     throw new \Exception("No se encontró un teléfono con el número " . ($fila['NUMERO_ASOCIADO'] ?? 'Sin número') . " en la fila " . ($index + 2));
                // }

                $imeiTelefono = ImeiTelefono::firstOrCreate($datosVincular);


                if (!$imeiTelefono) {
                    throw new \Exception("Error al crear Vinculo IMEI-Teléfono en fila " . ($index + 2) . "Numero: " . $fila['NUMERO_IMEI'] ?? 'Sin número');
                }
                $contador++;
            }


            DB::commit();

            return $contador;
        } catch (\Exception $e) {

            DB::rollBack();
            return ["error" => $e->getMessage()];
        }
    }


    public function indexVehiculo()
    {
        return view('importar.indexVehiculoImportar');
    }

    public function storeVehiculo(Request $request)
    {


        $request->validate([
            'archivo' => 'required|file|extensions:csv|max:10240',
        ], [
            'archivo.required' => 'El archivo es obligatorio',
            'archivo.extensions' => 'El archivo debe ser CSV',
            'archivo.max' => 'El archivo no debe exceder 10MB',
        ]);

        $path = $request->file('archivo')->storeAs(
            'csv_imports',
            'vehiculos_' . $request->file('archivo')->getClientOriginalName(),
            'local'
        );

        $reader = ReaderEntityFactory::createReaderFromFile(Storage::disk('local')->path($path));

        $delimiter = $this->detectDelimiter(Storage::disk('local')->path($path));
        $reader->setFieldDelimiter($delimiter);

        $reader->open(Storage::disk('local')->path($path));

        $data = [];
        $cabeceras = [];
        $filasVaciasConsecutivas = 0;
        $umbralFilasVacias = 20; // Si encuentra 20 filas vacías seguidas, se detiene
        $columnasRequeridas = ['PLACA', 'CARACTERISTICAS', 'RESPONSABLE', 'CASO_RELACIONADO', 'RUAT', 'CI_RUAT', 'BSISA', 'CI_BSISA', 'SOAT', 'CI_SOAT'];

        foreach ($reader->getSheetIterator() as $sheet) {

            foreach ($sheet->getRowIterator() as $index => $row) {
                $cells = $row->toArray();

                if ($index === 1) {
                    $cabeceras = $this->convertirCabeceras($cells);

                    // /verificar si el archivo tiene las columnas necesarias la indispensable es el numero de celular
                    $faltantes = array_diff($columnasRequeridas, $cabeceras);
                    if (in_array('PLACA', $faltantes)) {
                        return response()->json([
                            'errors' => 'El archivo debe contener al menos la columna PLACA. Columnas faltantes: ' . implode(', ', $faltantes),
                            'message' => 'El archivo debe contener al menos la columna PLACA. Columnas faltantes: ' . implode(', ', $faltantes)
                        ], 422);
                    }


                    continue;
                }

                // Verificar si la fila está completamente vacía
                $filaVacia = empty(array_filter($cells, function ($celda) {
                    return $celda !== null && trim($celda) !== '';
                }));

                if ($filaVacia) {
                    $filasVaciasConsecutivas++;

                    // Si hay más de X filas vacías seguidas, detener
                    if ($filasVaciasConsecutivas > $umbralFilasVacias) {
                        break 2; // Rompe ambos foreach
                    }

                    continue; // Saltar fila vacía pero continuar leyendo
                }

                // Si encontramos datos, resetear el contador de filas vacías
                $filasVaciasConsecutivas = 0;

                $filaAsociativa = array_combine($cabeceras, $cells);
                $data[] = $filaAsociativa;
            }
            break; // Solo la primera hoja
        }

        $resultado = $this->registrarDatosVehiculos($data);

        if (!is_numeric($resultado)) {
            return response()->json([
                'error' => "No se pudieron importar los vehículos. Error: { $resultado[error] }"
            ], 500);
        }

        return response()->json([
            'success' => 'Migración de vehículos completada, Se importaron ' . $resultado . ' vehículos',
            'total' => $resultado,
            'importadas' => $resultado,
            'errores' => []
        ], 200);

        return $path;
    }

    private function registrarDatosVehiculos($data)
    {


        try {
            DB::beginTransaction();

            $contador = 0;

            foreach ($data as $index => $fila) {


                if (!empty(limpiarCI($fila['PLACA'] ?? null))) {
                    $datos  = [
                        'placa' => limpiarCI($fila['PLACA'] ?? null),
                        'descripcion' => campoDB($fila['CARACTERISTICAS'] ?? null),
                        'responsable' => campoDB($fila['RESPONSABLE'] ?? null),
                        'caso_relacionado' => campoDB($fila['CASO_RELACIONADO'] ?? null),
                    ];


                    $vehiculo = Vehiculo::firstOrCreate(['placa' => $datos['placa']], $datos);


                    if (!$vehiculo) {
                        throw new \Exception("Error al crear vehículo en fila " . ($index + 2) . "Placa: " . $fila['PLACA'] ?? 'Sin placa');
                    }

                    if (!empty($fila['RUAT'] ?? null) || !empty($fila['CI_RUAT'] ?? null)) {
                        $nombreCompleto = $this->separarNombreApellidos($fila['RUAT'] ?? '', true);
                        $personaRuat = Persona::idPersonaDatos(['nombres' => $nombreCompleto['nombres'], 'apellidos' => $nombreCompleto['apellidos'], 'ci' => limpiarCI($fila['CI_RUAT'] ?? null)]);

                        VehiculoCaso::firstOrCreate([
                            'vehiculo_id' => $vehiculo->id,
                            'persona_id' => $personaRuat,
                            'tipo' => 'RUAT'
                        ]);
                    }

                    if (!empty($fila['BSISA'] ?? null) || !empty($fila['CI_BSISA'] ?? null)) {
                        $nombreCompleto = $this->separarNombreApellidos($fila['BSISA'] ?? '', true);
                        $personaBsisa = Persona::idPersonaDatos(['nombres' => $nombreCompleto['nombres'], 'apellidos' => $nombreCompleto['apellidos'], 'ci' => limpiarCI($fila['CI_BSISA'] ?? null)]);

                        VehiculoCaso::firstOrCreate([
                            'vehiculo_id' => $vehiculo->id,
                            'persona_id' => $personaBsisa,
                            'tipo' => 'BSISA'
                        ]);
                    }

                    if (!empty($fila['SOAT'] ?? null) || !empty($fila['CI_SOAT'] ?? null)) {
                        $nombreCompleto = $this->separarNombreApellidos($fila['SOAT'] ?? '', true);
                        $personaSoat = Persona::idPersonaDatos(['nombres' => $nombreCompleto['nombres'], 'apellidos' => $nombreCompleto['apellidos'], 'ci' => limpiarCI($fila['CI_SOAT'] ?? null)]);

                        VehiculoCaso::firstOrCreate([
                            'vehiculo_id' => $vehiculo->id,
                            'persona_id' => $personaSoat,
                            'tipo' => 'SOAT'
                        ]);
                    }


                    $contador++;
                }
            }

            DB::commit();

            return $contador;
        } catch (\Exception $e) {

            DB::rollBack();
            return ["error" => $e->getMessage()];
        }
    }



    public function storeCarguiosVehiculo(Request $request)
    {


        $request->validate([
            'archivo' => 'required|file|extensions:csv,xlsx|max:10240',
        ], [
            'archivo.required' => 'El archivo es obligatorio',
            'archivo.extensions' => 'El archivo debe ser CSV, XLSX',
            'archivo.max' => 'El archivo no debe exceder 10MB',
        ]);

        $path = $request->file('archivo')->storeAs(
            'csv_imports',
            'carguios_' . $request->file('archivo')->getClientOriginalName(),
            'local'
        );

        $extension = mb_strtolower($request->file('archivo')->getClientOriginalExtension());


        $reader = ReaderEntityFactory::createReaderFromFile(Storage::disk('local')->path($path));

        if ($extension == 'csv') {
            $delimiter = $this->detectDelimiter(Storage::disk('local')->path($path));
            $reader->setFieldDelimiter($delimiter);
        }
        $reader->open(Storage::disk('local')->path($path));

        $data = [];
        $cabeceras = [];
        $filasVaciasConsecutivas = 0;
        $umbralFilasVacias = 20; // Si encuentra 20 filas vacías seguidas, se detiene
        $columnasRequeridas = ['EESS', 'NIT_ESTACION', 'DEPARTAMENTO', 'PRODUCTO', 'RAZON_SOCIAL', 'NIT_CONSUMIDOR', 'FACTURA', 'NRO_AUTORIZACION', 'CODIGO_CONTROL', 'CANTIDAD', 'MONTO_BS', 'FECHA_VENTA', 'PLACA'];

        $resultado = ['error' => 'No se procesó el archivo.'];
        $nroHojas   = 0;
        $total = 0;

        foreach ($reader->getSheetIterator() as $sheet) {

            foreach ($sheet->getRowIterator() as $index => $row) {
                $cells = $row->toArray();

                if ($index === 1) {
                    $cabeceras = $this->convertirCabeceras($cells);


                    // /verificar si el archivo tiene las columnas necesarias la indispensable es el numero de celular
                    $faltantes = array_diff($columnasRequeridas, $cabeceras);
                    if (in_array(['EESS', 'PLACA', 'FECHA_VENTA'], $faltantes)) {
                        return response()->json([
                            'errors' => 'El archivo debe contener al menos la columna PLACA. Columnas faltantes: ' . implode(', ', $faltantes),
                            'message' => 'El archivo debe contener al menos la columna PLACA. Columnas faltantes: ' . implode(', ', $faltantes)
                        ], 422);
                    }

                    continue;
                }

                // Verificar si la fila está completamente vacía
                $filaVacia = empty(array_filter($cells, function ($celda) {
                    return $celda !== null && trim($celda) !== '';
                }));

                if ($filaVacia) {
                    $filasVaciasConsecutivas++;

                    // Si hay más de X filas vacías seguidas, detener
                    if ($filasVaciasConsecutivas > $umbralFilasVacias) {
                        break 2; // Rompe ambos foreach
                    }

                    continue; // Saltar fila vacía pero continuar leyendo
                }

                // Si encontramos datos, resetear el contador de filas vacías
                $filasVaciasConsecutivas = 0;

                $filaAsociativa = array_combine($cabeceras, $cells);
                $data[] = $filaAsociativa;
            }

            $resultado = $this->registrarDatosCarguiosVehiculos($data);
            $total += is_numeric($resultado) ? $resultado : 0;

            if ($extension == 'csv') {
                break; // Solo la primera hoja para CSV
            }
            $nroHojas++;
        }


        if (!is_numeric($resultado)) {

            return response()->json([
                'error' => "No se pudieron importar los cargios. Error: { $resultado[error] }"
            ], 500);
        }

        return response()->json([
            'success' => 'Migración de cargios completada, Se importaron ' . $resultado . ' cargios',
            'total' => $total,
            'importadas' => $resultado,
            'errores' => [],
            'hoja' => $nroHojas
        ], 200);

        return $path;
    }


    private function registrarDatosCarguiosVehiculos($data)
    {


        try {
            DB::beginTransaction();

            $contador = 0;

            foreach ($data as $index => $fila) {

                $placa = limpiarCI($fila['PLACA'] ?? null);

                if (empty($placa)) {
                    continue; // Saltar filas sin placa
                }

                $vehiculo = Vehiculo::firstOrCreate(['placa' => $placa], ['placa' => $placa]);

                $datos = [
                    'vehiculo_id' => $vehiculo->id,
                    'estacion_servicio_id' => EstacionServicio::idEstacionDatos(limpiarCI($fila['NIT_ESTACION'] ?? null), campoDB($fila['EESS'] ?? null)),
                    'nit_consumidor' => limpiarCI($fila['NIT_CONSUMIDOR'] ?? null),
                    'razon_social' => campoDB($fila['RAZON_SOCIAL'] ?? null),
                    'departamento' => campoDB($fila['DEPARTAMENTO'] ?? null),
                    'producto' => campoDB($fila['PRODUCTO'] ?? null),
                    'factura' => campoDB($fila['FACTURA'] ?? null),
                    'nro_autorizacion' => campoDB($fila['NRO_AUTORIZACION'] ?? null),
                    'codigo_control' => campoDB($fila['CODIGO_CONTROL'] ?? null),
                    'cantidad' => campoDBNumero($fila['CANTIDAD'] ?? null),
                    'monto' => campoDBNumero($fila['MONTO_BS'] ?? null),
                    'fecha_venta' => campoDBFechaHora($fila['FECHA_VENTA'] ?? null)
                ];

                //verificar si no existe un carguio similar para evitar duplicados exactos la fecha_venta viene en formato 2026/02/25 10:20
                $existeCargio = Cargio::where('vehiculo_id', $datos['vehiculo_id'])
                    ->where('estacion_servicio_id', $datos['estacion_servicio_id'])
                    ->where('producto', $datos['producto'])
                    ->where('nit_consumidor', $datos['nit_consumidor'])
                    ->where('factura', $datos['factura'])
                    ->where('fecha_venta', $datos['fecha_venta'])
                    ->first();

                if ($existeCargio) {
                    continue; // Saltar fila si ya existe
                }

                $cargio = Cargio::create($datos);

                $contador++;
            }

            DB::commit();

            return $contador;
        } catch (\Exception $e) {

            DB::rollBack();
            return ["error" => $e->getMessage()];
        }
    }


    public function storeInspeccionesVehiculo(Request $request)
    {


        $request->validate([
            'archivo' => 'required|file|extensions:csv,xlsx|max:10240',
        ], [
            'archivo.required' => 'El archivo es obligatorio',
            'archivo.extensions' => 'El archivo debe ser CSV, XLSX',
            'archivo.max' => 'El archivo no debe exceder 10MB',
        ]);

        $path = $request->file('archivo')->storeAs(
            'csv_imports',
            'carguios_' . $request->file('archivo')->getClientOriginalName(),
            'local'
        );

        $extension = mb_strtolower($request->file('archivo')->getClientOriginalExtension());


        $reader = ReaderEntityFactory::createReaderFromFile(Storage::disk('local')->path($path));

        if ($extension == 'csv') {
            $delimiter = $this->detectDelimiter(Storage::disk('local')->path($path));
            $reader->setFieldDelimiter($delimiter);
        }
        $reader->open(Storage::disk('local')->path($path));

        $data = [];
        $cabeceras = [];
        $filasVaciasConsecutivas = 0;
        $umbralFilasVacias = 20; // Si encuentra 20 filas vacías seguidas, se detiene
        $columnasRequeridas = ['ANO', 'NOMBRE', 'C.I.', 'DETALLE',  'PLACA'];

        $resultado = ['error' => 'No se procesó el archivo.'];
        $nroHojas   = 0;
        $total = 0;

        foreach ($reader->getSheetIterator() as $sheet) {

            foreach ($sheet->getRowIterator() as $index => $row) {
                $cells = $row->toArray();

                if ($index === 1) {
                    $cabeceras = $this->convertirCabeceras($cells);


                    // /verificar si el archivo tiene las columnas necesarias la indispensable es el numero de celular
                    $faltantes = array_diff($columnasRequeridas, $cabeceras);
                    if (in_array(['ANO', 'PLACA'], $faltantes)) {
                        return response()->json([
                            'errors' => 'El archivo debe contener al menos la columna PLACA y AÑO. Columnas faltantes: ' . implode(', ', $faltantes),
                            'message' => 'El archivo debe contener al menos la columna PLACA y AÑO. Columnas faltantes: ' . implode(', ', $faltantes)
                        ], 422);
                    }

                    continue;
                }

                // Verificar si la fila está completamente vacía
                $filaVacia = empty(array_filter($cells, function ($celda) {
                    return $celda !== null && trim($celda) !== '';
                }));

                if ($filaVacia) {
                    $filasVaciasConsecutivas++;

                    // Si hay más de X filas vacías seguidas, detener
                    if ($filasVaciasConsecutivas > $umbralFilasVacias) {
                        break 2; // Rompe ambos foreach
                    }

                    continue; // Saltar fila vacía pero continuar leyendo
                }

                // Si encontramos datos, resetear el contador de filas vacías
                $filasVaciasConsecutivas = 0;

                $filaAsociativa = array_combine($cabeceras, $cells);
                $data[] = $filaAsociativa;
            }

            $resultado = $this->registrarDatosInspeccionesVehiculos($data);
            $total += is_numeric($resultado) ? $resultado : 0;

            if ($extension == 'csv') {
                break; // Solo la primera hoja para CSV
            }
            $nroHojas++;
        }


        if (!is_numeric($resultado)) {

            return response()->json([
                'error' => "No se pudieron importar las inspecciones. Error: { $resultado[error] }"
            ], 500);
        }

        return response()->json([
            'success' => 'Migración de inspecciones completada, Se importaron ' . $resultado . ' inspecciones',
            'total' => $total,
            'importadas' => $resultado,
            'errores' => [],
            'hoja' => $nroHojas
        ], 200);

        return $path;
    }


    private function registrarDatosInspeccionesVehiculos($data)
    {


        try {
            DB::beginTransaction();

            $contador = 0;

            foreach ($data as $index => $fila) {

                $placa = limpiarCI($fila['PLACA'] ?? null);

                if (empty($placa)) {
                    continue; // Saltar filas sin placa
                }

                $vehiculo = Vehiculo::firstOrCreate(['placa' => $placa], ['placa' => $placa]);

                $personaId = null;

                if (!empty($fila['CI'] ?? null) || !empty($fila['NOMBRE'] ?? null)) {
                    $nombreCompleto = $this->separarNombreApellidos($fila['NOMBRE'] ?? '', true);
                    $personaId = Persona::idPersonaDatos(['nombres' => $nombreCompleto['nombres'], 'apellidos' => $nombreCompleto['apellidos'], 'ci' => limpiarCI($fila['CI'] ?? null)]);

                }

                $datos = [
                    'vehiculo_id' => $vehiculo->id,
                    'persona_id' => $personaId,
                    'resultado' => nuloSiVacio($fila['DETALLE'] ?? null),
                    'anio' => campoDB($fila['ANO'] ?? null),
                ];

                //verificar si no existe un carguio similar para evitar duplicados exactos la fecha_venta viene en formato 2026/02/25 10:20
                $existeInspeccion = InspeccionTecnica::where('vehiculo_id', $datos['vehiculo_id'])
                    ->where('anio', $datos['anio'])
                    ->first();

                if ($existeInspeccion) {
                    continue; // Saltar fila si ya existe
                }

                $inspeccion = InspeccionTecnica::create($datos);

                $contador++;
            }

            DB::commit();

            return $contador;
        } catch (\Exception $e) {

            DB::rollBack();
            return ["error" => $e->getMessage()];
        }
    }
}
