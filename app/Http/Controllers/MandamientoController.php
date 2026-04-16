<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mandamiento;
use App\Models\Multimedia;
use App\Models\TipoMandamiento;
use App\Models\AuditarConsultas;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class MandamientoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if (!$request->user()->hasAnyPermission(['mandamientos_all', 'mandamientos_listar'])) {
            abort(403, 'No tienes permiso para acceder a esta sección.');
        }
        // Si es una petición AJAX, devolver los datos para DataTables
        if ($request->ajax()) {
            $mandamientos = Mandamiento::getMandamientos($request->all())
                ->paginate($request->get('size', 10), ['*'], 'page', $request->get('page', 1));

            return response()->json([
                'datos' => $mandamientos->items(),
                'total' => $mandamientos->total(),
                'page' => $mandamientos->currentPage(),
            ]);
        }

        // Si no es AJAX, mostrar la vista
        $estados = Mandamiento::select('estado')->groupBy('estado')->get()->pluck('estado');

        return view('mandamientos.index', compact('estados'));
    }



    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (!request()->user()->hasAnyPermission(['mandamientos_all', 'mandamientos_crear'])) {
            abort(403, 'No tienes permiso para acceder a esta sección.');
        }
        $estados = Mandamiento::select('estado')->groupBy('estado')->get()->pluck('estado');
        return view('mandamientos.formulario', compact('estados'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        if (!request()->user()->hasAnyPermission(['mandamientos_all', 'mandamientos_crear'])) {
            abort(403, 'No tienes permiso para acceder a esta sección.');
        }

        $request->validate(Mandamiento::$rules);


        try {

            DB::beginTransaction();

            $mandamiento = Mandamiento::create($request->all());
            $imagenMandamiento = $request->file('imagen_mandamiento');

            if ($imagenMandamiento) {
                $nombreArchivo = $imagenMandamiento->hashName();

                $extension = $request->file('imagen_mandamiento')->getClientOriginalExtension();
                $directorio = $extension == 'pdf' ? 'mandamientos-pdf' : 'mandamientos';


                $ruta =  $imagenMandamiento->storeAs($directorio, $nombreArchivo, 'public');
                if (!Storage::disk('public')->exists($directorio . '/' . $nombreArchivo)) {
                    throw new \Exception('Error al guardar la imagen del mandamiento.');
                }
                $multimedia = Multimedia::create([
                    'tipo' => 'mandamiento',
                    'ruta' => $ruta,
                    'nombre_archivo' => $nombreArchivo,
                    'id_mandamiento' => $mandamiento->id, // Se asignará después de crear el mandamiento
                    'tipo_archivo' => $extension,
                ]);
            }

            $actaEjecucion = $request->file('acta_ejecucion');
            if ($actaEjecucion) {
                $extensionActa = $request->file('acta_ejecucion')->getClientOriginalExtension();
                $directorioActa =  $extensionActa == 'pdf' ? 'mandamientos-pdf-actas' : 'mandamientos-img-actas';
                $nombreArchivoActa = $actaEjecucion->hashName();
                $rutaActa =  $actaEjecucion->storeAs($directorioActa, $nombreArchivoActa, 'public');
                if (!Storage::disk('public')->exists($directorioActa . '/' . $nombreArchivoActa)) {
                    throw new \Exception('Error al guardar el acta de ejecución.');
                }
                $multimediaActa = Multimedia::create([
                    'tipo' => 'acta_ejecucion',
                    'ruta' => $rutaActa,
                    'nombre_archivo' => $nombreArchivoActa,
                    'id_mandamiento' => $mandamiento->id,
                    'tipo_archivo' => $extensionActa,
                ]);
            }



            DB::commit();
            $datos = Mandamiento::getMandamientos([], $mandamiento->id)->first();
            return response()->json([
                'success' => 'Mandamiento guardado correctamente.',
                'datos' => $datos
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Error al guardar el mandamiento: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        if (!request()->user()->hasAnyPermission(['mandamientos_all', 'mandamientos_listar', 'consulta_mandamientos'])) {
            abort(403, 'No tienes permiso para acceder a esta sección.');
        }


        $mandamiento = Mandamiento::getMandamientos([], $id)->first();

        if (in_array(request()->user()->role->nombre, ['tecnico_daci', 'tecnico_felcc', 'consultor_daci', 'consultor_felcc'])) {
            AuditarConsultas::agregarIdsAccedidos(request()->get('identificador'), $id, get_class($mandamiento));
        }

        if (!$mandamiento) {
            return response()->json(['error' => 'Mandamiento no encontrado'], 404);
        }

        return view('mandamientos.show', compact('mandamiento'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        if (!request()->user()->hasAnyPermission(['mandamientos_all'])) {
            abort(403, 'No tienes permiso para acceder a esta sección.');
        }

        $mandamiento = Mandamiento::getMandamientos([], $id)->first();

        if (!$mandamiento) {
            return response()->json(['error' => 'Mandamiento no encontrado'], 404);
        }

        return response()->json(['datos' => $mandamiento], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {

        if (!request()->user()->hasAnyPermission(['mandamientos_all'])) {
            abort(403, 'No tienes permiso para acceder a esta sección.');
        }

        try {
            DB::beginTransaction();
            $mandamiento = Mandamiento::findOrFail($id);
            $imagenMandamiento = $request->file('imagen_mandamiento');

            if ($imagenMandamiento) {

                $imagenExistente = Multimedia::where('id_mandamiento', $mandamiento->id)->where('tipo', 'mandamiento')->first();

                $extension = $request->file('imagen_mandamiento')->getClientOriginalExtension();
                $directorio = $extension == 'pdf' ? 'mandamientos-pdf' : 'mandamientos';

                $nombreArchivo = $imagenMandamiento->hashName();
                $ruta =  $imagenMandamiento->storeAs($directorio, $nombreArchivo, 'public');
                if (!Storage::disk('public')->exists($directorio . '/' . $nombreArchivo)) {
                    throw new \Exception('Error al guardar la imagen del mandamiento.');
                }
                $multimedia = Multimedia::create([
                    'tipo' => 'mandamiento',
                    'ruta' => $ruta,
                    'nombre_archivo' => $nombreArchivo,
                    'id_mandamiento' => $mandamiento->id, // Se asignará después de crear el mandamiento
                    'tipo_archivo' => $extension,
                ]);

                if ($imagenExistente) {
                    // Eliminar la imagen anterior del almacenamiento
                    if (Storage::disk('public')->exists($imagenExistente->ruta)) {
                        Storage::disk('public')->delete($imagenExistente->ruta);
                    }
                    // Eliminar el registro de la imagen anterior en la base de datos
                    $imagenExistente->delete();
                }
            }

            $actaEjecucion = $request->file('acta_ejecucion');
            if ($actaEjecucion) {
                $actaExistente = Multimedia::where('id_mandamiento', $mandamiento->id)->where('tipo_archivo', 'acta_ejecucion')->first();
                $extensionActa = $request->file('acta_ejecucion')->getClientOriginalExtension();
                $directorioActa =  $extensionActa == 'pdf' ? 'mandamientos-pdf-actas' : 'mandamientos-img-actas';
                $nombreArchivoActa = $actaEjecucion->hashName();
                $rutaActa =  $actaEjecucion->storeAs($directorioActa, $nombreArchivoActa, 'public');
                if (!Storage::disk('public')->exists($directorioActa . '/' . $nombreArchivoActa)) {
                    throw new \Exception('Error al guardar el acta de ejecución.');
                }
                $multimediaActa = Multimedia::create([
                    'tipo' => 'acta_ejecucion',
                    'ruta' => $rutaActa,
                    'nombre_archivo' => $nombreArchivoActa,
                    'id_mandamiento' => $mandamiento->id,
                    'tipo_archivo' => $extensionActa,
                ]);
                if ($actaExistente) {
                    // Eliminar el acta anterior del almacenamiento
                    if (Storage::disk('public')->exists($actaExistente->ruta)) {
                        Storage::disk('public')->delete($actaExistente->ruta);
                    }
                    // Eliminar el registro del acta anterior en la base de datos
                    $actaExistente->delete();
                }
            }


            $mandamiento->update($request->all());

            DB::commit();

            $datos = Mandamiento::getMandamientos([], $id)->first();

            return response()->json([
                'success' => 'Mandamiento actualizado correctamente.',
                'datos' => $datos
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Error al actualizar el mandamiento: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {

        if (!request()->user()->hasAnyPermission(['mandamientos_all'])) {
            abort(403, 'No tienes permiso para acceder a esta sección.');
        }
        $mandamiento = Mandamiento::findOrFail($id);
        $mandamiento->delete();

        return response()->json(['success' => 'Mandamiento eliminado correctamente.'], 200);
    }



    public function consultarMandamientos(Request $request)
    {
        if (!request()->user()->hasAnyPermission(['mandamientos_all', 'mandamientos_listar', 'consulta_mandamientos'])) {
            abort(403, 'No tienes permiso para acceder a esta sección.');
        }

        if ($request->ajax()) {

            if (empty($request->input('tipo_filtro')) || strlen($request->input('search')) < 4) {
                return response()->json(['datos' => []]);
            }


            $mandamientos = Mandamiento::getMandamientos($request->all())
                ->paginate($request->get('size', 10), ['*'], 'page', $request->get('page', 1));

            $user = auth()->user();
            if (in_array($user->role->nombre, ['tecnico_daci', 'tecnico_felcc', 'consultor_daci', 'consultor_felcc'])) {
                if ($request->get('nuevo_filtro', false)) {
                    $request->merge([
                        'cantidad_resultados' => $mandamientos->total(),
                    ]);
                    AuditarConsultas::registrar($user, 'consulta_mandamientos', $request);
                }
            }

            return response()->json([
                'datos' => $mandamientos->items(),
                'total' => $mandamientos->total(),
                'page' => $mandamientos->currentPage(),
            ]);
        }


        return view('mandamientos.consultas');
    }
}
