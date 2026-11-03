<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Prestamo extends Model
{
    protected $fillable = ['id_prestamo','empleado_id', 'monto_solicitado', 'monto_total', 'frecuencia', 'cantidad_cuotas', 'estado'];

    public function empleado()
    {
        return $this->belongsTo(Empleado::class);
    }

    public function cuotas()
    {
        return $this->hasMany(PrestamoCuota::class);
    }
}
