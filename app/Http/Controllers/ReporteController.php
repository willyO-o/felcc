<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Lib\ReportesPdf;
use App\Models\FichaRegistro;
use App\Models\Mandamiento;
use App\Models\RegistroCriminal;
use App\Models\Persona;
use App\Models\Telefono;
use App\Models\Imei;
use App\Models\Vehiculo;
use OpenSpout\Common\Entity\Row;
use OpenSpout\Writer\CSV\Writer;

class ReporteController extends Controller
{
    protected $reportesPdf;

    public function __construct()
    {
        $this->reportesPdf = new ReportesPdf();
    }

    /**
     * Mostrar interfaz principal de reportes
     */
    public function index()
    {
        if (!request()->user()->hasAnyPermission(['reporte_all'])) {
            abort(403, 'No tienes permiso para acceder a esta sección.');
        }
        $reportes = [
            [
                'titulo' => 'Mandamientos',
                'descripcion' => 'Exporta todos los mandamientos registrados',
                'icono' => 'mdi-briefcase-check',
                'color' => 'primary',
                'ruta_csv' => 'reportes.exportar',
                'ruta_pdf' => 'reportes.exportar',
                'params' => ['tipo' => 'mandamientos']
            ],
            [
                'titulo' => 'Registro Criminal',
                'descripcion' => 'Exporta el registro criminal de personas',
                'icono' => 'mdi-alert-box',
                'color' => 'danger',
                'ruta_csv' => 'reportes.exportar',
                'ruta_pdf' => 'reportes.exportar',
                'params' => ['tipo' => 'registro-criminal']
            ],
            [
                'titulo' => 'Personas',
                'descripcion' => 'Exporta lista de personas registradas',
                'icono' => 'mdi-account-multiple',
                'color' => 'info',
                'ruta_csv' => 'reportes.exportar',
                'ruta_pdf' => 'reportes.exportar',
                'params' => ['tipo' => 'personas']
            ],
            [
                'titulo' => 'Celulares',
                'descripcion' => 'Exporta teléfonos y números celulares',
                'icono' => 'mdi-phone',
                'color' => 'success',
                'ruta_csv' => 'reportes.exportar',
                'ruta_pdf' => 'reportes.exportar',
                'params' => ['tipo' => 'celulares']
            ],
            // [
            //     'titulo' => 'IMEIs',
            //     'descripcion' => 'Exporta códigos IMEI de dispositivos',
            //     'icono' => 'mdi-sim',
            //     'color' => 'warning',
            //     'ruta_csv' => 'reportes.exportar',
            //     'ruta_pdf' => 'reportes.exportar',
            //     'params' => ['tipo' => 'imeis']
            // ],
            // [
            //     'titulo' => 'Vehículos',
            //     'descripcion' => 'Exporta vehículos y datos asociados',
            //     'icono' => 'mdi-car',
            //     'color' => 'secondary',
            //     'ruta_csv' => 'reportes.exportar',
            //     'ruta_pdf' => 'reportes.exportar',
            //     'params' => ['tipo' => 'vehiculos']
            // ]
        ];

        return view('reportes.index', compact('reportes'));
    }

    /**
     * Mostrar formulario de filtros para un tipo de reporte
     */
    public function formulario(string $tipo)
    {
        $datos = [
            'mandamientos' => Mandamiento::count(),
            'registro_criminal' => RegistroCriminal::count(),
            'personas' => Persona::count(),
            'celulares' => Telefono::count(),
            'imeis' => Imei::count(),
            'vehiculos' => Vehiculo::count(),
        ];

        return view('reportes.formulario', compact('tipo', 'datos'));
    }

    /**
     * Exportar reporte en CSV o PDF
     */
    public function exportar(Request $request)
    {

        if (!request()->user()->hasAnyPermission(['reporte_all'])) {
            abort(403, 'No tienes permiso para acceder a esta sección.');
        }

        $tipo = $request->get('tipo');
        $formato = $request->get('formato', 'csv');

        if ($formato === 'pdf') {
            return $this->exportarPDF($tipo, $request);
        } else {
            return $this->exportarCSV($tipo, $request);
        }
    }

    /**
     * Exportar a CSV
     */
    private function exportarCSV(string $tipo, Request $request,)
    {

        switch ($tipo) {
            case 'mandamientos':
                return $this->obtenerMandamientos($request);
                break;
            case 'registro-criminal':
                return $this->obtenerRegistroCriminal($request);
                break;
            case 'personas':
                return $this->obtenerPersonas($request);
                break;
            case 'celulares':
                return $this->obtenerCelulares($request);
                break;
            case 'imeis':
                return $this->obtenerImeis($request);
                break;
            case 'vehiculos':
                return $this->obtenerVehiculos($request);
                break;
        }

        return abort(400, 'Tipo de reporte no válido');
    }

    /**
     * Exportar a PDF
     */
    private function exportarPDF($tipo, Request $request)
    {
        // Aquí irá la lógica de PDF más adelante
        return redirect()->back()->with('info', 'La exportación a PDF será implementada próximamente.');
    }

    /**
     * Obtener datos de Mandamientos
     */
    private function obtenerMandamientos($request)
    {



        return response()->streamDownload(function () {

            $writer = new Writer();
            $writer->openToFile('php://output'); // Escribimos directamente al flujo de salida
            $headerRow = Row::fromValues(['Nro.', 'HOJA DE RUTA O MEMORANDUM', 'NOMBRES', 'APELLIDOS', 'TIPO DE MANDAMIENTO', 'ORIGINAL O FOTOCOPIA', 'DELITO', 'JUZGADO', 'ESTADO', 'DOMICILIO', 'C.I.', 'TELEFONO', 'ASIGNADO', 'ACTIVIDADES REALIZADAS', 'FECHA DE EJECUCIÓN', 'DETALLE EJECUCIÓN']);
            $writer->addRow($headerRow);
            $contador = 1;

            Mandamiento::with(['persona', 'delito', 'juzgado', 'tipoMandamiento'])->cursor()->each(function ($mandamiento) use ($writer, &$contador) {
                $row = Row::fromValues([
                    $contador++,
                    $mandamiento->hoja_ruta,
                    $mandamiento->persona->nombres ?? '',
                    $mandamiento->persona->apellidos ?? '',
                    $mandamiento->tipoMandamiento->tipo_mandamiento ?? '',
                    $mandamiento->tipo_documento,
                    $mandamiento->delito->nombre_delito ?? '',
                    $mandamiento->juzgado->nombre_juzgado ?? '',
                    $mandamiento->estado,
                    $mandamiento->domicilio,
                    $mandamiento->persona->ci ?? '',
                    $mandamiento->telefono,
                    $mandamiento->asignado,
                    $mandamiento->actividades_realizadas,
                    $mandamiento->fecha_ejecucion,
                    $mandamiento->detalle_ejecucion
                ]);
                $writer->addRow($row);
            });

            $writer->close();
        }, 'mandamientos_' . date('Y-m-d_His') . '.csv');
    }

    /**
     * Obtener datos de Registro Criminal
     */
    private function obtenerRegistroCriminal($request)
    {
        return response()->streamDownload(function () {

            $writer = new Writer();
            $writer->openToFile('php://output'); // Escribimos directamente al flujo de salida
            $headerRow = Row::fromValues(['Nro. REGISTRO', 'FECHA REGISTRO', 'NOMBRES', 'APELLIDOS', 'C.I.', 'CUD', 'ALIAS', 'NOMBRE SUPUESTO', 'DIVISION', 'ESPECIALIDAD', 'NACIONALIDAD', 'EDAD APROXIMADA', 'ZONAS OPERA', 'MODUS OPERANDI', 'RASGOS', 'ESTATURA', 'PESO', 'CARACTERISTICAS PARTICULARES', 'HIJOS', 'OBSERVACIONES']);
            $writer->addRow($headerRow);

            RegistroCriminal::with(['persona', 'pais', 'division'])->cursor()->each(function ($registro) use ($writer, &$contador) {
                $row = Row::fromValues([
                    $registro->nro_registro,
                    $registro->fecha_registro?->format('d/m/Y'),
                    $registro->persona->nombres ?? '',
                    $registro->persona->apellidos ?? '',
                    $registro->persona->ci ?? '',
                    $registro->cud,
                    $registro->alias,
                    $registro->nombre_supuesto,
                    $registro->division->division,
                    $registro->especialidad,
                    $registro->pais?->gentilicio ?? '',
                    $registro->edad_aproximada,
                    $registro->zonas_opera,
                    $registro->modus_operandi,
                    $registro->rasgos,
                    $registro->estatura,
                    $registro->peso,
                    $registro->caracteristicas_particulares,
                    $registro->hijos,
                    $registro->observaciones,

                ]);
                $writer->addRow($row);
            });

            $writer->close();
        }, 'registro_criminal_' . date('Y-m-d_His') . '.csv');
    }

    /**
     * Obtener datos de Personas
     */
    private function obtenerPersonas($request)
    {
        return response()->streamDownload(function () {

            $writer = new Writer();
            $writer->openToFile('php://output'); // Escribimos directamente al flujo de salida
            $headerRow = Row::fromValues(['Nro. REGISTRO', 'NOMBRES', 'APELLIDOS', 'C.I.', 'DATOS_SEGIP', 'ESTADO', 'RESPONSABLE', 'GRUPO SANGUINEO', 'ALIAS', 'PADRE', 'MADRE', 'OCUPACION', 'ESTADO CIVIL', 'NOMBRE CONYUGE', 'FECHA NACIMIENTO', 'LUGAR NACIMIENTO', 'DOMICILIO']);
            $writer->addRow($headerRow);
            $contador = 1;

            Persona::with(['persona'])->cursor()->each(function ($persona) use ($writer, &$contador) {
                $row = Row::fromValues([
                    $contador++,
                    $persona->nombres,
                    $persona->apellidos,
                    $persona->ci,
                    $persona->datos_segip,
                    $persona->estado_investigacion,
                    $persona->responsable,
                    $persona->grupo_sanguineo,
                    $persona->alias,
                    $persona->padre,
                    $persona->madre,
                    $persona->ocupacion,
                    $persona->estado_civil,
                    $persona->nombre_conyuge,
                    $persona->fecha_nacimiento?->format('d/m/Y'),
                    $persona->lugar_nacimiento,
                    $persona->domicilio,

                ]);
                $writer->addRow($row);
            });

            $writer->close();
        }, 'personas_' . date('Y-m-d_His') . '.csv');
    }

    /**
     * Obtener datos de Celulares
     */
    private function obtenerCelulares($request)
    {
        return response()->streamDownload(function () {

            $writer = new Writer();
            $writer->openToFile('php://output'); // Escribimos directamente al flujo de salida
            $headerRow = Row::fromValues(['Nro.', 'NUMERO DE CELULAR', 'PERSONA DEL CASO', 'CASO', 'EMPRESA', 'RESP A REQ', 'C.I.', 'INFO', 'CALLAPP', 'TRUECALL', 'UNINET']);
            $writer->addRow($headerRow);
            $contador = 1;

            Telefono::with(['persona'])->cursor()->each(function ($telefono) use ($writer, &$contador) {
                $row = Row::fromValues([
                    $contador++,
                    $telefono->numero_celular,
                    $telefono->persona_caso,
                    $telefono->caso,
                    $telefono->empresa,
                    $telefono->persona?->nombres . ' ' . $telefono->persona?->apellidos,
                    $telefono->persona?->ci,
                    $telefono->informacion ?? '',
                    $telefono->callapp,
                    $telefono->truecall,
                    $telefono->uninet,
                ]);
                $writer->addRow($row);
            });

            $writer->close();
        }, 'telefonos_' . date('Y-m-d_His') . '.csv');
    }

    /**
     * Obtener datos de IMEIs
     */
    private function obtenerImeis($request)
    {
        return []; // Placeholder - a completar
    }

    /**
     * Obtener datos de Vehículos
     */
    private function obtenerVehiculos($request)
    {
        return []; // Placeholder - a completar
    }

    /**
     * Generar archivo CSV
     */
    private function generarCSV($datos, $nombreArchivo)
    {
        // Placeholder - a completar
        return response()->json(['message' => 'CSV será generado aquí']);
    }

    public function pdfFicha($codigo)
    {
        $fichaRegistro = FichaRegistro::whereRaw('MD5(id) = ?', [$codigo])->firstOrFail();
        $this->reportesPdf->fichaRegistro($fichaRegistro);
        exit;
    }
}
