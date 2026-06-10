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

            // Búsqueda avanzada por campo individual (LIKE)
            $advSimpleFields = [
                'adv_propietario'  => 'propietario',
                'adv_docidentidad' => 'docidentidad',
                'adv_nochasis'     => 'nochasis',
                'adv_nomotor'      => 'nomotor',
                'adv_marca'        => 'marca',
                'adv_modelo'       => 'modelo',
                'adv_clase'        => 'clase',
                'adv_color'        => 'color',
                'adv_tipo'         => 'tipo',
                'adv_servicio'     => 'servicio',
            ];

            foreach ($advSimpleFields as $param => $column) {
                if ($request->filled($param)) {
                    $val = str_replace('%', ' ', $request->input($param));
                    $query->whereRaw("{$column} LIKE ?", ["%{$val}%"]);
                }
            }

            // Placa: busca en placa y placaantigua
            if ($request->filled('adv_placa')) {
                $val = str_replace('%', ' ', $request->input('adv_placa'));
                $query->where(function ($q) use ($val) {
                    $q->whereRaw('placa LIKE ?', ["%{$val}%"])
                      ->orWhereRaw('placaantigua LIKE ?', ["%{$val}%"]);
                });
            }

            // Dirección del propietario
            if ($request->filled('adv_dom')) {
                $val = str_replace('%', ' ', $request->input('adv_dom'));
                $query->whereRaw('dompropietario LIKE ?', ["%{$val}%"]);
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
