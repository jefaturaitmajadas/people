<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class IgssActividadEconomica extends Model
{
    use SoftDeletes;

    // Le decimos a Laravel el nombre exacto de la tabla en la BD
    protected $table = 'igss_actividad_economica';

    protected $fillable = [
        'categoria',
        'division',
        'grupo',
        'clase',
        'clasificacion',
        'descripcion'
    ];
}
