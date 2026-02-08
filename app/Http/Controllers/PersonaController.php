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

        $request->validate([
            'nombre' => 'required|string|max:255',
            'paterno' => 'required|string|max:255',
            'materno' => 'nullable|string|max:255',
            'ci' => 'nullable|string|max:20|unique:persona,ci',
            'fecha_nacimiento' => 'nullable|date|before:today',
            'fotos' => 'nullable|array',
            'fotos.*' => 'file|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

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
        $query = $request->input('q');
        $query = str_replace('%', ' ', $query);

        $personas = Persona::where('nombre', 'LIKE', "%{$query}%")
            ->orWhere('paterno', 'LIKE', "%{$query}%")
            ->orWhere('materno', 'LIKE', "%{$query}%")
            ->orWhere('ci', 'LIKE', "%{$query}%")
            ->orWhereRaw("CONCAT(COALESCE(nombre, ''), ' ', COALESCE(paterno, ''), ' ', COALESCE(materno, ''),' - ', COALESCE(ci, '')) LIKE ?", ["%{$query}%"])
            ->orWhereRaw("CONCAT(COALESCE(paterno, ''), ' ', COALESCE(materno, ''), ' ', COALESCE(nombre, ''),' - ', COALESCE(ci, '')) LIKE ?", ["%{$query}%"])
            ->orWhereRaw("CONCAT(COALESCE(materno, ''), ' ', COALESCE(paterno, ''), ' ', COALESCE(nombre, ''),' - ', COALESCE(ci, '')) LIKE ?", ["%{$query}%"])
            ->get();

        return response()->json($personas);
    }
}
