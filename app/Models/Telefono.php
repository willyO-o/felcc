<?php

namespace App\Models;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Telefono extends Model implements AuditableContract
{
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;

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

    /**
     * Un Teléfono tiene muchos IMEIs
     */
    public function imeis()
    {
        return $this->belongsToMany(Imei::class, 'imei_telefono', 'telefono_id', 'imei_id');
    }


    public static function idTelefonoNumero($numero)
    {
        $telefono = static::where('numero_celular', $numero)->first();

        if ($telefono) {
            $telefono = static::create([
                'numero_celular' => $numero,
            ]);
        }
        return $telefono->id;
    }
}
