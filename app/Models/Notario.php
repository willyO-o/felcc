<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notario extends Model
{
    //
    protected $table = 'notario';
    protected $fillable = [
        'cod_notario',
        'id_loc_not_e',
        'nom_not_e',
        'direccion',
        'zona'
    ];


    public function localidad()
    {
        return $this->belongsTo(Localidad::class, 'id_loc_not_e');
    }

}
