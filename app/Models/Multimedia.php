<?php

namespace App\Models;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

use Illuminate\Database\Eloquent\Model;

class Multimedia extends Model implements AuditableContract
{
    use \OwenIt\Auditing\Auditable;

    protected $table = 'multimedia';

    protected $fillable = [
        'tipo',
        'ruta',
        'nombre_archivo',
        'id_mandamiento',
        'id_persona',
        'tipo_archivo',
        'id_vehiculo',
    ];

    /**
     * Relación con mandamiento
     */
    public function mandamiento()
    {
        return $this->belongsTo(Mandamiento::class, 'id_mandamiento');
    }

    /**
     * Relación con persona
     */
    public function persona()
    {
        return $this->belongsTo(Persona::class, 'id_persona');
    }
}
