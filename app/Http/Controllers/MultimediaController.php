<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Multimedia;
use Illuminate\Support\Facades\Storage;

class MultimediaController extends Controller
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
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
        try {
            $multimedia = Multimedia::findOrFail($id);

            // Eliminar archivo del storage
            if (Storage::disk('public')->exists($multimedia->ruta)) {
                Storage::disk('public')->delete($multimedia->ruta);
            }

            $multimedia->delete();

            return response()->json([
                'success' => 'Archivo eliminado correctamente.',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al eliminar el archivo: ' . $e->getMessage(),
            ], 500);
        }
    }
}
