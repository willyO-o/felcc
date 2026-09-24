<?php

namespace App\Models;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

use Illuminate\Database\Eloquent\Model;

class TipoMandamiento extends Model implements AuditableContract
{
    use \OwenIt\Auditing\Auditable;

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


    static function idtipoMandamientoNombre($nombre)
    {
        // fisrtor create
        if(empty($nombre)){
            return null;
        }
        $tipoMandamiento = static::firstOrCreate(['tipo_mandamiento' => $nombre]);
        return $tipoMandamiento->id;
    }
}
