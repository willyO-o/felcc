<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mandamiento;
use App\Models\Persona;
use App\Models\Delito;
use App\Models\Juzgado;
use App\Models\TipoMandamiento;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class MandamientoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Si es una petición AJAX, devolver los datos para DataTables
        if ($request->ajax()) {
            return $this->getDataForDataTable($request);
        }

        // Si no es AJAX, mostrar la vista
        return view('mandamientos.index');
    }

    /**
     * Obtener datos para DataTables usando Yajra (Server-Side)
     */
    private function getDataForDataTable(Request $request)
    {
        $mandamientos = Mandamiento::select(
                'mandamiento.id',
                'mandamiento.hoja_ruta',
                'mandamiento.estado',
                'mandamiento.tipo_documento',
                'mandamiento.created_at',
                DB::raw("CONCAT(persona.nombre, ' ', COALESCE(persona.paterno, ''), ' ', COALESCE(persona.materno, '')) as nombre_completo"),
                'persona.ci',
                'tipo_mandamiento.tipo_mandamiento',
                'delito.nombre_delito',
                'juzgado.nombre_juzgado'
            )
            ->leftJoin('persona', 'mandamiento.id_persona', '=', 'persona.id')
            ->leftJoin('delito', 'mandamiento.id_delito', '=', 'delito.id')
            ->leftJoin('juzgado', 'mandamiento.id_juzgado', '=', 'juzgado.id')
            ->leftJoin('tipo_mandamiento', 'mandamiento.id_tipo_mandamiento', '=', 'tipo_mandamiento.id');

        return DataTables::of($mandamientos)
            ->addIndexColumn()
            ->addColumn('hoja_ruta', function ($row) {
                return $row->hoja_ruta ?? 'N/A';
            })
            ->addColumn('nombre_completo', function ($row) {
                return $row->nombre_completo ?? 'N/A';
            })
            ->addColumn('ci', function ($row) {
                return $row->ci ?? 'N/A';
            })
            ->addColumn('tipo_mandamiento', function ($row) {
                return $row->tipo_mandamiento ?? 'N/A';
            })
            ->addColumn('tipo_documento', function ($row) {
                if (!$row->tipo_documento || $row->tipo_documento === 'N/A') {
                    return '<span class="badge bg-secondary">N/A</span>';
                }
                return '<span class="badge bg-info">' . $row->tipo_documento . '</span>';
            })
            ->addColumn('delito', function ($row) {
                return $row->nombre_delito ?? 'N/A';
            })
            ->addColumn('juzgado', function ($row) {
                return $row->nombre_juzgado ?? 'N/A';
            })
            ->addColumn('estado', function ($row) {
                $badges = [
                    'PENDIENTE' => 'bg-warning',
                    'EJECUTADO' => 'bg-success',
                    'ANULADO' => 'bg-danger',
                    'EN PROCESO' => 'bg-info'
                ];
                $estado = $row->estado ?? 'N/A';
                $badgeClass = $badges[$estado] ?? 'bg-secondary';
                return '<span class="badge ' . $badgeClass . '">' . $estado . '</span>';
            })
            ->addColumn('acciones', function ($row) {
                return $this->getActionButtons($row->id);
            })
            ->rawColumns(['tipo_documento', 'estado', 'acciones'])
            ->make(true);
    }

    /**
     * Generar botones de acción
     */
    private function getActionButtons($id)
    {
        return '
            <div class="d-flex gap-2">
                <a href="'.route('mandamientos.show', $id).'" class="btn btn-sm btn-info" title="Ver">
                    <i class="ri-eye-line"></i>
                </a>
                <a href="'.route('mandamientos.edit', $id).'" class="btn btn-sm btn-warning" title="Editar">
                    <i class="ri-edit-line"></i>
                </a>
                <button type="button" class="btn btn-sm btn-danger btn-delete" data-id="'.$id.'" title="Eliminar">
                    <i class="ri-delete-bin-line"></i>
                </button>
            </div>
        ';
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $mandamientos = new Mandamiento();
        return view('mandamientos.formulario', compact('mandamientos'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}

