<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class IgssPais extends Model
{
    use SoftDeletes;

    // Indicamos el nombre exacto de la tabla en la BD
    protected $table = 'igss_pais';

    protected $fillable = [
        'codigo',
        'campo'
    ];
}
