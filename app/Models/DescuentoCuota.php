<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DescuentoCuota extends Model
{
    protected $table = 'descuento_cuotas';
    
    protected $fillable = [
        'descuento_id', 
        'numero_cuota', 
        'monto', 
        'fecha_cobro_proyectada', 
        'pagado', 
        'fecha_pago', 
        'usuario_confirma_id'
    ];

    // A qué descuento (maestro) pertenece
    public function descuento() 
    { 
        return $this->belongsTo(Descuento::class); 
    }

    // Quién autorizó el pago (Relación con la tabla users)
    public function usuarioConfirma() 
    {
        return $this->belongsTo(User::class, 'usuario_confirma_id');
    }
}