<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmplTipoDiscapacidad extends Model
{
    use SoftDeletes;

    // Indicamos explícitamente el nombre de la tabla
    protected $table = 'empl_tipo_discapacidad';

    protected $fillable = [
        'codigo',
        'discapacidad'
    ];
}