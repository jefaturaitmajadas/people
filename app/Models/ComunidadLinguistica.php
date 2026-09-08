<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ComunidadLinguistica extends Model
{
    use SoftDeletes;

    // Indicamos el nombre exacto de la tabla
    protected $table = 'comunidad_linguistica';

    protected $fillable = [
        'nombre',
        'id_empl_linguistica'
    ];

    // Relación a EmplComunidadLinguistica
    public function emplComunidadLinguistica()
    {
        return $this->belongsTo(EmplComunidadLinguistica::class, 'id_empl_linguistica');
    }
}