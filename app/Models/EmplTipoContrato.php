<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmplTipoContrato extends Model
{
    use SoftDeletes;

    // Indicamos explícitamente el nombre de la tabla
    protected $table = 'empl_tipo_contrato';

    protected $fillable = [
        'codigo',
        'tipo_contrato'
    ];
}