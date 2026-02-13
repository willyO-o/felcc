<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RegistroCriminal extends Model
{
    protected $table = 'registro_criminal';

    protected $fillable = [
        'fecha_registro',
        'especialidad',
        'rasgos',
        'id_persona',
        'id_division',
        'id_usuario',
    ];

    public function persona()
    {
        return $this->belongsTo(Persona::class, 'id_persona');
    }

    public function division()
    {
        return $this->belongsTo(Division::class, 'id_division');
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'id_usuario');
    }


}
