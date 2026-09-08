<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PrestamoCuota extends Model
{
    protected $fillable = [
        'prestamo_id', 'numero_cuota', 'fecha_aplicacion', 'monto_capital', 
        'monto_ahorro', 'monto_total', 'pagado', 'fecha_pagado'
    ];

    public function prestamo()
    {
        return $this->belongsTo(Prestamo::class);
    }

    public function planilla()
    {
        return $this->belongsTo(Planilla::class);
    }
}
