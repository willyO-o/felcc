<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ciudadano;
use Illuminate\Support\Facades\DB;

class CiudadanoController extends Controller
{
    /**
     * Display a listing of the resource.
     * Soporta listar y las solicitudes AJAX para DataTable.
     */
    public function index(Request $request)
    {
        if (!request()->user()->hasAnyPermission(['ciudadanos_all', 'ciudadanos_listar'])) {
            abort(403, 'No tienes permiso para acceder a esta sección.');
        }

        if ($request->ajax()) {
            $query = Ciudadano::with('departamento');

            // Filtro por departamento
            if ($request->filled('id_departamento')) {
                $query->where('id_departamento', $request->id_departamento);
            }

            // Filtro por sexo
            if ($request->filled('sexo')) {
                $query->where('sexo', $request->sexo);
            }

            // Filtro por estado civil
            if ($request->filled('estado_civil')) {
                $query->where('estado_civil', $request->estado_civil);
            }

            // Filtro por estado registro
            if ($request->filled('estado_registro')) {
                $query->where('estado_registro', $request->estado_registro);
            }

            // Búsqueda simple (global)
            if ($request->filled('search')) {
                $search = $request->search;
                $search = str_replace('%', ' ', $search);
                $searchType = $request->get('search_type', '');

                $query->where(function ($q) use ($search, $searchType) {
                    if ($searchType === 'nombre_completo') {
                        $q->whereRaw("CONCAT(COALESCE(nombres, ''), ' ', COALESCE(ap_pat, ''), ' ', COALESCE(ap_mat, '')) LIKE ?", ["%{$search}%"]);
                    } elseif ($searchType === 'cedula') {
                        $q->whereRaw('cedula_act LIKE ?', ["%{$search}%"]);
                    } elseif ($searchType === 'ap_paterno') {
                        $q->whereRaw('ap_pat LIKE ?', ["%{$search}%"]);
                    } elseif ($searchType === 'ap_esposo') {
                        $q->whereRaw('ap_esp LIKE ?', ["%{$search}%"]);
                    } else {
                        // Búsqueda en todos los campos (valor por defecto)
                        $q->whereRaw('nombres LIKE ?', ["%{$search}%"])
                            ->orWhereRaw('ap_pat LIKE ?', ["%{$search}%"])
                            ->orWhereRaw('ap_mat LIKE ?', ["%{$search}%"])
                            ->orWhereRaw('cedula_act LIKE ?', ["%{$search}%"])
                            ->orWhereRaw('ciudadano LIKE ?', ["%{$search}%"])
                            ->orWhereRaw("CONCAT(COALESCE(nombres, ''), ' ', COALESCE(ap_pat, ''), ' ', COALESCE(ap_mat, '')) LIKE ?", ["%{$search}%"]);
                    }
                });
            }

            // Búsqueda avanzada por campo individual (LIKE)
            $advSimpleFields = [
                'adv_nombres'   => 'nombres',
                'adv_ap_pat'    => 'ap_pat',
                'adv_ap_mat'    => 'ap_mat',
                'adv_ap_esp'    => 'ap_esp',
                'adv_cedula'    => 'cedula_act',
                'adv_ocupacion' => 'ocupacion',
                'adv_mun'       => 'nom_mun',
                'adv_prov'      => 'nom_prov',
                'adv_departamento' => 'id_departamento',
            ];

            foreach ($advSimpleFields as $param => $column) {
                if ($request->filled($param)) {
                    $val = str_replace('%', ' ', $request->input($param));
                    $query->whereRaw("{$column} LIKE ?", ["%{$val}%"]);
                }
            }

            // Dirección: busca en dom_1 y dom_2
            if ($request->filled('adv_dom')) {
                $val = str_replace('%', ' ', $request->input('adv_dom'));
                $query->where(function ($q) use ($val) {
                    $q->whereRaw('dom_1 LIKE ?', ["%{$val}%"])
                      ->orWhereRaw('dom_2 LIKE ?', ["%{$val}%"]);
                });
            }

            // Fecha de nacimiento: búsqueda exacta
            if ($request->filled('adv_fecha_nac')) {
                $val = $request->input('adv_fecha_nac');
                $query->whereDate('fecha_nac', $val);
            }

            $query->orderBy('id', 'desc');

            $ciudadanos = $query->paginate($request->input('size', 10), ['*'], 'page', $request->input('page', 1));

            return response()->json([
                'datos' => $ciudadanos->items(),
                'total' => $ciudadanos->total(),
                'page' => $ciudadanos->currentPage(),
            ]);
        }

        // Obtener departamentos para el filtro
        $departamentos = \App\Models\Departamento::orderBy('departamento', 'asc')->get();

        return view('ciudadanos.index', compact('departamentos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (!request()->user()->hasAnyPermission(['ciudadanos_all', 'ciudadanos_crear'])) {
            abort(403, 'No tienes permiso para realizar esta acción.');
        }

        $ciudadano = new Ciudadano();
        return view('ciudadanos.formulario', compact('ciudadano'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (!request()->user()->hasAnyPermission(['ciudadanos_all', 'ciudadanos_crear'])) {
            abort(403, 'No tienes permiso para realizar esta acción.');
        }

        $reglas = [
            'ciudadano' => 'nullable|string|max:255',
            'tipo_cedula_act' => 'nullable|string|max:255',
            'cedula_act' => 'nullable|string|max:255',
            'ap_pat' => 'nullable|string|max:255',
            'ap_mat' => 'nullable|string|max:255',
            'ap_esp' => 'nullable|string|max:255',
            'nombres' => 'required|string|max:255',
            'sexo' => 'nullable|in:M,F,MASCULINO,FEMENINO',
            'estado_civil' => 'nullable|in:SOLTERO,CASADO,DIVORCIADO,VIUDO,UNION_LIBRE,CONYUGUE',
            'pais_nac' => 'nullable|string|max:180',
            'fecha_nac' => 'nullable|date',
            'mesa_ciudadano' => 'nullable|integer',
            'partida_mesa_ciudadano' => 'nullable|integer',
            'dom_1' => 'nullable|string',
            'dom_2' => 'nullable|string',
            'id_loc' => 'nullable|integer',
            'nom_dep' => 'nullable|string|max:255',
            'nom_prov' => 'nullable|string|max:255',
            'nom_mun' => 'nullable|string|max:255',
            'ocupacion' => 'nullable|string|max:255',
            'estado_registro' => 'nullable|in:0,1',
            'id_departamento' => 'nullable|integer',
        ];

        $request->validate($reglas);

        try {
            DB::beginTransaction();

            $data = $request->all();
            $data['fecha_ins'] = now();
            $data['estado_registro'] = $data['estado_registro'] ?? 1;

            $ciudadano = Ciudadano::create($data);

            DB::commit();

            return response()->json([
                'success' => 'Ciudadano creado correctamente.',
                'datos' => $ciudadano,
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'error' => 'Error al crear el ciudadano: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $datos = Ciudadano::findOrFail($id);

        return view('ciudadanos.show', [
            'datos' => $datos,
            'isAjax' => true,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $ciudadano = Ciudadano::findOrFail($id);
        return view('ciudadanos.formulario', compact('ciudadano'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $ciudadano = Ciudadano::findOrFail($id);

        $reglas = [
            'ciudadano' => 'nullable|string|max:255',
            'tipo_cedula_act' => 'nullable|string|max:255',
            'cedula_act' => 'nullable|string|max:255',
            'ap_pat' => 'nullable|string|max:255',
            'ap_mat' => 'nullable|string|max:255',
            'ap_esp' => 'nullable|string|max:255',
            'nombres' => 'required|string|max:255',
            'sexo' => 'nullable|in:M,F,MASCULINO,FEMENINO',
            'estado_civil' => 'nullable|in:SOLTERO,CASADO,DIVORCIADO,VIUDO,UNION_LIBRE,CONYUGUE',
            'pais_nac' => 'nullable|string|max:180',
            'fecha_nac' => 'nullable|date',
            'mesa_ciudadano' => 'nullable|integer',
            'partida_mesa_ciudadano' => 'nullable|integer',
            'dom_1' => 'nullable|string',
            'dom_2' => 'nullable|string',
            'id_loc' => 'nullable|integer',
            'nom_dep' => 'nullable|string|max:255',
            'nom_prov' => 'nullable|string|max:255',
            'nom_mun' => 'nullable|string|max:255',
            'ocupacion' => 'nullable|string|max:255',
            'estado_registro' => 'nullable|in:0,1',
            'id_departamento' => 'nullable|integer',
        ];

        $request->validate($reglas);

        try {
            DB::beginTransaction();

            $data = $request->all();
            $ciudadano->update($data);

            DB::commit();

            return response()->json([
                'success' => 'Ciudadano actualizado correctamente.',
                'datos' => $ciudadano,
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'error' => 'Error al actualizar el ciudadano: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            DB::beginTransaction();

            $ciudadano = Ciudadano::findOrFail($id);
            $ciudadano->delete();

            DB::commit();

            return response()->json([
                'success' => 'Ciudadano eliminado correctamente.',
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'error' => 'Error al eliminar el ciudadano: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Búsqueda rápida de ciudadanos
     */
    public function search(Request $request)
    {
        $query = $request->input('q', $request->input('query', ''));
        $query = str_replace('%', ' ', $query);

        $builder = Ciudadano::where(function ($q) use ($query) {
            $q->where('nombres', 'LIKE', "%{$query}%")
                ->orWhere('ap_pat', 'LIKE', "%{$query}%")
                ->orWhere('ap_mat', 'LIKE', "%{$query}%")
                ->orWhere('cedula_act', 'LIKE', "%{$query}%")
                ->orWhere('ciudadano', 'LIKE', "%{$query}%")
                ->orWhereRaw("CONCAT(COALESCE(nombres, ''), ' ', COALESCE(ap_pat, ''), ' ', COALESCE(ap_mat, ''),' - ', COALESCE(cedula_act, '')) LIKE ?", ["%{$query}%"]);
        });

        $builder->when($request->input('id'), function ($q) use ($request) {
            return $q->where('id', '!=', $request->input('id'));
        });

        $ciudadanos = $builder->limit(20)->get();

        return response()->json($ciudadanos);
    }

    /**
     * Mostrar modal para eliminar un ciudadano
     */
    public function showDeleteModal(Request $request, string $id)
    {
        if (!request()->user()->hasAnyPermission(['ciudadanos_all', 'ciudadanos_eliminar'])) {
            return response()->json([
                'error' => 'No tienes permiso para eliminar ciudadanos.'
            ], 403);
        }

        $ciudadano = Ciudadano::findOrFail($id);

        return view('ciudadanos.partials._frm-eliminar', compact('ciudadano'));
    }
}
