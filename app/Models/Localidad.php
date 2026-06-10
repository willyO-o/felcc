<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;



class Localidad extends Model
{
    //

    protected $table = 'localidad';
    protected $fillable = [
        'dep_loc',
        'prov_loc',
        'sec_loc',
        'can',
        'loc',
        'nom_dep',
        'nom_prov',
        'nom_sec',
        'nom_can',
        'nom_loc',
    ];

    public function notarios()
    {
        return $this->hasMany(Notario::class, 'id_loc_not_e');
    }

    public function libros()
    {
        return $this->hasMany(Libro::class, 'id_loc_libro');
    }
}
