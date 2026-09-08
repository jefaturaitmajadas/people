<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Puesto extends Model
{
    use SoftDeletes;

    // Indicamos explícitamente el nombre de la tabla
    protected $table = 'puesto';

    protected $fillable = [
        'nombre',
        'descriptor_de_puesto',
        'responsabilidades',
        'funciones_generales',
        'funciones_especificas',
        'salario_base'
    ];
}
