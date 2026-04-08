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
        'respuesta_requerimiento',
        'persona_id',
        'informacion',
        'callapp',
        'truecall',
        'uninet',
    ];


    /**
     * Relación con Persona
     */
    public function persona()
    {
        return $this->belongsTo(Persona::class, 'persona_id');
    }
    public function imei()
    {
        return $this->belongsTo(Imei::class, 'imei_id');
    }
}
