<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Delito;
class DelitoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $delitos = Delito::all();
        return response()->json($delitos);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre_delito' => 'required|string|max:255',
        ]);
        $delito = Delito::create($request->all());
        return response()->json(['success' => true, 'message' => 'Delito creado correctamente', 'data' => $delito]);
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
