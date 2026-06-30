<?php

namespace App\Models;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

use Illuminate\Database\Eloquent\Model;

class InspeccionTecnica extends Model implements AuditableContract
{
    use \OwenIt\Auditing\Auditable;

    //

    protected $table = 'inspeccion_tecnica';

    protected $fillable = [
        'vehiculo_id',
        'persona_id',
        'dep',
        'resultado',
        'fecha_inspeccion',
        'anio',
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
