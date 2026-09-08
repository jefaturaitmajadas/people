<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Turno extends Model
{
    use SoftDeletes;

    // Indicamos el nombre exacto de la tabla
    protected $table = 'turno';

    protected $fillable = [
        'nombre',
        'id_empl_jornada'
    ];

    // Relación a EmplJornadaTrabajo
    public function emplJornadaTrabajo()
    {
        return $this->belongsTo(EmplJornadaTrabajo::class, 'id_empl_jornada');
    }
}
