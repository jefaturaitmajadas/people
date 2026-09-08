<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Genero extends Model
{
    use SoftDeletes;

    // Indicamos el nombre exacto de la tabla
    protected $table = 'genero';

    protected $fillable = [
        'nombre',
        'id_empl_sexo'
    ];

    // Relación a EmplSexo
    public function emplSexo()
    {
        return $this->belongsTo(EmplSexo::class, 'id_empl_sexo');
    }
}
