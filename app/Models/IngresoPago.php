<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class IngresoPago extends Model
{
    protected $table = 'ingreso_pagos';
    protected $fillable = [
        'ingreso_id', 'numero_cuota', 'monto', 'fecha_pago_proyectada', 
        'pagado', 'fecha_pago', 'usuario_confirma_id'
    ];

    public function ingreso() { return $this->belongsTo(Ingreso::class); }
    public function usuarioConfirma() { return $this->belongsTo(User::class, 'usuario_confirma_id'); }
}