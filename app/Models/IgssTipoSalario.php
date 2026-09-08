<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class IgssTipoSalario extends Model
{
    use SoftDeletes;

    // Indicamos explícitamente el nombre de la tabla
    protected $table = 'igss_tipo_salario';

    protected $fillable = [
        'codigo',
        'descripcion'
    ];
}
