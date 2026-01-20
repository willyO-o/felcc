<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Juzgado extends Model
{
    protected $table = 'juzgado';

    protected $fillable = [
        'nombre_juzgado',
        'estado_juzgado'
    ];

    /**
     * Relación con mandamientos
     */
    public function mandamientos()
    {
        return $this->hasMany(Mandamiento::class, 'id_juzgado');
    }
}
