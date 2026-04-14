<?php

namespace App\Http\Controllers;

use App\Models\Vehiculo;
use App\Models\VehiculoCaso;
use App\Models\Multimedia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VehiculoController extends Controller
{
    /**
     * Display a listing of the resource with AJAX support.
     */
    public function index(Request $request)
    {
        if (!request()->user()->hasAnyPermission(['vehiculos_all', 'vehiculos_listar'])) {
            abort(403, 'No tienes permiso para acceder a esta sección.');
        }

        if ($request->ajax()) {
            $query = Vehiculo::with('personas')
                ->orderBy('id', 'desc');

            if ($request->filled('search') && !$request->filled('filtro')) {
                $search = $request->search;
                $search = str_replace('%', ' ', $search);
                $query->where(function ($q) use ($search) {
                    $q->whereRaw('placa LIKE ?', ["%{$search}%"])
                        ->orWhereRaw('descripcion LIKE ?', ["%{$search}%"])
                        ->orWhereRaw('responsable LIKE ?', ["%{$search}%"])
                        ->orWhereRaw('caso_relacionado LIKE ?', ["%{$search}%"]);
                });
            }

            if ($request->filled('filtro') && $request->filled('search')) {
                $search = $request->search;
                $search = str_replace('%', ' ', $search);
                switch ($request->filtro) {
                    case 'placa':
                        $query->whereRaw('placa LIKE ?', ["%{$search}%"]);
                        break;
                    case 'descripcion':
                        $query->whereRaw('descripcion LIKE ?', ["%{$search}%"]);
                        break;
                    case 'responsable':
                        $query->whereRaw('responsable LIKE ?', ["%{$search}%"]);
                        break;
                    case 'caso_relacionado':
                        $query->whereRaw('caso_relacionado LIKE ?', ["%{$search}%"]);
                        break;
                    case 'ci_persona':
                        $query->whereHas('personas', function ($q) use ($search) {
                            $q->whereRaw('ci LIKE ?', ["%{$search}%"]);
                        });
                        break;
                    case 'nombres':
                        $query->whereHas('personas', function ($q) use ($search) {
                            $q->whereRaw(
                                "CONCAT(COALESCE(nombres, ''), ' ', COALESCE(apellidos, '')) LIKE ?",
                                ["%{$search}%"]
                            );
                        });
                        break;
                }
            }

            $vehiculos = $query->paginate($request->get('size', 10), ['*'], 'page', $request->get('page', 1));

            return response()->json([
                'datos' => $vehiculos->items(),
                'total' => $vehiculos->total(),
                'page' => $vehiculos->currentPage(),
                'success' => true,
            ]);
        }

        return view('vehiculos.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (!request()->user()->hasAnyPermission(['vehiculos_all', 'vehiculos_crear'])) {
            abort(403, 'No tienes permiso para crear vehículos.');
        }

        return view('vehiculos.form');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (!request()->user()->hasAnyPermission(['vehiculos_all', 'vehiculos_crear'])) {
            abort(403, 'No tienes permiso para crear vehículos.');
        }

        $validated = $request->validate([
            'placa' => 'required|string|unique:vehiculo,placa|max:20|min:3',
            'descripcion' => 'nullable|string|max:2500',
            'responsable' => 'nullable|string|max:255',
            'caso_relacionado' => 'nullable|string|max:255',
            'bsisa' => 'nullable|string|max:255',
            'ci_bsisa' => 'nullable|string|max:255',
            'ruat' => 'nullable|string|max:255',
            'anh' => 'nullable|string|max:255',
            'itb' => 'nullable|string|max:255',
            'soat' => 'nullable|string|max:255',
            'personas_asociadas' => 'nullable|json',
        ]);

        try {
            DB::beginTransaction();

            $vehiculo = Vehiculo::create($validated);

            // Procesar personas asociadas si existen
            if ($request->filled('personas_asociadas')) {
                $personas = json_decode($request->input('personas_asociadas'), true);

                if (is_array($personas) && count($personas) > 0) {
                    foreach ($personas as $persona) {
                        VehiculoCaso::create([
                            'vehiculo_id' => $vehiculo->id,
                            'persona_id' => $persona['persona_id'],
                            'tipo' => $persona['tipo'],
                            'numero_informacion' => $persona['caso'] ?? null,
                        ]);
                    }
                }
            }

            if($request->hasFile('fotosVehiculos')) {
                $fotos = $request->file('fotosVehiculos');
                foreach ($fotos as $foto) {
                    $nombreArchivo = $foto->getClientOriginalName();
                    $path = $foto->storeAs('vehiculos', $nombreArchivo  , 'public');
                    Multimedia::create([
                        'id_vehiculo' => $vehiculo->id,
                        'tipo' => 'foto_vehiculo',
                        'ruta' => $path,
                        'nombre_archivo' => $nombreArchivo
                    ]);
                }
            }

            DB::commit();

            return response()->json([
                'success' => 'Vehículo creado correctamente',
                'data' => $vehiculo->load('personas'),
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Error al crear vehículo'], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        if (!request()->user()->hasAnyPermission(['vehiculos_all', 'vehiculos_listar'])) {
            abort(403, 'No tienes permiso para ver vehículos.');
        }

        $vehiculo = Vehiculo::with(['casos.persona', 'cargios','cargios.estacionServicio','inspecciones'])->findOrFail($id);
        return view('vehiculos.detalles', ['vehiculo' => $vehiculo]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        if (!request()->user()->hasAnyPermission(['vehiculos_all', 'vehiculos_editar'])) {
            abort(403, 'No tienes permiso para editar vehículos.');
        }

        $vehiculo = Vehiculo::with('casos.persona')->findOrFail($id);
        return view('vehiculos.form', ['vehiculo' => $vehiculo]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        if (!request()->user()->hasAnyPermission(['vehiculos_all', 'vehiculos_editar'])) {
            abort(403, 'No tienes permiso para editar vehículos.');
        }

        $vehiculo = Vehiculo::findOrFail($id);

        $validated = $request->validate([
            'placa' => 'required|string|unique:vehiculo,placa,' . $id . '|max:20',
            'descripcion' => 'nullable|string|max:500',
            'responsable' => 'nullable|string|max:255',
            'caso_relacionado' => 'nullable|string|max:255',
            'bsisa' => 'nullable|string|max:255',
            'ci_bsisa' => 'nullable|string|max:255',
            'ruat' => 'nullable|string|max:255',
            'anh' => 'nullable|string|max:255',
            'itb' => 'nullable|string|max:255',
            'soat' => 'nullable|string|max:255',
            'personas_asociadas' => 'nullable|json',
        ]);

        try {
            DB::beginTransaction();

            $vehiculo->update($validated);

            // Procesar personas asociadas si existen
            if ($request->filled('personas_asociadas')) {
                // Obtener los IDs de personas en el nuevo array
                $personas = json_decode($request->input('personas_asociadas'), true);
                $nuevasPersonasIds = [];

                if (is_array($personas) && count($personas) > 0) {
                    foreach ($personas as $persona) {
                        $caso = VehiculoCaso::updateOrCreate(
                            [
                                'vehiculo_id' => $vehiculo->id,
                                'persona_id' => $persona['persona_id'],
                                'tipo' => $persona['tipo'],
                            ],
                            [
                                'numero_informacion' => $persona['caso'] ?? null,
                            ]
                        );
                        $nuevasPersonasIds[] = $caso->id;
                    }
                }

                // Eliminar personas que no están en el nuevo array
                VehiculoCaso::where('vehiculo_id', $vehiculo->id)
                    ->whereNotIn('id', $nuevasPersonasIds)
                    ->delete();
            } else {
                // Si no hay personas, eliminar todas
                VehiculoCaso::where('vehiculo_id', $vehiculo->id)->delete();
            }

            if($request->hasFile('fotosVehiculos')) {

                $fotos = $request->file('fotosVehiculos');
                foreach ($fotos as $foto) {
                    $nombreArchivo = $foto->getClientOriginalName();
                    $path = $foto->storeAs('vehiculos', $nombreArchivo, 'public');
                    Multimedia::create([
                        'id_vehiculo' => $vehiculo->id,
                        'tipo' => 'foto_vehiculo',
                        'ruta' => $path,
                        'nombre_archivo' => $nombreArchivo
                    ]);
                }
            }

            DB::commit();

            return response()->json([
                'success' => 'Vehículo actualizado correctamente',
                'data' => $vehiculo->load('personas'),
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Error al actualizar vehículo'. $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        if (!request()->user()->hasAnyPermission(['vehiculos_all', 'vehiculos_eliminar'])) {
            abort(403, 'No tienes permiso para eliminar vehículos.');
        }

        $vehiculo = Vehiculo::findOrFail($id);
        $vehiculo->delete();

        return response()->json([
            'success' => 'Vehículo eliminado correctamente',
        ]);
    }

    /**
     * Vincular una persona al vehículo directamente
     */
    public function vincularPersona(Request $request, string $id)
    {
        if (!request()->user()->hasAnyPermission(['vehiculos_all', 'vehiculos_vincular'])) {
            abort(403, 'No tienes permiso para vincular personas a vehículos.');
        }

        try {
            $vehiculo = Vehiculo::findOrFail($id);

            $validated = $request->validate([
                'persona_id' => 'required|exists:persona,id',
                'tipo' => 'required|string|max:255',
                'caso' => 'nullable|string|max:255',
            ]);

            // Verificar que no exista ya esta combinación
            $existe = VehiculoCaso::where('vehiculo_id', $vehiculo->id)
                ->where('persona_id', $validated['persona_id'])
                ->where('tipo', $validated['tipo'])
                ->exists();

            if ($existe) {
                return response()->json([
                    'error' => 'Esta persona ya está vinculada con este tipo',
                ], 422);
            }

            VehiculoCaso::create([
                'vehiculo_id' => $vehiculo->id,
                'persona_id' => $validated['persona_id'],
                'tipo' => $validated['tipo'],
                'caso' => $validated['caso'] ?? null,
            ]);

            return response()->json([
                'success' => 'Persona vinculada correctamente',
                'data' => $vehiculo->load('personas'),
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al vincular persona: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Desvincular una persona del vehículo
     */
    public function desvincularPersona(string $vehiculoId, string $personaId)
    {
        if (!request()->user()->hasAnyPermission(['vehiculos_all', 'vehiculos_vincular'])) {
            abort(403, 'No tienes permiso para desvincular personas.');
        }

        try {
            $vehiculo = Vehiculo::findOrFail($vehiculoId);

            VehiculoCaso::where('vehiculo_id', $vehiculo->id)
                ->where('persona_id', $personaId)
                ->delete();

            return response()->json([
                'success' => 'Persona desvinculada correctamente',
                'data' => $vehiculo->load('personas'),
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al desvincular persona'], 500);
        }
    }
}
