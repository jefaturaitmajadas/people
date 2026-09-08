<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ingreso extends Model
{
    use SoftDeletes;
    protected $table = 'ingresos';
    protected $fillable = [
        'empleado_id', 'tipo_ingreso_id', 'es_recurrente', 'descripcion', 
        'monto_total', 'cantidad_cuotas', 'monto_cuota', 'fecha_inicio', 
        'fecha_fin', 'estado', 'observaciones'
    ];

    public function empleado() { return $this->belongsTo(Empleado::class); }
    public function tipoIngreso() { return $this->belongsTo(TipoIngreso::class); }
    public function pagosDetalle() { return $this->hasMany(IngresoPago::class, 'ingreso_id'); }
}
