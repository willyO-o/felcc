<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InspeccionTecnica extends Model
{
    //

    protected $table = 'inspeccion_tecnica';

    protected $fillable = [
        'vehiculo_id',
        'persona_id',
        'dep',
        'resultado',
        'fecha_inspeccion',
    ];

    protected $casts = [
        'fecha_inspeccion' => 'datetime',
    ];

    public function vehiculo()
    {
        return $this->belongsTo(Vehiculo::class, 'vehiculo_id');
    }

    public function persona()
    {
        return $this->belongsTo(Persona::class, 'persona_id');
    }

}
