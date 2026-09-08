<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmplNivelEducativo extends Model
{
    use SoftDeletes;

    // Indicamos explícitamente el nombre de la tabla
    protected $table = 'empl_nivel_educativo';

    protected $fillable = [
        'codigo',
        'nivel_educativo'
    ];
}
