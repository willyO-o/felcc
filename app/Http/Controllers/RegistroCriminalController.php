<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RegistroCriminal;
use App\Models\Persona;
use App\Models\Telefono;
use App\Models\AuditarConsultas;
use App\Models\FotosRegistro;
use App\Models\FichaRegistroPlantilla;
use App\Models\FichaRegistro;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Imagick\Driver as ImagickDriver;
use Illuminate\Support\Str;
use App\Http\Requests\GuardarRegistroCriminalRequest;
use Illuminate\Support\Carbon;

class RegistroCriminalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        if (!request()->user()->hasAnyPermission(['registro-criminal_all', 'registro-criminal_listar'])) {
            abort(403, 'No tienes permiso para acceder a esta sección.');
        }

        if ($request->ajax()) {
            // $registros = RegistroCriminal::getRegistros($request->all())
            //     ->paginate($request->get('size', 10), ['*'], 'page', $request->get('page', 1));

            // return response()->json([
            //     'datos' => $registros->items(),
            //     'total' => $registros->total(),
            //     'page' => $registros->currentPage(),
            // ]);

            $query = RegistroCriminal::with(['persona', 'division', 'fotos', 'persona.pais'])
                ->orderBy('registro_criminal.created_at', 'desc')
                ->when($request->input('search') && empty($request->input('filtro')), function ($q) use ($request) {
                    $search = $request->get('search');
                    $search = str_replace(' ', '%', $search);

                    $q->whereHas('persona', function ($q2) use ($search) {
                        $q2->whereRaw("CONCAT(COALESCE(nombres, ''), ' ', COALESCE(apellidos, '')) LIKE ?", ['%' . $search . '%'])
                            ->orWhere('ci', 'like', '%' . $search . '%')
                            ->orWhere('alias', 'like', '%' . $search . '%')
                            ->orWhere('telefono', 'like', '%' . $search . '%');
                    })->orWhere('alias', 'like', '%' . $search . '%')
                        ->orWhere('nombre_supuesto', 'like', '%' . $search . '%');
                })
                ->when($request->input('filtro') && $request->input('search'), function ($q) use ($request) {
                    $search = $request->get('search');
                    $search = str_replace(' ', '%', $search);
                    $filtro = $request->get('filtro');

                    switch ($filtro) {
                        case 'nombres':
                            $q->whereHas('persona', function ($q2) use ($search) {
                                $q2->whereRaw("CONCAT(COALESCE(nombres, ''), ' ', COALESCE(apellidos, '')) LIKE ?", ['%' . $search . '%']);
                            });
                            break;
                        case 'apellidos':
                            $q->whereHas('persona', function ($q2) use ($search) {
                                $q2->where('apellidos', 'like', '%' . $search . '%');
                            });
                            break;
                        case 'alias':

                            // tengo alias en registro criminal y en persona consultar a ambos
                            $q->where('alias', 'like', '%' . $search . '%')
                                ->orWhereHas('persona', function ($q2) use ($search) {
                                    $q2->where('alias', 'like', '%' . $search . '%');
                                });
                            break;
                        case 'ci':
                            $q->whereHas('persona', function ($q2) use ($search) {
                                $q2->where('ci', 'like', '%' . $search . '%');
                            });
                            break;
                        case 'celular':
                            //existe una relacion de personas con telefonos y el registro criminal tiene un campo telefono, consultar ambos
                            $q->where('telefono', 'like', '%' . $search . '%')
                                ->orWhereHas('persona.telefonos', function ($q2) use ($search) {
                                    $q2->where('numero_celular', 'like', '%' . $search . '%');
                                });
                            break;
                        case 'cud':
                            $q->where('cud', 'like', '%' . $search . '%');
                            break;
                        case 'conyuge':
                            $q->where('nombre_conyuge', 'like', '%' . $search . '%');
                            break;
                        case 'padre':
                            $q->whereHas('persona', function ($q2) use ($search) {
                                $q2->where('padre', 'like', '%' . $search . '%');
                            });
                            break;
                        case 'madre':
                            $q->whereHas('persona', function ($q2) use ($search) {
                                $q2->where('madre', 'like', '%' . $search . '%');
                            });
                            break;
                        case 'nombre_supuesto':
                            $q->where('nombre_supuesto', 'like', '%' . $search . '%');
                            break;
                        case 'hijos':
                            $q->where('hijos', 'like', '%' . $search . '%');
                            break;
                        case 'nacimiento':
                            //nacimiento viene como 	01-02-2007 convierto a 2007-02-01 para comparar con fecha_nacimiento que es date si no viene en el formato esperado no aplico el filtro

                            if (preg_match('/^\d{2}-\d{2}-\d{4}$/', $search)) {
                                $fechaNacimiento = \Carbon\Carbon::createFromFormat('d-m-Y', $search)->format('Y-m-d');

                                $q->whereHas('persona', function ($q2) use ($fechaNacimiento) {
                                    $q2->where('fecha_nacimiento', 'like', '%' . $fechaNacimiento . '%');
                                });
                            } else {
                                // Si el formato no es correcto, no aplicar ningún filtro y no devolver resultados
                                $q->whereRaw('1 = 0'); // Esto hará que no se devuelvan resultados si el formato de fecha es incorrecto
                            }

                            break;

                        default:
                            // Si el filtro no coincide con ningún caso, no aplicar ningún filtro adicional
                            break;
                    }
                })
                ->paginate($request->get('size', 10), ['*'], 'page', $request->get('page', 1));

            return response()->json([
                'datos' => $query->items(),
                'total' => $query->total(),
                'page' => $query->currentPage(),
            ]);
        }

        return view('registro-criminal.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // $this->authorize('acceso-total');

        if (!request()->user()->hasAnyPermission(['registro-criminal_all', 'registro-criminal_crear'])) {
            abort(403, 'No tienes permiso para acceder a esta sección.');
        }

        $registroCriminal = new RegistroCriminal();
        return view('registro-criminal.formulario', compact('registroCriminal'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(GuardarRegistroCriminalRequest $request)
    {
        // return $request->file('foto_frente');
        if (!request()->user()->hasAnyPermission(['registro-criminal_all', 'registro-criminal_crear'])) {
            abort(403, 'No tienes permiso para acceder a esta sección.');
        }

        try {
            DB::beginTransaction();

            $criterio = $request->id_persona ? ['id' => $request->id_persona] : ['ci' => $request->ci];

            $persona = Persona::where($criterio)->first();

            if (!$persona) {
                $persona = Persona::create($request->only([
                    'nombres',
                    'apellidos',
                    'ci',
                    'domicilio',
                    'telefono',
                    'fecha_nacimiento',
                    'lugar_nacimiento',
                    'complemento',
                    'genero',
                    'estado_civil',
                    'nombre_conyuge',
                    'ocupacion',
                    'id_pais',
                    'alias',
                ]));
            }

            if ($request->telefono) {
                $persona->telefono = trim($request->telefono);
                $persona->save();

                //verificar si el telefono ya existe en la tabla telefono sino crearlo y vincularlo a la persona
                $telefono = Telefono::where('numero_celular', $persona->telefono)->first();
                if (!$telefono) {
                    $telefono = Telefono::create([
                        'numero_celular' => $persona->telefono,
                        'persona_id' => $persona->id,
                    ]);
                } else {
                    if ($telefono->persona_id !== $persona->id) {
                        $telefono->persona_id = $persona->id;
                        $telefono->save();
                    }
                }
            }

            $request->merge(['id_persona' => $persona->id]);


            $registro = RegistroCriminal::create($request->all());



            if ($request->hasFile('foto_frente')) {
                $fotoFrentePath = $this->convertToWebp($request->file('foto_frente'), 'registro-criminal');
                FotosRegistro::create([
                    'tipo' => 'FRONTAL',
                    'ruta_archivo' => $fotoFrentePath,
                    'id_registro_criminal' => $registro->id,
                    'id_usuario' => auth()->id(),
                ]);
            }

            if ($request->hasFile('foto_perfil')) {
                $fotoPerfilPath = $this->convertToWebp($request->file('foto_perfil'), 'registro-criminal');
                FotosRegistro::create([
                    'tipo' => 'LATERAL',
                    'ruta_archivo' => $fotoPerfilPath,
                    'id_registro_criminal' => $registro->id,
                    'id_usuario' => auth()->id(),
                ]);
            }

            DB::commit();
            return response()->json(['success' => 'Registro creado exitosamente.'], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'error' => 'Ocurrió un error al crear el registro. Detalles: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        if (!request()->ajax()) {
            abort(404);
        }

        if (!request()->user()->hasAnyPermission(['registro-criminal_all', 'registro-criminal_listar', 'consulta_registro-criminal'])) {
            abort(403, 'No tienes permiso para acceder a esta sección.');
        }
        $datos = RegistroCriminal::with(['persona', 'fotos', 'persona.vehiculos', 'persona.telefonos'])->findOrFail($id);

        $identificador = request()->get('identificador');
        AuditarConsultas::agregarIdsAccedidos($identificador, $id, get_class($datos));

        return view('registro-criminal.partials._datos', [
            'datos' => $datos,
            'identificador' => isset($identificador) ? $identificador : null,
            'isAjax' => true,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        if (!request()->user()->hasAnyPermission(['registro-criminal_all', 'registro-criminal_editar'])) {
            abort(403, 'No tienes permiso para acceder a esta sección.');
        }

        $registroCriminal = RegistroCriminal::findOrFail($id);


        return view('registro-criminal.formulario', compact('registroCriminal'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        if (!request()->user()->hasAnyPermission(['registro-criminal_all', 'registro-criminal_editar'])) {
            abort(403, 'No tienes permiso para acceder a esta sección.');
        }

        try {
            DB::beginTransaction();

            $registro = RegistroCriminal::findOrFail($id);

            $registro->update($request->only([
                'fecha_registro',
                'nombre_supuesto',
                'alias',
                'especialidad',
                'edad_aproximada',
                'nombre_conyuge',
                'domicilio',
                'rasgos',
                'modus_operandi',
                'zonas_opera',
                'observaciones',
                'id_division',
                'telefono',
                'estatura',
                'peso',
                'cud',
                'caracteristicas_particulares',
                'hijos',

            ]));

            if (trim($request->telefono)) {
                $telefono = Telefono::where('numero_celular', trim($request->telefono))->first();
                if (!$telefono) {
                    $telefono = Telefono::create([
                        'numero_celular' => trim($request->telefono),
                    ]);
                }

                $telefono->persona_id = $registro->persona->id;
                $telefono->save();
            }

            if ($request->hasFile('foto_frente')) {
                // Eliminar foto frontal anterior si existe
                $fotoFrenteAnterior = FotosRegistro::where('id_registro_criminal', $registro->id)->where('tipo', 'FRONTAL')->first();

                // Guardar nueva foto frontal
                $fotoFrentePath = $this->convertToWebp($request->file('foto_frente'), 'registro-criminal');
                FotosRegistro::create([
                    'tipo' => 'FRONTAL',
                    'ruta_archivo' => $fotoFrentePath,
                    'id_registro_criminal' => $registro->id,
                    'id_usuario' => auth()->id(),
                ]);

                if ($fotoFrenteAnterior) {
                    Storage::disk('public')->delete($fotoFrenteAnterior->ruta_archivo);
                    $fotoFrenteAnterior->delete();
                }
            }


            if ($request->hasFile('foto_perfil')) {
                // Eliminar foto de perfil anterior si existe
                $fotoPerfilAnterior = FotosRegistro::where('id_registro_criminal', $registro->id)->where('tipo', 'LATERAL')->first();
                // Guardar nueva foto de perfil
                $fotoPerfilPath = $this->convertToWebp($request->file('foto_perfil'), 'registro-criminal');
                FotosRegistro::create([
                    'tipo' => 'LATERAL',
                    'ruta_archivo' => $fotoPerfilPath,
                    'id_registro_criminal' => $registro->id,
                    'id_usuario' => auth()->id(),
                ]);

                if ($fotoPerfilAnterior) {
                    Storage::disk('public')->delete($fotoPerfilAnterior->ruta_archivo);
                    $fotoPerfilAnterior->delete();
                }
            }

            DB::commit();

            return response()->json(['success' => 'Registro actualizado exitosamente.']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'error' => 'Ocurrió un error al actualizar el registro. Detalles: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        if (!request()->user()->hasAnyPermission(['registro-criminal_all'])) {
            abort(403, 'No tienes permiso para realizar esta acción.');
        }

        $registro = RegistroCriminal::findOrFail($id);
        $registro->fotos()->delete();
        $registro->forceDelete();
        return response()->json(['success' => 'Registro eliminado exitosamente.']);
    }


    private function convertToWebp($imageFile, $pathName = 'personas')
    {


        // $originalName = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);

        $webpName = 'img_' . Str::uuid()->toString() . '.webp';

        $fullPath = $pathName . '/' . $webpName;
        $manager = new ImageManager(new ImagickDriver());

        $image = $manager->read($imageFile->getPathname());

        // Puedes ajustar calidad si quieres (0 a 100)
        $webpEncoded = $image->toWebp(quality: 80);

        // Guardar con Storage (en disco 'public' por ejemplo)
        Storage::disk('public')->put($fullPath, $webpEncoded);

        return $fullPath;
    }

    public function consultarRegistroCriminal(Request $request)
    {
        if (!request()->user()->hasAnyPermission(['registro-criminal_all', 'registro-criminal_listar', 'consulta_registro-criminal'])) {
            abort(403, 'No tienes permiso para acceder a esta sección.');
        }

        if ($request->ajax()) {
            if (empty($request->input('filtro')) || strlen($request->input('search')) < 4) {
                return response()->json(['datos' => []]);
            }

            $query = RegistroCriminal::with(['persona', 'division', 'fotos', 'persona.pais'])
                ->orderBy('registro_criminal.created_at', 'desc')
                ->when($request->input('filtro') && $request->input('search'), function ($q) use ($request) {
                    $search = $request->get('search');
                    $search = str_replace(' ', '%', $search);
                    $filtro = $request->get('filtro');

                    switch ($filtro) {
                        case 'nombres':
                            $q->whereHas('persona', function ($q2) use ($search) {
                                $q2->whereRaw("CONCAT(COALESCE(nombres, ''), ' ', COALESCE(apellidos, '')) LIKE ?", ['%' . $search . '%']);
                            });
                            break;
                        case 'apellidos':
                            $q->whereHas('persona', function ($q2) use ($search) {
                                $q2->where('apellidos', 'like', '%' . $search . '%');
                            });
                            break;
                        case 'alias':

                            // tengo alias en registro criminal y en persona consultar a ambos
                            $q->where('alias', 'like', '%' . $search . '%')
                                ->orWhereHas('persona', function ($q2) use ($search) {
                                    $q2->where('alias', 'like', '%' . $search . '%');
                                });
                            break;
                        case 'ci':
                            $q->whereHas('persona', function ($q2) use ($search) {
                                $q2->where('ci', 'like', '%' . $search . '%');
                            });
                            break;
                        case 'celular':
                            //existe una relacion de personas con telefonos y el registro criminal tiene un campo telefono, consultar ambos
                            $q->where('telefono', 'like', '%' . $search . '%')
                                ->orWhereHas('persona.telefonos', function ($q2) use ($search) {
                                    $q2->where('numero_celular', 'like', '%' . $search . '%');
                                });
                            break;
                        case 'cud':
                            $q->where('cud', 'like', '%' . $search . '%');
                            break;
                        case 'conyuge':
                            $q->where('nombre_conyuge', 'like', '%' . $search . '%');
                            break;
                        case 'padre':
                            $q->whereHas('persona', function ($q2) use ($search) {
                                $q2->where('padre', 'like', '%' . $search . '%');
                            });
                            break;
                        case 'madre':
                            $q->whereHas('persona', function ($q2) use ($search) {
                                $q2->where('madre', 'like', '%' . $search . '%');
                            });
                            break;
                        case 'nombre_supuesto':
                            $q->where('nombre_supuesto', 'like', '%' . $search . '%');
                            break;
                        case 'hijos':
                            $q->where('hijos', 'like', '%' . $search . '%');
                            break;
                        case 'nacimiento':
                            //nacimiento viene como 	01-02-2007 convierto a 2007-02-01 para comparar con fecha_nacimiento que es date si no viene en el formato esperado no aplico el filtro

                            if (preg_match('/^\d{2}-\d{2}-\d{4}$/', $search)) {
                                $fechaNacimiento = \Carbon\Carbon::createFromFormat('d-m-Y', $search)->format('Y-m-d');

                                $q->whereHas('persona', function ($q2) use ($fechaNacimiento) {
                                    $q2->where('fecha_nacimiento', 'like', '%' . $fechaNacimiento . '%');
                                });
                            } else {
                                // Si el formato no es correcto, no aplicar ningún filtro y no devolver resultados
                                $q->whereRaw('1 = 0'); // Esto hará que no se devuelvan resultados si el formato de fecha es incorrecto
                            }

                            break;

                        default:
                            // Si el filtro no coincide con ningún caso, no aplicar ningún filtro adicional
                            break;
                    }
                })
                ->paginate($request->get('size', 10), ['*'], 'page', $request->get('page', 1));


            $user = auth()->user();
            if ($request->get('nuevo_filtro', false)) {
                $request->merge([
                    'cantidad_resultados' => $query->total(),
                ]);
                AuditarConsultas::registrar($user, 'consulta_registros', $request);
            }


            return response()->json([
                'datos' => $query->items(),
                'total' => $query->total(),
                'page' => $query->currentPage(),
            ]);
        }


        return view('registro-criminal.consultas');
    }


    public function showByCodigo(string $codigo)
    {

        if (!request()->user()->hasAnyPermission(['registro-criminal_all', 'registro-criminal_listar', 'consulta_registro-criminal'])) {
            abort(403, 'No tienes permiso para acceder a esta sección.');
        }

        $datos = RegistroCriminal::with(['persona', 'fotos', 'persona.vehiculos', 'persona.telefonos'])->whereRaw('MD5(id) = ?', [$codigo])->firstOrFail();

        $identificador = request()->get('identificador');
        AuditarConsultas::agregarIdsAccedidos($identificador, $datos->id, get_class($datos));

        return view('registro-criminal.show', [
            'datos' => $datos,
            'identificador' => isset($identificador) ? $identificador : null,
        ]);
    }


    public function vistaPrevia(string  $id)
    {
        $registro = RegistroCriminal::with(['persona', 'division'])->findOrFail($id);

        $plantilla = FichaRegistroPlantilla::where('estado', 'ACTIVO')->first();

        // ultimo  del año actual  y numero maximo
        $ultomaFicha = FichaRegistro::whereYear('created_at', Carbon::now()->year)->max('numero_ficha');
        $nro = str_pad($ultomaFicha + 1, 3, '0', STR_PAD_LEFT);

        $nroFicha = $nro . '/' . Carbon::now()->year;

        $fechaHoraActual = Carbon::now()->isoFormat('dddd DD [de] MMMM [de] YYYY, [a horas] hh:mm a');
        $plantilla->introduccion = Str::replace('{fecha_hora_actual}', $fechaHoraActual, $plantilla->introduccion);
        $fechaActual = Carbon::now()->isoFormat('DD [de] MMMM [de] YYYY');
        $plantilla->persona = Str::replace(['{nombre_persona}', '{ci_persona}'], [$registro->persona->nombres . ' ' . $registro->persona->apellidos, $registro->persona->ci], $plantilla->persona);
        $plantilla->resultado_busqueda = Str::replace(['{division}', '{fecha_aprehension}', '{delito}'], [$registro->division->nombre, $registro->fecha_registro->isoFormat('DD/MM/YYYY'), $registro->especialidad], $plantilla->resultado_busqueda);

        $registrCriminalId = $registro->id;

        return view('registro-criminal.partials._previewPrint', compact('plantilla', 'fechaActual', 'nroFicha', 'registrCriminalId'));
    }
}
