<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Municipio extends Model
{
    use SoftDeletes;

    // Indicamos el nombre exacto de la tabla
    protected $table = 'municipio';

    protected $fillable = [
        'nombre',
        'codigo_empl',
        'codigo_igss',
        'id_igss_departamento'
    ];

    // Relación a IgssDepartamento
    public function igssDepartamento()
    {
        return $this->belongsTo(IgssDepartamento::class, 'id_igss_departamento');
    }
}
