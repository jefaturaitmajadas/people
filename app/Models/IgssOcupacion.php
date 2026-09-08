<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class IgssOcupacion extends Model
{
    use SoftDeletes;

    // Indicamos el nombre exacto de la tabla
    protected $table = 'igss_ocupacion';

    protected $fillable = [
        'gran_grupo',
        'sub_grupo_principal',
        'sub_grupo',
        'grupo_primario',
        'descripcion'
    ];
}