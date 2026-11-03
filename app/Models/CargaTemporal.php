<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CargaTemporal extends Model
{
    protected $fillable = ['descripcion', 'fecha_inicio', 'fecha_fin', 'estado'];

    public function detalles()
    {
        return $this->hasMany(CargaTemporalDetalle::class);
    }
}