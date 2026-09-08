<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ocupacion extends Model
{
    use SoftDeletes;

    // Indicamos el nombre exacto de la tabla
    protected $table = 'ocupacion';

    protected $fillable = [
        'nombre',
        'id_emple_ocupacion',
        'id_igss_ocupacion'
    ];

    // Relación a EmpleOcupacion
    public function empleOcupacion()
    {
        return $this->belongsTo(EmpleOcupacion::class, 'id_emple_ocupacion');
    }

    // Relación a IgssOcupacion
    public function igssOcupacion()
    {
        return $this->belongsTo(IgssOcupacion::class, 'id_igss_ocupacion');
    }
}