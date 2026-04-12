<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cargio extends Model
{
    //
    protected $table = 'cargio';
    protected $fillable = [
        'vehiculo_id',
        'estacion_servicio_id',
        'persona_id',
        'departamento',
        'producto',
        'factura',
        'nro_autorizacion',
        'codigo_control',
        'cantidad',
        'monto',
        'fecha_venta'
    ];

    public function vehiculo()
    {
        return $this->belongsTo(Vehiculo::class, 'vehiculo_id');
    }

    public function estacionServicio()
    {
        return $this->belongsTo(EstacionServicio::class, 'estacion_servicio_id');
    }

    public function persona()
    {
        return $this->belongsTo(Persona::class, 'persona_id');
    }

}
