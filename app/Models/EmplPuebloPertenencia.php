<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmplPuebloPertenencia extends Model
{
    use SoftDeletes;

    // Indicamos explícitamente el nombre de la tabla
    protected $table = 'empl_pueblo_pertenencia';

    protected $fillable = [
        'codigo',
        'pueblo_de_pertenencia'
    ];
}
