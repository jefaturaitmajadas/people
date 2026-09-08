<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pueblo extends Model
{
    use SoftDeletes;

    // Indicamos el nombre exacto de la tabla
    protected $table = 'pueblo';

    protected $fillable = [
        'nombre',
        'id_empl_pueblo_per'
    ];

    // Relación a EmplPuebloPertenencia
    public function emplPuebloPertenencia()
    {
        return $this->belongsTo(EmplPuebloPertenencia::class, 'id_empl_pueblo_per');
    }
}