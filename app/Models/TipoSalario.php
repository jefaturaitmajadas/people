<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TipoSalario extends Model
{
    use SoftDeletes;

    // Indicamos el nombre exacto de la tabla
    protected $table = 'tipo_salario';

    protected $fillable = [
        'nombre',
        'id_igss_tipo_salario'
    ];

    // Relación a IgssTipoSalario
    public function igssTipoSalario()
    {
        return $this->belongsTo(IgssTipoSalario::class, 'id_igss_tipo_salario');
    }
}
