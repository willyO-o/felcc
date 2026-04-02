<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Persona extends Model
{
    use SoftDeletes;
    protected $table = 'persona';

    protected $fillable = [
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
        'url_documento',
        'responsable',
        'datos_segip',
        'estado_investigacion',
        'url_documento',
        'user_id',
        'padre',
        'madre',
        'grupo_sanguineo',

    ];

    protected $casts = [
        'fecha_nacimiento' => 'date'
    ];

    /**
     * Obtener el nombre completo de la persona
     */
    public function getNombreCompletoAttribute()
    {
        return trim($this->nombres . ' ' . $this->apellidos);
    }

    /**
     * Relación con mandamientos
     */
    public function mandamientos()
    {
        return $this->hasMany(Mandamiento::class, 'id_persona');
    }

    public function multimedia()
    {
        return $this->hasMany(Multimedia::class, 'id_persona');
    }

    public function registroCriminal()
    {
        return $this->hasMany(RegistroCriminal::class, 'id_persona');
    }



    static $rules = [
        'nombres' => 'required|string|max:255',
        'apellidos' => 'required|string|max:255',
        'ci' => 'nullable|string|max:20|unique:persona,ci',
        'fecha_nacimiento' => 'nullable|date|before:today',
        'fotos' => 'nullable|array',
        'fotos.*' => 'file|mimes:jpeg,png,jpg,webp|max:2048',
    ];


    static function idPersonaDatos($datos)
    {
        $persona = null;
        if (!empty($datos['ci'])) {
            $persona = static::where('ci', $datos['ci'])->first();
        }

        if(!$persona && !empty($datos['nombre']))
        {
            $persona = static::whereRaw("CONCAT(nombres, ' ', apellidos) = ?", [$datos['nombre']. ' ' . $datos['apellidos']])->first();
        }


        if (!$persona) {
            $persona = static::create($datos);
        }

        return $persona->id;
    }

    static function boot()
    {
        parent::boot();

        static::creating(function ($persona) {
            $persona->user_id = auth()->id();
        });
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
