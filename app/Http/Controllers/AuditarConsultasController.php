<?php

namespace App\Http\Controllers;

use App\Models\AuditarConsultas;
use App\Lib\AuditoriaHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AuditarConsultasController extends Controller
{
    /**
     * Display a listing of the audit logs.
     * Soporta vistas normales y solicitudes AJAX para tablas dinámicas.
     */
    public function index(Request $request)
    {
        // if (!request()->user()->hasAnyPermission(['auditar_consultas_all', 'auditar_consultas_listar'])) {
        //     abort(403, 'No tienes permiso para acceder a esta sección.');
        // }

        if ($request->ajax()) {
            return $this->ajaxIndex($request);
        }

        return view('auditar-consultas.index');
    }

    /**
     * Responder a solicitudes AJAX para cargar datos con paginación
     */
    private function ajaxIndex(Request $request)
    {
        $query = AuditarConsultas::with('usuario')
            ->when($request->filled('rol_usuario'), function ($q) use ($request) {
                $q->where('rol_usuario', $request->rol_usuario);
            })
            ->when($request->filled('modulo'), function ($q) use ($request) {
                $q->where('modulo', $request->modulo);
            })
            ->when($request->filled('search'), function ($q) use ($request) {
                $search = $request->search;
                $q->whereHas('usuario', function ($q2) use ($search) {
                    $q2->where('name', 'like', "%{$search}%")
                       ->orWhere('email', 'like', "%{$search}%");
                })
                ->orWhere('identificador', 'like', "%{$search}%");
            })
            ->when($request->filled('fecha_inicio') && $request->filled('fecha_fin'), function ($q) use ($request) {
                $q->whereBetween(DB::raw('DATE(created_at)'), [
                    $request->fecha_inicio,
                    $request->fecha_fin
                ]);
            })
            ->orderBy('created_at', 'desc');

        $registros = $query->paginate($request->get('size', 10), ['*'], 'page', $request->get('page', 1));

        // Procesar los datos para incluir información de los IDs accedidos
        $datos = $registros->items();
        foreach ($datos as $registro) {
            $registro->ids_accedidos_resueltos = AuditoriaHelper::resolverIdsAccedidos($registro->ids_accedidos);
        }

        return response()->json([
            'datos' => $datos,
            'total' => $registros->total(),
            'page' => $registros->currentPage(),
        ]);
    }

    /**
     * Mostrar detalles de un registro de auditoría
     */
    public function show($id)
    {
        // if (!request()->user()->hasAnyPermission(['auditar_consultas_all', 'auditar_consultas_ver'])) {
        //     abort(403, 'No tienes permiso para ver esto.');
        // }

        $auditoria = AuditarConsultas::with('usuario')->findOrFail($id);

        // Resolver IDs accedidos
        $auditoria->ids_accedidos_resueltos = AuditoriaHelper::resolverIdsAccedidos($auditoria->ids_accedidos);
        $auditoria->location_info = $auditoria->getLocationInfo();

        if (request()->ajax()) {
            return response()->json($auditoria);
        }

        return view('auditar-consultas.show', compact('auditoria'));
    }

    /**
     * Obtener módulos disponibles para filtrar
     */
    public function obtenerModulos()
    {
        $modulos = AuditarConsultas::selectRaw('DISTINCT modulo')
            ->orderBy('modulo')
            ->pluck('modulo')
            ->toArray();

        return response()->json(['modulos' => $modulos]);
    }

    /**
     * Obtener roles disponibles para filtrar
     */
    public function obtenerRoles()
    {
        $roles = AuditarConsultas::selectRaw('DISTINCT rol_usuario')
            ->orderBy('rol_usuario')
            ->pluck('rol_usuario')
            ->toArray();

        return response()->json(['roles' => $roles]);
    }
}

