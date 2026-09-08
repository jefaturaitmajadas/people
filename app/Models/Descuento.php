<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Descuento extends Model
{
    use SoftDeletes;
    protected $table = 'descuentos';

    protected $fillable = [
        'empleado_id', 
        'tipo_descuento_id', 
        'es_recurrente', // NUEVO
        'descripcion', 
        'monto_total', 
        'cantidad_cuotas', // NUEVO
        'cuota', 
        'saldo', 
        'fecha_inicio', 
        'fecha_fin', 
        'estado', 
        'observaciones'
    ];

    public function empleado() 
    { 
        return $this->belongsTo(Empleado::class); 
    }
    
    public function tipoDescuento() 
    { 
        return $this->belongsTo(TipoDescuento::class); 
    }

    // Relación hacia la tabla de amortización
    public function cuotasDetalle() 
    { 
        return $this->hasMany(DescuentoCuota::class, 'descuento_id'); 
    }
}