<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Persona;
use App\Models\Pais;
use App\Models\Mandamiento;
use App\Models\TipoMandamiento;
use App\Models\Juzgado;
use App\Models\Delito;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use OpenSpout\Reader\CSV\Reader as CSVReader;
use OpenSpout\Reader\XLSX\Reader as XLSXReader;
use Carbon\Carbon;
use Box\Spout\Reader\Common\Creator\ReaderEntityFactory;



class ImportarPersonasController extends Controller
{

    // protected $cabeceras  = [
    //     'hoja_ruta' => 'HR',
    //     'id_persona' => 'ID_PERSONA',
    //     'nombres' => 'NOMBRE',
    //     'apellidos' => 'APELLIDOS',
    //     'id_tipo_mandamiento' => 'ID_TIPO_MANDAMIENTO',
    //     'tipo_mandamiento' => 'TIPO_MANDAMIENTO',
    //     'tipo_documento' => 'ORIGINAL_FOTOCOPIA',
    //     'id_delito' => 'ID_DELITO',
    //     'delito' => 'DELITO',
    //     'id_juzgado' => 'ID_JUZGADO',
    //     'juzgado' => 'JUZGADO',
    //     'estado' => 'ESTADO',
    //     'domicilio' => 'DOMICILIO',
    //     'ci' => 'CI',
    //     'vehiculos' => 'VEHICULOS',
    //     'telefono' => 'TELEFONO',
    //     'asignado' => 'ASIGNADO',
    //     'actividades_realizadas' => 'ACTIVIDADES_REALIZADAS',

    //     'fecha_ejecucion' => 'FECHA_EJECUCION',
    //     'detalle_ejecucion' => 'DETALLE_EJECUCION',
    // ];
    /**
     * Mostrar vista de importación
     */
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

        // Parsear datos del SEGIP
        $datosSEGIP = $fila['DATOS_SEGIP'] ?? '';

        // Aplicar datos parseados
        if (!empty($datosSEGIP['fecha_nacimiento']) && empty($datos['fecha_nacimiento'])) {
            $datos['fecha_nacimiento'] = $datosSEGIP['fecha_nacimiento'];
        }
        if (!empty($datosSEGIP['domicilio']) && empty($datos['domicilio'])) {
            $datos['domicilio'] = $datosSEGIP['domicilio'];
        }
        if (!empty($datosSEGIP['telefono']) && empty($datos['telefono'])) {
            $datos['telefono'] = $datosSEGIP['telefono'];
        }
        if (!empty($datosSEGIP['lugar_nacimiento']) && empty($datos['lugar_nacimiento'])) {
            $datos['lugar_nacimiento'] = $datosSEGIP['lugar_nacimiento'];
        }
        if (!empty($datosSEGIP['genero']) && empty($datos['genero'])) {
            $datos['genero'] = $datosSEGIP['genero'];
        }
        if (!empty($datosSEGIP['estado_civil']) && empty($datos['estado_civil'])) {
            $datos['estado_civil'] = $datosSEGIP['estado_civil'];
        }
        if (!empty($datosSEGIP['nombre_conyuge']) && empty($datos['nombre_conyuge'])) {
            $datos['nombre_conyuge'] = $datosSEGIP['nombre_conyuge'];
        }
        if (!empty($datosSEGIP['ocupacion']) && empty($datos['ocupacion'])) {
            $datos['ocupacion'] = $datosSEGIP['ocupacion'];
        }
        if (!empty($datosSEGIP['pais']) && empty($datos['id_pais'])) {
            // Buscar país en caché o BD
            if (!isset($paisesCache[$datosSEGIP['pais']])) {
                $pais = Pais::where('pais', 'LIKE', "%{$datosSEGIP['pais']}%")->first();
                $paisesCache[$datosSEGIP['pais']] = $pais?->id;
            }
            if ($paisesCache[$datosSEGIP['pais']]) {
                $datos['id_pais'] = $paisesCache[$datosSEGIP['pais']];
            }
        }

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
            $request->file('archivo')->getClientOriginalName(),
            'local'
        );

        $reader = ReaderEntityFactory::createReaderFromFile(Storage::disk('local')->path($path));

        $reader->setFieldDelimiter(';');

        $reader->open(Storage::disk('local')->path($path));

        $data = [];
        $cabeceras = [];
        foreach ($reader->getSheetIterator() as $sheet) {

            foreach ($sheet->getRowIterator() as $index => $row) {
                $cells = $row->toArray();


                if ($index === 1) {
                    $cabeceras = $this->convertirCabeceras($cells);
                    continue;
                }

                $filaAsociativa = array_combine($cabeceras, $cells);

                // if (empty($cells)) {
                //     return "vacioo"; // Saltar filas vacías
                // }

                $data[] =   $filaAsociativa;
            }
            break; // Solo la primera hoja
        }

        $resultado = $this->registrarDatosMandamientos($data);

        if (!is_numeric($resultado)) {
            return response()->json([
                'error' => "No se pudieron importar los mandamientos. Error: { $resultado[error] }"
            ], 500);
        }


        return response()->json([
            'success' => 'Migración de mandamientos completada, Se importaron ' . $resultado . ' mandamientos',
            'data' => $resultado,
        ], 200);
    }

    private function convertirCabeceras($cells)
    {
        $cabecerasNormalizadas = array_map(function ($celda) {
            return  str_replace([' ', '.'], ['_', ''], convertirMayusculas(eliminarEspaciosMultiples($celda)));
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
}
