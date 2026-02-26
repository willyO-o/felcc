<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RegistroCriminal;
use App\Models\Persona;
use App\Models\FotosRegistro;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Imagick\Driver as ImagickDriver;
use Illuminate\Support\Str;

class RegistroCriminalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        if ($request->ajax()) {
            $registros = RegistroCriminal::getRegistros($request->all())
                ->paginate($request->get('size', 10), ['*'], 'page', $request->get('page', 1));

            return response()->json([
                'datos' => $registros->items(),
                'total' => $registros->total(),
                'page' => $registros->currentPage(),
            ]);
        }

        return view('registro-criminal.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $registroCriminal = new RegistroCriminal();
        return view('registro-criminal.formulario', compact('registroCriminal'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // return $request->all();

        try {
            DB::beginTransaction();

            $criterio = $request->id_persona ? ['id' => $request->id_persona] : ['ci' => $request->ci];

            $persona = Persona::updateOrCreate(
                $criterio,
                $request->only([
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
                    'id_pais'
                ])
            );

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
            return response()->json(['message' => 'Registro creado exitosamente.'], 201);
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


    private function convertToWebp($imageFile, $pathName = 'personas')
    {


        // $originalName = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);

        $webpName = 'img_' . Str::uuid()->toString() . '.webp';

        $manager = new ImageManager(new ImagickDriver());

        $image = $manager->read($imageFile->getPathname());

        // Puedes ajustar calidad si quieres (0 a 100)
        $webpEncoded = $image->toWebp(quality: 80);

        // Guardar con Storage (en disco 'public' por ejemplo)
        Storage::disk('public')->put($pathName . "/{$webpName}", $webpEncoded);

        return $webpName;
    }
}
