<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mandamiento;
use App\Models\Persona;
use App\Models\Delito;
use App\Models\Juzgado;

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // Por ahora devolvemos la vista sin datos
        // Más adelante aquí calcularemos estadísticas

        return view('dashboard.index');
    }
}
