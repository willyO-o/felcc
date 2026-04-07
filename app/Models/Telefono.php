<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Telefono extends Model
{
    use SoftDeletes;

    protected $table = 'telefono';

    protected $fillable = [
        'numero_celular',
        'persona_caso',
        'caso',
        'empresa',
        'imeis_asociados',
        'respuesta_requerimiento',
        'persona_id',
        'informacion',
        'callapp',
        'truecall',
        'uninet',
    ];

    protected $casts = [
        'imeis_asociados' => 'array',
    ];

    /**
     * Relación con Persona
     */
    public function persona()
    {
        return $this->belongsTo(Persona::class, 'persona_id');
    }
}
