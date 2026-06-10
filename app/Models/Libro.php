<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Libro extends Model
{
    //
    protected $table = 'libro';
    protected $fillable = [
        'num_libro',
        'cod_notario',
        'id_loc_libro',
        'circun',
        'nom_circun',
        'dist',
        'nom_dist',
        'zona',
        'nom_zona',
        'reci',
        'nom_reci'
    ];

    public function localidad()
    {
        return $this->belongsTo(Localidad::class, 'id_loc_libro');
    }

}
