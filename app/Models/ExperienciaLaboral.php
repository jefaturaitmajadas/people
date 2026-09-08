<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ExperienciaLaboral extends Model
{
    use SoftDeletes;
    
    protected $table = 'experiencia_laboral';

    protected $fillable = [
        'empleado_id', 'fecha_inicio', 'fecha_fin', 'empresa', 
        'telefono_empresa', 'direccion_empresa', 'puesto', 
        'motivo_retiro', 'observaciones', 'primer_salario', 'segundo_salario'
    ];

    // Relación inversa: esta experiencia pertenece a un empleado
    public function empleado()
    {
        return $this->belongsTo(Empleado::class);
    }
}
