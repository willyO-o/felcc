<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VehiculoCaso extends Model
{
    protected $table = 'vehiculo_caso';

    protected $fillable = [
        'vehiculo_id',
        'persona_id',
        'registro_criminal_id',
        'tipo',
        'numero_informacion',
    ];

    /**
     * Relación con Vehiculo
     */
    public function vehiculo()
    {
        return $this->belongsTo(Vehiculo::class, 'vehiculo_id');
    }

    /**
     * Relación con Persona
     */
    public function persona()
    {
        return $this->belongsTo(Persona::class, 'persona_id');
    }

    /**
     * Relación con RegistroCriminal
     */
    public function registroCriminal()
    {
        return $this->belongsTo(RegistroCriminal::class, 'registro_criminal_id');
    }
}
