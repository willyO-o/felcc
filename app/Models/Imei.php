<?php

namespace App\Models;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Imei extends Model implements AuditableContract
{
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;

    protected $table = 'imei';
    protected $fillable = [
        'imei',
        'caracteristicas',
    ];

    /**
     * Un IMEI pertenece a un Teléfono
     */
    public function telefonos()
    {
        return $this->belongsToMany(Telefono::class, 'imei_telefono', 'imei_id', 'telefono_id');
    }
}


