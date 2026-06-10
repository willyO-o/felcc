<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VehiculoPadron extends Model
{
    protected $connection = 'mysql_vehiculos';
    protected $table = 'vehiculo';
    public $timestamps = false;

    protected $fillable = [
        'alcaldia',
        'clase',
        'color',
        'docidentidad',
        'dompropietario',
        'marca',
        'modelo',
        'nochasis',
        'nomotor',
        'placa',
        'placaantigua',
        'poliza',
        'propietario',
        'servicio',
        'tipo',
    ];

    /**
     * Obtener descripción completa del vehículo
     */
    public function getDescripcionAttribute(): string
    {
        return implode(' ', array_filter([
            $this->marca,
            $this->modelo,
            $this->clase,
            $this->color,
        ]));
    }
}
