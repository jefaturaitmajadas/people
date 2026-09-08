<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmplComunidadLinguistica extends Model
{
    use SoftDeletes;

    // Indicamos explícitamente el nombre de la tabla
    protected $table = 'empl_comunidad_linguistica';

    protected $fillable = [
        'codigo',
        'comunidad_linguistica'
    ];
}
