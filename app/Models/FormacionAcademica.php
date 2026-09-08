<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FormacionAcademica extends Model
{
    use SoftDeletes;
    
    // Forzamos el nombre exacto de la tabla
    protected $table = 'formacion_academica';

    protected $fillable = [
        'empleado_id', 
        'fecha_inicio', 
        'fecha_fin', 
        'grado_academico_id', 
        'institucion'
    ];

    // Relación: esta formación pertenece a un empleado
    public function empleado()
    {
        return $this->belongsTo(Empleado::class);
    }

    // Relación: esta formación tiene un grado académico
    public function gradoAcademico()
    {
        return $this->belongsTo(GradoAcademico::class, 'grado_academico_id');
    }
}
