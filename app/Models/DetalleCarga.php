<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DetalleCarga extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'carga_id', 'empleado_id', 'sueldo_base', 'salario_diario', 
        'dias_laborados', 'horas_extras', 'salario', 'desc_igss', 
        'salario_liquido', 'bonificacion_ley', 'bonificacion_extra', 
        'descuentos_varios', 'isr', 'liquido'
    ];

    

    public function empleado()
    {
        return $this->belongsTo(Empleado::class);
    }

    public function carga()
    {
        // El withTrashed() permite acceder a la carga aunque haya sido eliminada (SoftDelete)
        return $this->belongsTo(Carga::class)->withTrashed();
    }
}
