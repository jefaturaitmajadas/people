<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class NivelEducativo extends Model
{
    use SoftDeletes;

    // Indicamos el nombre exacto de la tabla
    protected $table = 'nivel_educativo';

    protected $fillable = [
        'nombre',
        'id_empl_nivel_edu'
    ];

    // Relación a EmplNivelEducativo
    public function emplNivelEducativo()
    {
        return $this->belongsTo(EmplNivelEducativo::class, 'id_empl_nivel_edu');
    }
}
