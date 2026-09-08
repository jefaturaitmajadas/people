<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReferenciaPersonal extends Model
{
    use SoftDeletes;
    
    // Forzamos el nombre exacto de la tabla
    protected $table = 'referencia_personal';

    protected $fillable = [
        'empleado_id', 
        'nombre', 
        'empresa', 
        'telefono'
    ];

    // Relación: esta referencia pertenece a un empleado
    public function empleado()
    {
        return $this->belongsTo(Empleado::class);
    }
}