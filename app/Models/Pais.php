<?php

namespace App\Models;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

use Illuminate\Database\Eloquent\Model;

class Pais extends Model implements AuditableContract
{
    use \OwenIt\Auditing\Auditable;

    //
    protected $table = 'pais';
    protected $fillable = ['pais', 'gentilicio'];
}
