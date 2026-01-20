<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Delito extends Model
{
    protected $table = 'delito';

    protected $fillable = [
        'nombre_delito',
        'estado_delito',
        'descripcion_delito'
    ];

    /**
     * Relación con mandamientos
     */
    public function mandamientos()
    {
        return $this->hasMany(Mandamiento::class, 'id_delito');
    }
}
