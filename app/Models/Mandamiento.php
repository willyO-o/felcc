<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mandamiento extends Model
{
    protected $table = 'mandamiento';

    protected $fillable = [
        'hoja_ruta',
        'estado',
        'fecha_ejecucion',
        'detalle_ejecucion',
        'asignado',
        'tipo_documento',
        'actividades_realizadas',
        'id_usuario',
        'id_juzgado',
        'id_delito',
        'id_tipo_mandamiento',
        'id_persona'
    ];

    protected $casts = [
        'fecha_ejecucion' => 'date'
    ];

    /**
     * Relación con usuario
     */
    public function usuario()
    {
        return $this->belongsTo(User::class, 'id_usuario');
    }

    /**
     * Relación con juzgado
     */
    public function juzgado()
    {
        return $this->belongsTo(Juzgado::class, 'id_juzgado');
    }

    /**
     * Relación con delito
     */
    public function delito()
    {
        return $this->belongsTo(Delito::class, 'id_delito');
    }

    /**
     * Relación con tipo de mandamiento
     */
    public function tipoMandamiento()
    {
        return $this->belongsTo(TipoMandamiento::class, 'id_tipo_mandamiento');
    }

    /**
     * Relación con persona
     */
    public function persona()
    {
        return $this->belongsTo(Persona::class, 'id_persona');
    }

    /**
     * Relación con multimedia
     */
    public function multimedia()
    {
        return $this->hasMany(Multimedia::class, 'id_mandamiento');
    }
}
