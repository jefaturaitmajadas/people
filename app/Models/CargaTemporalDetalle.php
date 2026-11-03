<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CargaTemporalDetalle extends Model
{
    protected $fillable = [
        'carga_temporal_id', 'codigo_empleado', 'nombres', 'puesto', 'departamento', 'dpi',
        'salario_hora', 'cantidad_horas_global', 'salario_base', 'cantidad_horas', 'horas_extras', 
        'cantidad_horas_dobles', 'horas_dobles', 'comisiones', 'bonificacion', 'bonificacion_incentivo', 
        'retroactivos', 'total_ingresos', 'igss_laboral', 'isr', 'anticipos_salario_1', 
        'anticipos_salario_2', 'otros_descuentos', 'total_deducciones', 'total_neto', 'forma_pago', 
        'tipo_planilla', 'correo_enviado'
    ];

    public function cargaTemporal()
    {
        return $this->belongsTo(CargaTemporal::class);
    }
}