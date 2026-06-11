<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
// use Illuminate\Database\Eloquent\SoftDeletes;

class Ciudadano extends Model
{
    // use SoftDeletes;
    protected $connection = 'mysql_vehiculos';

    protected $table = 'ciudadano';
    public $timestamps = false;

    protected $fillable = [
        'ciudadano',
        'tipo_cedula_act',
        'cedula_act',
        'ap_pat',
        'ap_mat',
        'ap_esp',
        'nombres',
        'sexo',
        'estado_civil',
        'pais_nac',
        'fecha_nac',
        'mesa_ciudadano',
        'partida_mesa_ciudadano',
        'fecha_ins',
        'dom_1',
        'dom_2',
        'id_loc',
        'nom_dep',
        'nom_prov',
        'nom_mun',
        'ocupacion',
        'estado_registro',
        'id_departamento',
    ];

    protected $casts = [
        'fecha_nac' => 'date',
        'fecha_ins' => 'datetime',
    ];

    protected $appends = [
        'fecha_nac_formatted',
    ];


    public function localidad()
    {
        return $this->belongsTo(Localidad::class, 'id_loc');
    }

    public function departamento()
    {
        return $this->belongsTo(Departamento::class, 'id_departamento');
    }

    /**
     * Obtener el nombre completo del ciudadano
     */
    public function getNombreCompletoAttribute()
    {
        $apellidos = array_filter([
            $this->ap_pat,
            $this->ap_mat,
            $this->ap_esp
        ]);
        $apellidos_str = implode(' ', $apellidos);
        return trim($this->nombres . ' ' . $apellidos_str);
    }

    /**
     * Obtener fecha de nacimiento formateada
     */
    public function getFechaNacFormattedAttribute()
    {
        return $this->fecha_nac ? $this->fecha_nac->format('d/m/Y') : null;
    }
    /**
     * Obtener cédula formateada
     */
    public function getCedulaFormattedAttribute()
    {
        if ($this->cedula_act) {
            return $this->cedula_act . ($this->tipo_cedula_act ? ' (' . $this->tipo_cedula_act . ')' : '');
        }
        return null;
    }

    /**
     * Obtener dirección completa
     */
    public function getDireccionAttribute()
    {
        $direcciones = array_filter([
            $this->dom_1,
            $this->dom_2
        ]);
        return implode(' - ', $direcciones) ?: null;
    }

    /**
     * Obtener ubicación (departamento, provincia, municipio)
     */
    public function getUbicacionAttribute()
    {
        $ubicacion = array_filter([
            $this->nom_dep,
            $this->nom_prov,
            $this->nom_mun
        ]);
        return implode(' / ', $ubicacion) ?: null;
    }

    /**
     * Obtener estado del registro formateado
     */
    public function getEstadoRegistroFormattedAttribute()
    {
        $estados = [
            0 => 'Inactivo',
            1 => 'Activo',
        ];
        return $estados[$this->estado_registro] ?? 'Desconocido';
    }

    /**
     * Obtener género formateado
     */
    public function getSexoFormattedAttribute()
    {
        $sexos = [
            'M' => 'Masculino',
            'F' => 'Femenino',
            'MASCULINO' => 'Masculino',
            'FEMENINO' => 'Femenino',
        ];
        return $sexos[$this->sexo] ?? $this->sexo;
    }

    /**
     * Obtener estado civil formateado
     */
    public function getEstadoCivilFormattedAttribute()
    {
        $estados = [
            'SOLTERO' => 'Soltero/a',
            'CASADO' => 'Casado/a',
            'DIVORCIADO' => 'Divorciado/a',
            'VIUDO' => 'Viudo/a',
            'UNION_LIBRE' => 'Unión Libre',
            'CONYUGUE' => 'Cónyuge',
        ];
        return $estados[$this->estado_civil] ?? $this->estado_civil;
    }

    /**
     * Scope para filtrar por estado
     */
    public function scopeActivos($query)
    {
        return $query->where('estado_registro', 1);
    }

    /**
     * Scope para filtrar por sexo
     */
    public function scopePorSexo($query, $sexo)
    {
        return $query->where('sexo', $sexo);
    }

    /**
     * Scope para filtrar por estado civil
     */
    public function scopePorEstadoCivil($query, $estadoCivil)
    {
        return $query->where('estado_civil', $estadoCivil);
    }

    /**
     * Scope para filtrar por departamento
     */
    public function scopePorDepartamento($query, $departamento)
    {
        return $query->where('id_departamento', $departamento);
    }

    /**
     * Scope para búsqueda general
     */
    public function scopeBuscar($query, $termino)
    {
        if (!$termino) {
            return $query;
        }

        $termino = str_replace('%', ' ', $termino);

        return $query->where(function ($q) use ($termino) {
            $q->whereRaw('nombres LIKE ?', ["%{$termino}%"])
                ->orWhereRaw('ap_pat LIKE ?', ["%{$termino}%"])
                ->orWhereRaw('ap_mat LIKE ?', ["%{$termino}%"])
                ->orWhereRaw('cedula_act LIKE ?', ["%{$termino}%"])
                ->orWhereRaw('ciudadano LIKE ?', ["%{$termino}%"])
                ->orWhereRaw("CONCAT(COALESCE(nombres, ''), ' ', COALESCE(ap_pat, ''), ' ', COALESCE(ap_mat, '')) LIKE ?", ["%{$termino}%"]);
        });
    }

    static function boot()
    {
        parent::boot();

        static::creating(function ($ciudadano) {
            if (is_null($ciudadano->fecha_ins)) {
                $ciudadano->fecha_ins = now();
            }
        });
    }
}
