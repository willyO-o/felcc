<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TipoMandamiento;

class TipoMandamientoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tiposMandamientos = TipoMandamiento::all();
        return response()->json($tiposMandamientos);
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
        $request->validate([
            'tipo_mandamiento' => 'required|string|max:255',
        ]);
        $tipoMandamiento = TipoMandamiento::create($request->all());
        return response()->json(['success' => true, 'message' => 'Tipo de mandamiento creado correctamente', 'data' => $tipoMandamiento]);
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
}
