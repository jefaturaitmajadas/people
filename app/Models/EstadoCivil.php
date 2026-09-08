<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EstadoCivil extends Model
{
    use SoftDeletes;

    // Indicamos el nombre exacto de la tabla
    protected $table = 'estado_civil';

    protected $fillable = [
        'nombre',
        'id_empl_estado_civil'
    ];

    // Relación a EmplEstadoCivil
    public function emplEstadoCivil()
    {
        return $this->belongsTo(EmplEstadoCivil::class, 'id_empl_estado_civil');
    }
}