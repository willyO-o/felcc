<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\VehiculoPadron;

class VehiculoPadronController extends Controller
{
    /**
     * Listado con soporte AJAX para DataTable + filtros de búsqueda.
     */
    public function index(Request $request)
    {
        if (!request()->user()->hasAnyPermission(['vehiculos_padron_all', 'vehiculos_padron_listar'])) {
            abort(403, 'No tienes permiso para acceder a esta sección.');
        }

        if ($request->ajax()) {
            $query = VehiculoPadron::query();

            if ($request->filled('search')) {
                $search = str_replace('%', ' ', $request->search);
                $searchType = $request->get('search_type', '');

                $query->where(function ($q) use ($search, $searchType) {
                    if ($searchType === 'placa') {
                        $q->whereRaw('placa LIKE ?', ["%{$search}%"])
                          ->orWhereRaw('placaantigua LIKE ?', ["%{$search}%"]);
                    } elseif ($searchType === 'propietario') {
                        $q->whereRaw('propietario LIKE ?', ["%{$search}%"]);
                    } elseif ($searchType === 'docidentidad') {
                        $q->whereRaw('docidentidad LIKE ?', ["%{$search}%"]);
                    } elseif ($searchType === 'nochasis') {
                        $q->whereRaw('nochasis LIKE ?', ["%{$search}%"]);
                    } elseif ($searchType === 'nomotor') {
                        $q->whereRaw('nomotor LIKE ?', ["%{$search}%"]);
                    } else {
                        $q->whereRaw('placa LIKE ?', ["%{$search}%"])
                          ->orWhereRaw('placaantigua LIKE ?', ["%{$search}%"])
                          ->orWhereRaw('propietario LIKE ?', ["%{$search}%"])
                          ->orWhereRaw('docidentidad LIKE ?', ["%{$search}%"])
                          ->orWhereRaw('nochasis LIKE ?', ["%{$search}%"])
                          ->orWhereRaw('nomotor LIKE ?', ["%{$search}%"]);
                    }
                });
            }

            if ($request->filled('clase')) {
                $query->where('clase', $request->clase);
            }

            $query->orderBy('id', 'desc');

            $vehiculos = $query->paginate(
                $request->get('size', 10),
                ['*'],
                'page',
                $request->get('page', 1)
            );

            return response()->json([
                'datos' => $vehiculos->items(),
                'total' => $vehiculos->total(),
                'page'  => $vehiculos->currentPage(),
            ]);
        }

        return view('vehiculos-padron.index');
    }

    /**
     * Ver detalle de un vehículo (cargado dinámicamente en modal).
     */
    public function show(string $id)
    {
        $datos = VehiculoPadron::findOrFail($id);

        return view('vehiculos-padron.show', compact('datos'));
    }
}
