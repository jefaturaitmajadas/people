<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pais extends Model
{
    use SoftDeletes;

    // Nombre exacto de la tabla
    protected $table = 'pais';

    protected $fillable = [
        'nombre',
        'id_empl_nacionalidad',
        'id_igss_pais'
    ];

    // Relación a EmplNacionalidad
    public function emplNacionalidad()
    {
        return $this->belongsTo(EmplNacionalidad::class, 'id_empl_nacionalidad');
    }

    // Relación a IgssPais
    public function igssPais()
    {
        return $this->belongsTo(IgssPais::class, 'id_igss_pais');
    }
}
