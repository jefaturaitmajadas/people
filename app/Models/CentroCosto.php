<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CentroCosto extends Model
{
    use SoftDeletes;

    // Indicamos explícitamente el nombre de la tabla
    protected $table = 'centro_costo';

    protected $fillable = [
        'nombre',
        'codigo_sap'
    ];
}
