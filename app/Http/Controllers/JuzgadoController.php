<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Juzgado;
class JuzgadoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $juzgados = Juzgado::all();
        return response()->json($juzgados);

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
            'nombre_juzgado' => 'required|string|max:255',
        ]);
        $juzgado = Juzgado::create($request->all());
        return response()->json(['success' => true, 'message' => 'Juzgado creado correctamente', 'data' => $juzgado]);
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
