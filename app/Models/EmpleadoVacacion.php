<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmpleadoVacacion extends Model
{
    protected $table = 'empleado_vacaciones';
    
    protected $fillable = [
        'empleado_id',
        'fecha_inicio',
        'fecha_fin',
        'dias_tomados',
        'observaciones'
    ];

    public function empleado()
    {
        return $this->belongsTo(Empleado::class);
    }
}
