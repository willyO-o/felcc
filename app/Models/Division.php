<?php

namespace App\Models;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

use Illuminate\Database\Eloquent\Model;

class Division extends Model implements AuditableContract
{
    use \OwenIt\Auditing\Auditable;

    //
    protected $table = 'division';
    protected $fillable = ['division'];
}
