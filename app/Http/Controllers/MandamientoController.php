<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mandamiento;
use App\Models\Multimedia;
use App\Models\Persona;
use App\Models\Delito;
use App\Models\Juzgado;
use App\Models\TipoMandamiento;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Storage;

class MandamientoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Si es una petición AJAX, devolver los datos para DataTables
        if ($request->ajax()) {
            $mandamientos = Mandamiento::getMandamientos($request->all())
                ->paginate($request->get('size', 10), ['*'], 'page', $request->get('page', 1));

            return response()->json([
                'datos' => $mandamientos->items(),
                'total' => $mandamientos->total(),
                'page' => $mandamientos->currentPage(),
            ]);

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
        // $mandamientos = Mandamiento::with(['usuario', 'juzgado', 'delito', 'tipoMandamiento'])
        $mandamientos = Mandamiento::select('mandamiento.*')
            ->leftJoin('persona', 'mandamiento.id_persona', '=', 'persona.id')
            ->leftJoin('delito', 'mandamiento.id_delito', '=', 'delito.id')
            ->leftJoin('juzgado', 'mandamiento.id_juzgado', '=', 'juzgado.id')
            ->leftJoin('tipo_mandamiento', 'mandamiento.id_tipo_mandamiento', '=', 'tipo_mandamiento.id')
            ->leftJoin('multimedia', 'mandamiento.id', '=', 'multimedia.id_mandamiento')
            ->addSelect([
                'nombre_completo' => DB::raw("CONCAT(COALESCE(persona.nombre, ''), ' ', COALESCE(persona.paterno, ''), ' ', COALESCE(persona.materno, '')) as nombre_completo"),
                'ci' => 'persona.ci',
                'nombre_delito' => 'delito.nombre_delito',
                'nombre_juzgado' => 'juzgado.nombre_juzgado',
                'tipo_mandamiento' => 'tipo_mandamiento.tipo_mandamiento',
                'ruta_multimedia' => 'multimedia.ruta',
                'imagenes' => DB::raw("(SELECT JSON_ARRAYAGG(m.ruta)
                                            FROM multimedia m
                                            WHERE m.id_persona = persona.id) as imagenes_persona")
            ]);


        $mandamientos = $mandamientos->paginate(10);

        return [
            'datos' => $mandamientos->items(),
            'total' => $mandamientos->total(),
            'page' => $mandamientos->currentPage(),
        ];
    }

    /**
     * Generar botones de acción
     */
    private function getActionButtons($id)
    {
        return '
            <div class="d-flex gap-2">
                <a href="' . route('mandamientos.show', $id) . '" class="btn btn-sm btn-info" title="Ver">
                    <i class="ri-eye-line"></i>
                </a>
                <a href="' . route('mandamientos.edit', $id) . '" class="btn btn-sm btn-warning" title="Editar">
                    <i class="ri-edit-line"></i>
                </a>
                <button type="button" class="btn btn-sm btn-danger btn-delete" data-id="' . $id . '" title="Eliminar">
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
        $tipoMandamientos = TipoMandamiento::all();
        return view('mandamientos.formulario', compact('mandamientos', 'tipoMandamientos'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate(Mandamiento::$rules);


        try {

            DB::beginTransaction();

            $mandamiento = Mandamiento::create($request->all());
            $imagenMandamiento = $request->file('imagen_mandamiento');

            if ($imagenMandamiento) {
                $nombreArchivo = $imagenMandamiento->hashName();
                $ruta =  $imagenMandamiento->storeAs('mandamientos', $nombreArchivo, 'public');
                if (!Storage::disk('public')->exists('mandamientos/' . $nombreArchivo)) {
                    throw new \Exception('Error al guardar la imagen del mandamiento.');
                }
                $multimedia = Multimedia::create([
                    'tipo' => 'mandamiento',
                    'ruta' => $ruta,
                    'nombre_archivo' => $nombreArchivo,
                    'id_mandamiento' => $mandamiento->id, // Se asignará después de crear el mandamiento
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
        $mandamiento = Mandamiento::getMandamientos([], $id)->first();

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
        try {
            DB::beginTransaction();
            $mandamiento = Mandamiento::findOrFail($id);
            $imagenMandamiento = $request->file('imagen_mandamiento');

            if ($imagenMandamiento) {

                $imagenExistente = Multimedia::where('id_mandamiento', $mandamiento->id)->first();

                $nombreArchivo = $imagenMandamiento->hashName();
                $ruta =  $imagenMandamiento->storeAs('mandamientos', $nombreArchivo, 'public');
                if (!Storage::disk('public')->exists('mandamientos/' . $nombreArchivo)) {
                    throw new \Exception('Error al guardar la imagen del mandamiento.');
                }
                $multimedia = Multimedia::create([
                    'tipo' => 'mandamiento',
                    'ruta' => $ruta,
                    'nombre_archivo' => $nombreArchivo,
                    'id_mandamiento' => $mandamiento->id, // Se asignará después de crear el mandamiento
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
        //
    }
}
