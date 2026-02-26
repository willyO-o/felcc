<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RegistroCriminal extends Model
{
    protected $table = 'registro_criminal';


    protected $fillable = [
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
        'id_persona',
        'id_division',
        'id_usuario',
    ];

    public function persona()
    {
        return $this->belongsTo(Persona::class, 'id_persona');
    }

    public function division()
    {
        return $this->belongsTo(Division::class, 'id_division');
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'id_usuario');
    }

    public function fotos()
    {
        return $this->hasMany(FotosRegistro::class, 'id_registro_criminal');
    }

    public function getFotoFrenteAttribute()
    {
        $fotoFrente = $this->fotos()->where('tipo', 'FRONTAL')->first();
        return $fotoFrente ? $fotoFrente->ruta_archivo : null;
    }

    public function getFotoPerfilAttribute()
    {
        $fotoPerfil = $this->fotos()->where('tipo', 'LATERAL')->first();
        return $fotoPerfil ? $fotoPerfil->ruta_archivo : null;
    }


    // capturar el usuario al momento de registrar un nuevo registro criminal

    static function boot()
    {
        parent::boot();

        static::creating(function ($registro) {
            $registro->id_usuario = auth()->id();
        });
    }



    static function getRegistros($filters = [])
    {
        $query = self::select('registro_criminal.*')
            ->join('persona', 'registro_criminal.id_persona', '=', 'persona.id')
            ->join('division', 'registro_criminal.id_division', '=', 'division.id')
            ->leftJoin('pais', 'persona.id_pais', '=', 'pais.id')
            ->addSelect(
                ['persona.*',
                 'division.division as division',
                  'pais.gentilicio as gentilicio',
                  'foto_frente' => FotosRegistro::select('ruta_archivo')
                    ->whereColumn('id_registro_criminal', 'registro_criminal.id')
                    ->where('tipo', 'FRONTAL')
                    ->limit(1),
                  'foto_perfil' => FotosRegistro::select('ruta_archivo')
                    ->whereColumn('id_registro_criminal', 'registro_criminal.id')
                    ->where('tipo', 'LATERAL')
                    ->limit(1),
                  ]);



        if (!empty($filters['nombre_supuesto'])) {
            $query->where('nombre_supuesto', 'like', '%' . $filters['nombre_supuesto'] . '%');
        }

        if (!empty($filters['alias'])) {
            $query->where('alias', 'like', '%' . $filters['alias'] . '%');
        }

        if (!empty($filters['especialidad'])) {
            $query->where('especialidad', 'like', '%' . $filters['especialidad'] . '%');
        }

        if (!empty($filters['id_division'])) {
            $query->where('id_division', $filters['id_division']);
        }

        return $query;
    }
}
