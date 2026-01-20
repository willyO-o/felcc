<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Persona extends Model
{
    protected $table = 'persona';

    protected $fillable = [
        'nombre',
        'paterno',
        'materno',
        'ci',
        'domicilio',
        'telefono',
        'fecha_nacimiento'
    ];

    protected $casts = [
        'fecha_nacimiento' => 'date'
    ];

    /**
     * Obtener el nombre completo de la persona
     */
    public function getNombreCompletoAttribute()
    {
        return trim($this->nombre . ' ' . $this->paterno . ' ' . $this->materno);
    }

    /**
     * Relación con mandamientos
     */
    public function mandamientos()
    {
        return $this->hasMany(Mandamiento::class, 'id_persona');
    }
}
