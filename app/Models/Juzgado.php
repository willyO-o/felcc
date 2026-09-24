<?php

namespace App\Models;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

use Illuminate\Database\Eloquent\Model;

class Juzgado extends Model implements AuditableContract
{
    use \OwenIt\Auditing\Auditable;

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

    static function idJuzgadoNombre($nombre)
    {
        // fisrtor create
        if(empty($nombre)){
            return null;
        }
        $juzgado = static::firstOrCreate(['nombre_juzgado' => $nombre]);
        return $juzgado->id;
    }
}
