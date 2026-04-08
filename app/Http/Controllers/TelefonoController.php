<?php

namespace App\Http\Controllers;

use App\Models\Telefono;
use App\Models\Persona;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TelefonoController extends Controller
{
    /**
     * Display a listing of the resource with AJAX support.
     */
    public function index(Request $request)
    {
        if (!request()->user()->hasAnyPermission(['telefonos_all', 'telefonos_listar'])) {
            abort(403, 'No tienes permiso para acceder a esta sección.');
        }

        if ($request->ajax()) {
            $query = Telefono::with(['persona', 'imeis'])
                ->orderBy('id', 'desc');

            if ($request->filled('search') && !$request->filled('filtro')) {
                $search = $request->search;
                $search = str_replace('%', ' ', $search);
                $query->where(function ($q) use ($search) {
                    $q->whereRaw('numero_celular LIKE ?', ["%{$search}%"])
                        ->orWhereRaw('caso LIKE ?', ["%{$search}%"])
                        ->orWhereRaw('persona_caso LIKE ?', ["%{$search}%"])
                        ->orWhereHas('persona', function ($q2) use ($search) {
                            $q2->whereRaw('ci LIKE ?', ["%{$search}%"])
                                ->orWhereRaw('CONCAT(COALESCE(nombres, ""), " ", COALESCE(apellidos, "")) LIKE ?', ["%{$search}%"]);
                        });

                });
            }



            if ($request->filled('filtro') && $request->filled('search')) {
                $search = $request->search;
                $search = str_replace('%', ' ', $search);
                switch ($request->filtro) {
                    case 'numero':
                        $query->whereRaw('numero_celular LIKE ?', ["%{$search}%"]);
                        break;
                    case 'imei':
                        $query->whereHas('imeis', function ($q) use ($search) {
                            $q->where('imei', 'LIKE', "%{$search}%");
                        });
                        break;
                    case 'ci_persona':
                        $query->whereRaw('ci LIKE ?', ["%{$search}%"]);
                        break;
                    case 'nombre_persona':
                        $query->whereRaw('CONCAT(COALESCE(nombres, ""), " ", COALESCE(apellidos, "")) LIKE ?', ["%{$search}%"]);
                        break;
                    case 'callap':
                        $query->whereRaw('callapp LIKE ?', ["%{$search}%"]);
                        break;
                    case 'truecall':
                        $query->whereRaw('truecall LIKE ?', ["%{$search}%"]);
                        break;
                    case 'uninet':
                        $query->whereRaw('uninet LIKE ?', ["%{$search}%"]);
                        break;
                    case 'respuesta_requerimiento':
                        $query->whereRaw('respuesta_requerimiento LIKE ?', ["%{$search}%"]);
                        break;

                }
            }

            $telefonos = $query->paginate($request->get('size', 10), ['*'], 'page', $request->get('page', 1));

            return response()->json([
                'datos' => $telefonos->items(),
                'total' => $telefonos->total(),
                'page' => $telefonos->currentPage(),
            ]);
        }

        return view('telefonos.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (!request()->user()->hasAnyPermission(['telefonos_all', 'telefonos_crear'])) {
            abort(403, 'No tienes permiso para realizar esta acción.');
        }

        $telefono = new Telefono();

        return view('telefonos.formulario', compact('telefono'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (!request()->user()->hasAnyPermission(['telefonos_all', 'telefonos_crear'])) {
            abort(403, 'No tienes permiso para realizar esta acción.');
        }

        $reglas = [
            'numero_celular' => 'required|string|max:25|unique:telefono,numero_celular',
            'persona_caso' => 'nullable|string|max:255',
            'caso' => 'nullable|string|max:255',
            'empresa' => 'nullable|string|max:150',
            'respuesta_requerimiento' => 'nullable|string|max:255',
            'persona_id' => 'nullable|exists:persona,id',
            'informacion' => 'nullable|string|max:500',
            'callapp' => 'nullable|string|max:150',
            'truecall' => 'nullable|string|max:150',
            'uninet' => 'nullable|string|max:150',
        ];

        $request->validate($reglas);

        try {
            DB::beginTransaction();

            $data = $request->except('imeis_asociados');
            $telefono = Telefono::create($data);

            DB::commit();

            return response()->json([
                'success' => 'Teléfono registrado correctamente.',
                'datos' => $telefono->load(['persona', 'imeis']),
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'error' => 'Error al crear el teléfono: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $datos = Telefono::with(['persona', 'imeis'])->findOrFail($id);
        return view('telefonos.show', compact('datos'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        if (!request()->user()->hasAnyPermission(['telefonos_all', 'telefonos_editar'])) {
            abort(403, 'No tienes permiso para realizar esta acción.');
        }

        $telefono = Telefono::with(['persona', 'imeis'])->findOrFail($id);

        return view('telefonos.formulario', compact('telefono'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        if (!request()->user()->hasAnyPermission(['telefonos_all', 'telefonos_editar'])) {
            abort(403, 'No tienes permiso para realizar esta acción.');
        }

        $telefono = Telefono::findOrFail($id);

        $reglas = [
            'numero_celular' => 'required|string|max:25|unique:telefono,numero_celular,' . $id,
            'persona_caso' => 'nullable|string|max:255',
            'caso' => 'nullable|string|max:255',
            'empresa' => 'nullable|string|max:150',
            'respuesta_requerimiento' => 'nullable|string|max:255',
            'persona_id' => 'nullable|exists:persona,id',
            'informacion' => 'nullable|string|max:500',
            'callapp' => 'nullable|string|max:150',
            'truecall' => 'nullable|string|max:150',
            'uninet' => 'nullable|string|max:150',
        ];

        $request->validate($reglas);

        try {
            DB::beginTransaction();

            $data = $request->except('imeis_asociados');
            $telefono->update($data);

            DB::commit();

            return response()->json([
                'success' => 'Teléfono actualizado correctamente.',
                'datos' => $telefono->load(['persona', 'imeis']),
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'error' => 'Error al actualizar el teléfono: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        if (!request()->user()->hasAnyPermission(['telefonos_all', 'telefonos_eliminar'])) {
            abort(403, 'No tienes permiso para realizar esta acción.');
        }

        try {
            DB::beginTransaction();

            $telefono = Telefono::findOrFail($id);
            $telefono->delete();

            DB::commit();

            return response()->json([
                'success' => 'Teléfono eliminado correctamente.',
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'error' => 'Error al eliminar el teléfono: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Vincular una persona a un teléfono
     */
    public function vincularPersona(Request $request, string $id)
    {
        $request->validate([
            'persona_id' => 'required|exists:persona,id'
        ]);

        try {
            $telefono = Telefono::findOrFail($id);
            $telefono->update(['persona_id' => $request->persona_id]);

            return response()->json([
                'success' => 'Persona vinculada correctamente.',
                'datos' => $telefono->load('persona'),
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al vincular persona: ' . $e->getMessage(),
            ], 500);
        }
    }
}
