<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vehiculo extends Model
{
    use SoftDeletes;

    protected $table = 'vehiculo';

    protected $fillable = [
        'placa',
        'descripcion',
        'responsable',
        'caso_relacionado',
        'bsisa',
        'ci_bsisa',
        'ruat',
        'anh',
        'itb',
        'soat',
    ];

    /**
     * Relación con VehiculoCaso
     */
    public function casos()
    {
        return $this->hasMany(VehiculoCaso::class, 'vehiculo_id');
    }

    /**
     * Relación con Persona a través de VehiculoCaso
     */
    public function personas()
    {
        return $this->belongsToMany(Persona::class, 'vehiculo_caso', 'vehiculo_id', 'persona_id')
            ->withPivot('tipo', 'caso', 'registro_criminal_id')
            ->withTimestamps();
    }
}
