<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Imei extends Model
{
    use SoftDeletes;
    //
    protected $table = 'imei';
    protected $fillable = [
        'imei',
        'caracteristicas',
    ];

    public function telefonos()
    {
        return $this->hasMany(Telefono::class, 'imei_id');

    }

}


