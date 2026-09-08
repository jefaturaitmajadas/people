<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TipoContrato extends Model
{
    use SoftDeletes;

    // Indicamos el nombre exacto de la tabla
    protected $table = 'tipo_contrato';

    protected $fillable = [
        'nombre',
        'id_empl_temp_con'
    ];

    // Relación a EmplTemporalidadContrato
    public function emplTemporalidadContrato()
    {
        return $this->belongsTo(EmplTemporalidadContrato::class, 'id_empl_temp_con');
    }
}
