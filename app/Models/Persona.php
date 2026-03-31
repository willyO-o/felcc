<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Persona extends Model
{
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
}
