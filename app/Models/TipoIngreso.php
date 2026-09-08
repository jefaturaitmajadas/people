<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TipoIngreso extends Model
{
    use SoftDeletes;
    protected $table = 'tipo_ingresos';
    protected $fillable = ['nombre'];
}
