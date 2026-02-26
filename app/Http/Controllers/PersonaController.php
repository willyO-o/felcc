<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Persona;
use Illuminate\Support\Facades\DB;
use App\Models\Multimedia;
use Illuminate\Support\Facades\Storage;

class PersonaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create() {}

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // return response()->json(['success' => true, 'message' => 'Persona creada correctamente', 'data' => $request->all()],400);

        $request->validate(Persona::$rules);

        try {
            DB::beginTransaction();
            $persona = Persona::create($request->all());

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

            return response()->json(['success' => true, 'message' => 'Persona creada correctamente', 'data' => $persona], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Error al crear la persona: ' . $e->getMessage()], 500);
        }
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

    public function search(Request $request)
    {
        $query = $request->input('q', $request->input('query', ''));
        $query = str_replace('%', ' ', $query);

        $personas = Persona::where('nombres', 'LIKE', "%{$query}%")
            ->orWhere('apellidos', 'LIKE', "%{$query}%")
            ->orWhere('ci', 'LIKE', "%{$query}%")
            ->orWhereRaw("CONCAT(COALESCE(nombres, ''), ' ', COALESCE(apellidos, ''),' - ', COALESCE(ci, '')) LIKE ?", ["%{$query}%"])
            ->orWhereRaw("CONCAT(COALESCE(apellidos, ''), ' ', COALESCE(nombres, ''),' - ', COALESCE(ci, '')) LIKE ?", ["%{$query}%"])
            ->orWhereRaw("CONCAT(COALESCE(ci, ''), ' - ', COALESCE(apellidos, ''), ' ', COALESCE(nombres, '')) LIKE ?", ["%{$query}%"])
            ->get();

        return response()->json($personas);
    }
}
