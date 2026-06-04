<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Persona;
use App\Models\Pais;
use Illuminate\Support\Facades\DB;
use App\Models\Multimedia;
use Illuminate\Support\Facades\Storage;

class PersonaController extends Controller
{
    /**
     * Display a listing of the resource.
     * Soporta listar y las solicitudes AJAX para DataTable.
     */
    public function index(Request $request)
    {

        if (!request()->user()->hasAnyPermission(['personas_all', 'personas_listar'])) {
            abort(403, 'No tienes permiso para acceder a esta sección.');
        }

        if ($request->ajax()) {
            $query = Persona::with('multimedia')
                ->when($request->filled('genero'), function ($q) use ($request) {
                    $q->whereRaw('genero = ?', [$request->genero]);
                })
                ->when($request->filled('estado_civil'), function ($q) use ($request) {
                    $q->whereRaw('estado_civil = ?', [$request->estado_civil]);
                })
                ->orderBy('id', 'desc');

            if (!$request->filled('filtro') && $request->filled('search')) {
                $query->when($request->filled('search'), function ($q) use ($request) {
                    $search = $request->search;
                    $search = str_replace('%', ' ', $search);
                    $q->where(function ($q2) use ($search) {
                        $q2->whereRaw('nombres LIKE ?', ["%{$search}%"])
                            ->orWhereRaw('apellidos LIKE ?', ["%{$search}%"])
                            ->orWhereRaw('ci LIKE ?', ["%{$search}%"])
                            ->orWhereRaw('CONCAT(ci, "-", complemento) LIKE ?', ["%{$search}%"])
                            ->orWhereRaw("CONCAT(COALESCE(nombres, ''), ' ', COALESCE(apellidos, '')) LIKE ?", ["%{$search}%"]);
                    });
                });
            } else if ($request->filled('search') && $request->filled('filtro')) {
                $search = $request->search;
                $search = str_replace('%', ' ', $search);
                switch ($request->filtro) {
                    case 'nombre':
                        $query->whereRaw('nombres  LIKE ?', ["%{$search}%"]);
                        break;
                    case 'apellidos':
                        $query->whereRaw('apellidos  LIKE ?', ["%{$search}%"]);
                        break;
                    case 'ci':
                        $query->whereRaw('ci  LIKE ?', ["%{$search}%"])
                            ->orWhereRaw('CONCAT(ci, "-", complemento)  LIKE ?', ["%{$search}%"]);
                        break;
                    case 'nombre_padre':
                        $query->whereRaw('padre  LIKE ?', ["%{$search}%"]);
                        break;
                    case 'nombre_madre':
                        $query->whereRaw('madre  LIKE ?', ["%{$search}%"]);
                        break;
                }
            }

            if ($request->filled('visible')) {
                switch ($request->input('visible', 'activos')) {
                    case 'todos':
                        $query->withTrashed();
                        break;
                    case 'activos':
                        break;
                    case 'eliminados':
                        $query->onlyTrashed();
                        break;
                }
            }

            $personas = $query->paginate($request->get('size', 10), ['*'], 'page', $request->get('page', 1));

            return response()->json([
                'datos' => $personas->items(),
                'total' => $personas->total(),
                'page' => $personas->currentPage(),
            ]);
        }

        return view('personas.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (!request()->user()->hasAnyPermission(['personas_all', 'personas_crear'])) {
            abort(403, 'No tienes permiso para realizar esta acción.');
        }
        $persona = new Persona();
        $paises = Pais::all();
        return view('personas.formulario', compact('persona', 'paises'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        if (!request()->user()->hasAnyPermission(['personas_all', 'personas_crear'])) {
            abort(403, 'No tienes permiso para realizar esta acción.');
        }


        $reglas = [
            'nombres' => 'required|string|max:255',
            'apellidos' => 'nullable|string|max:255',
            'ci' => 'nullable|string|max:25',
            'fecha_nacimiento' => 'nullable|date|before:today',
            'domicilio' => 'nullable|string|max:500',
            'telefono' => 'nullable|string|max:25',
            'lugar_nacimiento' => 'nullable|string|max:250',
            'complemento' => 'nullable|string|max:40',
            'genero' => 'nullable|in:MASCULINO,FEMENINO',
            'estado_civil' => 'nullable|in:SOLTERO,CASADO,DIVORCIADO,VIUDO,CONYUGUE',
            'nombre_conyuge' => 'nullable|string|max:250',
            'ocupacion' => 'nullable|string|max:150',
            'id_pais' => 'nullable|exists:pais,id',
            'fotos' => 'nullable|array',
            'fotos.*' => 'file|mimes:jpeg,png,jpg,webp|max:2048',
        ];

        $request->validate($reglas);

        try {
            DB::beginTransaction();

            $data = $request->all();

            $persona = Persona::create($data);

            $fotos = $request->file('fotos');
            if ($fotos) {
                foreach ($fotos as $foto) {
                    $nombreArchivo = $foto->hashName();
                    $ruta = $foto->storeAs('personas', $nombreArchivo, 'public');
                    if (Storage::disk('public')->exists('personas/' . $nombreArchivo)) {
                        Multimedia::create([
                            'tipo' => 'persona',
                            'ruta' => $ruta,
                            'nombre_archivo' => $nombreArchivo,
                            'id_persona' => $persona->id,
                        ]);
                    }
                }
            }

            DB::commit();

            $persona->load('multimedia');

            return response()->json([
                'success' => 'Persona creada correctamente.',
                'datos' => $persona,
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'error' => 'Error al crear la persona: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $datos = Persona::withTrashed()->with(['multimedia', 'registroCriminal', 'mandamientos', 'vehiculos', 'telefonos'])->findOrFail($id);
        return view('personas.show', [
            'datos' => $datos,
            'isAjax' => true,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $persona = Persona::with('multimedia')->findOrFail($id);
        $paises = Pais::all();
        return view('personas.formulario', compact('persona', 'paises'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $persona = Persona::findOrFail($id);

        // Si solo se está vinculando un documento, hacer validación simplificada
        if ($request->filled('url_documento') && $request->only('url_documento', '_token')) {
            $request->validate([
                'url_documento' => 'required|url|max:500',
            ]);

            try {
                $persona->update(['url_documento' => $request->url_documento]);
                return response()->json([
                    'success' => 'Documento vinculado correctamente.',
                    'datos' => $persona,
                ], 200);
            } catch (\Exception $e) {
                return response()->json([
                    'error' => 'Error al vincular el documento: ' . $e->getMessage(),
                ], 500);
            }
        }

        // Validación completa para edición de persona
        $reglas = [
            'nombres' => 'required|string|max:255',
            'apellidos' => 'nullable|string|max:255',
            'ci' => 'nullable|string|max:25',
            'fecha_nacimiento' => 'nullable|date|before:today',
            'domicilio' => 'nullable|string|max:500',
            'telefono' => 'nullable|string|max:25',
            'lugar_nacimiento' => 'nullable|string|max:250',
            'complemento' => 'nullable|string|max:40',
            'genero' => 'nullable|in:MASCULINO,FEMENINO',
            'estado_civil' => 'nullable|in:SOLTERO,CASADO,DIVORCIADO,VIUDO,CONYUGUE',
            'nombre_conyuge' => 'nullable|string|max:250',
            'ocupacion' => 'nullable|string|max:150',
            'id_pais' => 'nullable|exists:pais,id',
            'url_documento' => 'nullable|url|max:500',
            'fotos' => 'nullable|array',
            'fotos.*' => 'file|mimes:jpeg,png,jpg,webp|max:2048',
        ];

        $request->validate($reglas);

        try {
            DB::beginTransaction();

            $data = $request->all();

            $persona->update($data);

            // Procesar nuevas fotos si las hay
            $fotos = $request->file('fotos');
            if ($fotos) {
                foreach ($fotos as $foto) {
                    $nombreArchivo = $foto->hashName();
                    $ruta = $foto->storeAs('personas', $nombreArchivo, 'public');
                    if (Storage::disk('public')->exists('personas/' . $nombreArchivo)) {
                        Multimedia::create([
                            'tipo' => 'persona',
                            'ruta' => $ruta,
                            'nombre_archivo' => $nombreArchivo,
                            'id_persona' => $persona->id,
                        ]);
                    }
                }
            }

            DB::commit();

            $persona->load('multimedia');

            return response()->json([
                'success' => 'Persona actualizada correctamente.',
                'datos' => $persona,
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'error' => 'Error al actualizar la persona: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {

        // return request()->all();
        try {
            DB::beginTransaction();
            $persona = Persona::findOrFail($id);

            $request = request();

            if ($request->input('persona_migrar_id')) {

                $personaMigracion = Persona::findOrFail($request->input('persona_migrar_id'));

                $persona->migrateRelationsTo($personaMigracion);
            }

            if ($request->input('eliminar_completo')) {

                $multimedia = Multimedia::where('id_persona', $id)->get();
                foreach ($multimedia as $file) {
                    if (Storage::disk('public')->exists($file->ruta)) {
                        Storage::disk('public')->delete($file->ruta);
                    }
                    $file->delete();
                }

                $persona->deleteRelationsData();
                $persona->forceDelete();
            } else {
                $persona->delete();
            }
            DB::commit();
            return response()->json([
                'success' => 'Persona eliminada correctamente.',
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'error' => 'Error al eliminar la persona: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function search(Request $request)
    {
        $query = $request->input('q', $request->input('query', ''));
        $query = str_replace('%', ' ', $query);

        $builder = Persona::where(function ($q) use ($query) {
            $q->where('nombres', 'LIKE', "%{$query}%")
                ->orWhere('apellidos', 'LIKE', "%{$query}%")
                ->orWhere('ci', 'LIKE', "%{$query}%")
                ->orWhereRaw("CONCAT(COALESCE(nombres, ''), ' ', COALESCE(apellidos, ''),' - ', COALESCE(ci, '')) LIKE ?", ["%{$query}%"])
                ->orWhereRaw("CONCAT(COALESCE(apellidos, ''), ' ', COALESCE(nombres, ''),' - ', COALESCE(ci, '')) LIKE ?", ["%{$query}%"])
                ->orWhereRaw("CONCAT(COALESCE(ci, ''), ' - ', COALESCE(apellidos, ''), ' ', COALESCE(nombres, '')) LIKE ?", ["%{$query}%"]);
        });

        $builder->when($request->input('id'), function ($q) use ($request) {
            return $q->where('id', '!=', $request->input('id'));
        });

        $personas = $builder->limit(20)->get();

        return response()->json($personas);
    }

    /**
     * Mostrar modal para migrar y eliminar una persona
     */
    public function showDeleteModal(Request $request, string $id)
    {
        if (!request()->user()->hasAnyPermission(['personas_all', 'personas_eliminar'])) {
            return response()->json([
                'error' => 'No tienes permiso para eliminar personas.'
            ], 403);
        }

        $persona = Persona::findOrFail($id);
        // Obtener el resumen de datos relacionados
        $resumen = $persona->getResumenDatosRelacionados();

        return  view('personas.partials._frm-eliminar', compact('persona', 'resumen'));
    }

    /**
     * Migrar datos relacionados y eliminar persona
     */
    public function restore( string $id)
    {
        $persona = Persona::withTrashed()->findOrFail($id);

        $persona->restore();
        return response()->json([
            'success' => 'Persona restaurada correctamente.',
            'datos' => $persona,
        ], 200);


    }

    public function checkCI(Request $request)
    {
        $ci = $request->input('ci');
        $complemento = $request->input('complemento');

        $persona = Persona::where('ci', $ci)
            ->where(function ($q) use ($complemento) {
                if ($complemento) {
                    $q->where('complemento', $complemento);
                } else {
                    $q->whereNull('complemento');
                }
            })
            ->first();

        return response()->json([
            'data' => $persona,
        ]);

    }
}
