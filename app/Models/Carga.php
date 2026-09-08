<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Carga extends Model
{
    use SoftDeletes;

    protected $fillable = ['fecha_carga', 'mes_planilla', 'anio_planilla'];

    public function detalleCargas()
    {
        return $this->hasMany(DetalleCarga::class);
    }
}