<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FotosRegistro extends Model
{
    //
    protected $table = 'fotos_registro';
    protected $fillable = [
        'tipo',
        'ruta_archivo',
        'id_registro_criminal',
        'id_usuario',
    ];
}
