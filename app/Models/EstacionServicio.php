<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EstacionServicio extends Model
{
    //
    protected $table = 'estacion_servicio';
    protected $fillable = [
        'eess',
        'nit',
        'telefono',
    ];

    public function cargios()
    {
        return $this->hasMany(Cargio::class, 'estacion_servicio_id');
    }
}
