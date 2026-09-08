<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmplJornadaTrabajo extends Model
{
    use SoftDeletes;

    // Indicamos explícitamente el nombre de la tabla
    protected $table = 'empl_jornada_trabajo';

    protected $fillable = [
        'codigo',
        'jornada_trabajo'
    ];
}
