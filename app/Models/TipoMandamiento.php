<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipoMandamiento extends Model
{
    protected $table = 'tipo_mandamiento';

    protected $fillable = [
        'tipo_mandamiento',
        'estado_tipo_mandamiento',
        'descripcion_tipo_mandamiento'
    ];

    /**
     * Relación con mandamientos
     */
    public function mandamientos()
    {
        return $this->hasMany(Mandamiento::class, 'id_tipo_mandamiento');
    }
}
