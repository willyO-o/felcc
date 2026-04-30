<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Persona;
use Illuminate\Support\Facades\DB;

class EliminarDuplicados extends Controller
{
    //

    public function index(Request $request)
    {



        $codigo = $request->input('code');
        if (!$codigo == '79515350/Willy-Wonka#') {
            abort(404);
        }


        try {

            DB::beginTransaction();

            $campos = (new Persona)->getFillable();

            DB::table('persona')
                ->where('deleted_at', '!=', null)
                ->update(['deleted_at' => null]);


            $duplicados = Persona::select('nombres', 'apellidos', DB::raw('COUNT(*) as total'))
                ->groupBy('nombres', 'apellidos')
                ->whereNotNull('nombres')
                ->whereNotNull('apellidos')
                ->where('nombres', '!=', '')
                ->where('apellidos', '!=', '')
                ->havingRaw('COUNT(*) > 1')
                ->orderBy('total', 'desc')
                ->get();


            // dd($duplicados);

            foreach ($duplicados as $grupo) {
                $personas = Persona::where('nombres', $grupo->nombres)
                    ->where('apellidos', $grupo->apellidos)
                    ->orderBy('id')
                    ->get();

                // Mantener el primer registro y eliminar los demás
                $personaPrincipal = $personas->first();

                $personas->shift(); // Eliminar el primer registro de la colección
                $huboCambios = false;

                foreach ($personas as $duplicado) {

                    foreach ($campos as $campo) {
                        if (empty($personaPrincipal->$campo) && !empty($duplicado->$campo)) {
                            $personaPrincipal->$campo = $duplicado->$campo;
                            $huboCambios = true;
                        }
                    }

                    if(!empty($duplicado->ci)){
                        $duplicado->ci = null;
                        $duplicado->save();
                    }


                    //reasignar relaciones de mandamientos dinamiamente en un array asociativo
                    $relaciones = [
                        'documento' => 'id_persona',
                        'inspeccion_tecnica' => 'persona_id',
                        'mandamiento' => 'id_persona',
                        'multimedia' => 'id_persona',
                        'registro_criminal' => 'id_persona',
                        'telefono' => 'persona_id',
                        'vehiculo_caso' => 'persona_id',
                    ];

                    foreach ($relaciones as $tabla => $campo) {
                        DB::table($tabla)
                            ->where($campo, $duplicado->id)
                            ->update([$campo => $personaPrincipal->id]);
                    }

                    $duplicado->forceDelete();
                }

                if ($huboCambios) {
                    $personaPrincipal->save();
                }
            }

            DB::commit();

            return response()->json(['message' => 'Duplicados eliminados correctamente.']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Ocurrió un error al eliminar los duplicados: ' . $e->getMessage()], 500);
        }
    }
}
