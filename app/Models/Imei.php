<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Imei extends Model
{
    use SoftDeletes;

    protected $table = 'imei';
    protected $fillable = [
        'imei',
        'caracteristicas',
        'telefono_id',
    ];

    /**
     * Un IMEI pertenece a un Teléfono
     */
    public function telefono()
    {
        return $this->belongsTo(Telefono::class, 'telefono_id');
    }
}


