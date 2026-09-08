<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HoraExtra extends Model
{
    use SoftDeletes;
    protected $table = 'horas_extras';

    protected $fillable = [
        'empleado_id',
        'usuario_creador_id',
        'fecha_presentacion',
        'cantidad_horas',
        'salario_por_hora_sugerido',
        'factor_multiplicador',
        'total_pagar',
        'observaciones',
        'pagado',
        'fecha_pagada',
        'usuario_confirma_id'
    ];

    public function empleado() { return $this->belongsTo(Empleado::class); }
    public function usuarioCreador() { return $this->belongsTo(User::class, 'usuario_creador_id'); }
    public function usuarioConfirma() { return $this->belongsTo(User::class, 'usuario_confirma_id'); }
}