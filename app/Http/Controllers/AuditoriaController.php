<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use OwenIt\Auditing\Models\Audit;

class AuditoriaController extends Controller
{
    /**
     * Mapeo de tipos de modelo a nombres legibles
     */
    private array $modelosNombres = [
        'App\Models\Cargio'              => 'Cargío',
        'App\Models\Delito'              => 'Delito',
        'App\Models\Division'            => 'División',
        'App\Models\EstacionServicio'    => 'Estación de Servicio',
        'App\Models\FichaRegistro'       => 'Ficha de Registro',
        'App\Models\FotosRegistro'       => 'Fotos de Registro',
        'App\Models\Imei'                => 'IMEI',
        'App\Models\ImeiTelefono'        => 'IMEI-Teléfono',
        'App\Models\InspeccionTecnica'   => 'Inspección Técnica',
        'App\Models\Juzgado'             => 'Juzgado',
        'App\Models\Mandamiento'         => 'Mandamiento',
        'App\Models\Multimedia'          => 'Multimedia',
        'App\Models\Pais'                => 'País',
        'App\Models\Persona'             => 'Persona',
        'App\Models\RegistroCriminal'    => 'Registro Criminal',
        'App\Models\Role'                => 'Rol',
        'App\Models\Telefono'            => 'Teléfono',
        'App\Models\TipoMandamiento'     => 'Tipo de Mandamiento',
        'App\Models\User'                => 'Usuario',
        'App\Models\Vehiculo'            => 'Vehículo',
        'App\Models\VehiculoCaso'        => 'Vehículo-Caso',
    ];

    /**
     * Mapeo de eventos a etiquetas legibles
     */
    private array $eventosNombres = [
        'created'  => 'Creado',
        'updated'  => 'Actualizado',
        'deleted'  => 'Eliminado',
        'restored' => 'Restaurado',
    ];

    /**
     * Display a listing of audits.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return $this->ajaxIndex($request);
        }

        return view('auditorias.index');
    }

    /**
     * Responder a solicitudes AJAX con paginación y filtros
     */
    private function ajaxIndex(Request $request)
    {
        $query = Audit::with('user')
            ->when($request->filled('modelo'), function ($q) use ($request) {
                $q->where('auditable_type', $request->modelo);
            })
            ->when($request->filled('evento'), function ($q) use ($request) {
                $q->where('event', $request->evento);
            })
            ->when($request->filled('usuario_id'), function ($q) use ($request) {
                $q->where('user_id', $request->usuario_id);
            })
            ->when($request->filled('search'), function ($q) use ($request) {
                $search = $request->search;
                $q->where(function ($q2) use ($search) {
                    $q2->where('auditable_id', 'like', "%{$search}%")
                       ->orWhere('ip_address', 'like', "%{$search}%")
                       ->orWhereHas('user', function ($q3) use ($search) {
                           $q3->where('name', 'like', "%{$search}%")
                              ->orWhere('email', 'like', "%{$search}%");
                       });
                });
            })
            ->when($request->filled('fecha_inicio') && $request->filled('fecha_fin'), function ($q) use ($request) {
                $q->whereBetween(DB::raw('DATE(created_at)'), [
                    $request->fecha_inicio,
                    $request->fecha_fin,
                ]);
            })
            ->orderBy('created_at', 'desc');

        $registros = $query->paginate(
            $request->get('size', 10),
            ['*'],
            'page',
            $request->get('page', 1)
        );

        $datos = collect($registros->items())->map(function ($audit) {
            return [
                'id'              => $audit->id,
                'evento'          => $audit->event,
                'evento_label'    => $this->eventosNombres[$audit->event] ?? ucfirst($audit->event),
                'modelo'          => $audit->auditable_type,
                'modelo_label'    => $this->modelosNombres[$audit->auditable_type] ?? class_basename($audit->auditable_type),
                'registro_id'     => $audit->auditable_id,
                'usuario'         => $audit->user ? $audit->user->name : 'Sistema',
                'usuario_email'   => $audit->user ? $audit->user->email : '—',
                'ip'              => $audit->ip_address ?? '—',
                'url'             => $audit->url ?? '—',
                'created_at'      => $audit->created_at?->format('d/m/Y H:i:s'),
                'campos_nuevos'   => count($audit->new_values ?? []),
                'campos_antiguos' => count($audit->old_values ?? []),
            ];
        });

        return response()->json([
            'datos' => $datos,
            'total' => $registros->total(),
            'page'  => $registros->currentPage(),
        ]);
    }

    /**
     * Ver detalles de un registro de auditoría
     */
    public function show(Request $request, $id)
    {
        $audit = Audit::with('user')->findOrFail($id);

        $data = [
            'id'             => $audit->id,
            'evento'         => $audit->event,
            'evento_label'   => $this->eventosNombres[$audit->event] ?? ucfirst($audit->event),
            'modelo'         => $audit->auditable_type,
            'modelo_label'   => $this->modelosNombres[$audit->auditable_type] ?? class_basename($audit->auditable_type),
            'registro_id'    => $audit->auditable_id,
            'usuario'        => $audit->user ? $audit->user->name : 'Sistema',
            'usuario_email'  => $audit->user ? $audit->user->email : '—',
            'ip'             => $audit->ip_address ?? '—',
            'url'            => $audit->url ?? '—',
            'user_agent'     => $audit->user_agent ?? '—',
            'tags'           => $audit->tags ?? '—',
            'old_values'     => $audit->old_values ?? [],
            'new_values'     => $audit->new_values ?? [],
            'created_at'     => $audit->created_at?->format('d/m/Y H:i:s'),
        ];

        return response()->json($data);
    }

    /**
     * Obtener lista de modelos disponibles para filtrar
     */
    public function obtenerModelos()
    {
        $modelos = Audit::selectRaw('DISTINCT auditable_type')
            ->orderBy('auditable_type')
            ->pluck('auditable_type')
            ->map(function ($tipo) {
                return [
                    'value' => $tipo,
                    'label' => $this->modelosNombres[$tipo] ?? class_basename($tipo),
                ];
            });

        return response()->json(['modelos' => $modelos]);
    }

    /**
     * Obtener lista de eventos disponibles para filtrar
     */
    public function obtenerEventos()
    {
        $eventos = Audit::selectRaw('DISTINCT event')
            ->orderBy('event')
            ->pluck('event')
            ->map(function ($evento) {
                return [
                    'value' => $evento,
                    'label' => $this->eventosNombres[$evento] ?? ucfirst($evento),
                ];
            });

        return response()->json(['eventos' => $eventos]);
    }
}
