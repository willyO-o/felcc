<?php

namespace App\Models;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

use Illuminate\Database\Eloquent\Model;

class FotosRegistro extends Model implements AuditableContract
{
    use \OwenIt\Auditing\Auditable;

    //
    protected $table = 'fotos_registro';
    protected $fillable = [
        'tipo',
        'ruta_archivo',
        'id_registro_criminal',
        'id_usuario',
    ];
}
