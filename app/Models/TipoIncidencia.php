<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TipoIncidencia extends Model
{
    use SoftDeletes;
    protected $table = 'tipo_incidencias';
    
    protected $fillable = [
        'nombre', 
        'descuenta_salario',
        'porcentaje_descuento',
    ];

    public function incidencias()
        {
            return $this->hasMany(Incidencia::class, 'tipo_incidencia_id');
        }
}