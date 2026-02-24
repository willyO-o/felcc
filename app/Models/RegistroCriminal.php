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
}
