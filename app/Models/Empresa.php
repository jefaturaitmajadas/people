<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Empresa extends Model
{
    use SoftDeletes;

    protected $fillable = ['nombre', 'nit','logo','direccion','telefono','correo','firmante_rrhh','razon_social','numero_patronal'];

    public function empleados()
    {
        return $this->hasMany(Empleado::class);
    }

    public function usuarios()
    {
        return $this->belongsToMany(User::class);
    }
}