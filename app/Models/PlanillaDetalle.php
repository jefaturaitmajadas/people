<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlanillaDetalle extends Model
{
    protected $table = 'planilla_detalles';

    protected $fillable = [
        'planilla_id', 'empleado_id', 'dias_trabajados', 'salario_base_mes',
        'salario_proporcional', 'total_horas_extras', 'total_ingresos_adicionales',
        'igss_laboral', 'total_descuentos', 'liquido_a_recibir','isr','bono_decreto','pago_prestamos'
    ];

    public function planilla()
    {
        return $this->belongsTo(Planilla::class);
    }

    public function empleado()
    {
        return $this->belongsTo(Empleado::class);
    }
}