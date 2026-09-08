<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Discapacidad extends Model
{
    use SoftDeletes;

    // Indicamos el nombre exacto de la tabla
    protected $table = 'discapacidad';

    protected $fillable = [
        'nombre',
        'id_empl_tipo_capacidad'
    ];

    // Relación a EmplTipoDiscapacidad
    public function emplTipoDiscapacidad()
    {
        // Pasamos el modelo y el nombre de la llave foránea
        return $this->belongsTo(EmplTipoDiscapacidad::class, 'id_empl_tipo_capacidad');
    }
}