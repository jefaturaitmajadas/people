<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CumpleanosEnvio extends Model
{
    protected $table = 'cumpleanos_envios';

    protected $fillable = ['empleado_id', 'empresa_id', 'anio', 'correo', 'enviado_at'];

    protected $casts = ['enviado_at' => 'datetime'];

    public function empleado() { return $this->belongsTo(Empleado::class); }
}
