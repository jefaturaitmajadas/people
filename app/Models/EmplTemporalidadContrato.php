<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmplTemporalidadContrato extends Model
{
    use SoftDeletes;

    // Indicamos explícitamente el nombre de la tabla
    protected $table = 'empl_temporalidad_contrato';

    protected $fillable = [
        'codigo',
        'temporalidad_contrato'
    ];
}