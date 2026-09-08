<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmplNacionalidad extends Model
{
    use SoftDeletes;

    // Indicamos explícitamente el nombre de la tabla
    protected $table = 'empl_nacionalidad';

    protected $fillable = [
        'codigo',
        'pais',
        'nacionalidad'
    ];
}