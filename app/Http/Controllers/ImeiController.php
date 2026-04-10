<?php

namespace App\Http\Controllers;

use App\Models\Imei;
use App\Models\Telefono;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ImeiController extends Controller
{
    /**
     * Display a listing of the resource with AJAX support.
     */
    public function index(Request $request)
    {
        if (!request()->user()->hasAnyPermission(['imeis_all', 'imeis_listar'])) {
            abort(403, 'No tienes permiso para acceder a esta sección.');
        }

        if ($request->ajax()) {
            $query = Imei::with(['telefonos', 'telefonos.persona'])
                ->orderBy('id', 'desc');

            if ($request->filled('search') && !$request->filled('filtro')) {
                $search = $request->search;
                $search = str_replace('%', ' ', $search);
                $query->where(function ($q) use ($search) {
                    $q->whereRaw('imei LIKE ?', ["%{$search}%"])
                        ->orWhereRaw('caracteristicas LIKE ?', ["%{$search}%"])
                        ->orWhereHas('telefonos', function ($q2) use ($search) {
                            $q2->whereRaw('numero_celular LIKE ?', ["%{$search}%"]);
                        });
                });
            }

            if ($request->filled('filtro') && $request->filled('search')) {
                $search = $request->search;
                $search = str_replace('%', ' ', $search);
                switch ($request->filtro) {
                    case 'imei':
                        $query->whereRaw('imei LIKE ?', ["%{$search}%"]);
                        break;
                    case 'caracteristicas':
                        $query->whereRaw('caracteristicas LIKE ?', ["%{$search}%"]);
                        break;
                    case 'numero':
                        //modificar la relacion muchos a muchos para buscar por numero de telefono
                        $query->whereHas('telefonos', function ($q) use ($search) {
                            $q->whereRaw('numero_celular LIKE ?', ["%{$search}%"]);
                        });
                        break;
                }
            }

            $imeis = $query->paginate($request->get('size', 10), ['*'], 'page', $request->get('page', 1));

            return response()->json([
                'datos' => $imeis->items(),
                'total' => $imeis->total(),
                'page' => $imeis->currentPage(),
            ]);
        }

        return view('imeis.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (!request()->user()->hasAnyPermission(['imeis_all', 'imeis_crear'])) {
            abort(403, 'No tienes permiso para realizar esta acción.');
        }

        $imei = new Imei();

        return view('imeis.formulario', compact('imei'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (!request()->user()->hasAnyPermission(['imeis_all', 'imeis_crear'])) {
            abort(403, 'No tienes permiso para realizar esta acción.');
        }

        $reglas = [
            'imei' => 'required|string|max:50|unique:imei,imei',
            'caracteristicas' => 'nullable|string|max:1000',
            'telefono_id' => 'nullable|exists:telefono,id',
        ];

        $request->validate($reglas);



        $data = $request->only('imei', 'caracteristicas', 'telefono_id');
        $imeiRecord = Imei::create($data);


        return response()->json([
            'success' => 'IMEI registrado correctamente.',
            'datos' => $imeiRecord->load('telefono.persona'),
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $datos = Imei::with('telefono.persona')->findOrFail($id);
        return view('imeis.show', compact('datos'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        if (!request()->user()->hasAnyPermission(['imeis_all', 'imeis_editar'])) {
            abort(403, 'No tienes permiso para realizar esta acción.');
        }

        $imei = Imei::with('telefono')->findOrFail($id);

        return view('imeis.formulario', compact('imei'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        if (!request()->user()->hasAnyPermission(['imeis_all', 'imeis_editar'])) {
            abort(403, 'No tienes permiso para realizar esta acción.');
        }

        $imei = Imei::findOrFail($id);

        $reglas = [
            'imei' => 'sometimes|required|string|max:50|unique:imei,imei,' . $id,
            'caracteristicas' => 'sometimes|nullable|string|max:1000',
            'telefono_id' => 'sometimes|nullable|exists:telefono,id',
        ];

        $request->validate($reglas);

        $imei->update($request->all());


        return response()->json([
            'success' => 'IMEI actualizado correctamente.',
            'datos' => $imei->load('telefono.persona'),
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        if (!request()->user()->hasAnyPermission(['imeis_all', 'imeis_eliminar'])) {
            abort(403, 'No tienes permiso para realizar esta acción.');
        }

        try {
            DB::beginTransaction();

            $imei = Imei::findOrFail($id);
            $imei->delete();

            DB::commit();

            return response()->json([
                'success' => 'IMEI eliminado correctamente.',
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'error' => 'Error al eliminar el IMEI: ' . $e->getMessage(),
            ], 500);
        }
    }

}
