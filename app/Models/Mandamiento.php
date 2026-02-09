<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;


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


    protected static function boot()
    {
        parent::boot();

        static::creating(function ($mandamiento) {
            $mandamiento->id_usuario = auth()->id() ?? null; // Asignar el ID del usuario autenticado o null si no hay ninguno
        });
    }



    static function getMandamientos($filtros = [], $idMandamiento = null)
    {

        $query = self::select('mandamiento.*')
            ->leftJoin('persona', 'mandamiento.id_persona', '=', 'persona.id')
            ->leftJoin('delito', 'mandamiento.id_delito', '=', 'delito.id')
            ->leftJoin('juzgado', 'mandamiento.id_juzgado', '=', 'juzgado.id')
            ->leftJoin('tipo_mandamiento', 'mandamiento.id_tipo_mandamiento', '=', 'tipo_mandamiento.id')
            ->leftJoin('multimedia', 'mandamiento.id', '=', 'multimedia.id_mandamiento')
            ->addSelect([
                'nombre_completo' => DB::raw("CONCAT(COALESCE(persona.nombre, ''), ' ', COALESCE(persona.paterno, ''), ' ', COALESCE(persona.materno, '')) as nombre_completo"),
                'ci' => 'persona.ci',
                'nombre_delito' => 'delito.nombre_delito',
                'nombre_juzgado' => 'juzgado.nombre_juzgado',
                'tipo_mandamiento' => 'tipo_mandamiento.tipo_mandamiento',
                'ruta_multimedia' => 'multimedia.ruta',
                'imagenes' => DB::raw("(SELECT JSON_ARRAYAGG(m.ruta)
                                            FROM multimedia m
                                            WHERE m.id_persona = persona.id) as imagenes_persona")
            ])
            ->orderBy('mandamiento.id', 'desc');


        if ($idMandamiento) {
            $query->where('mandamiento.id', $idMandamiento);
        }

        return $query;
    }
}
