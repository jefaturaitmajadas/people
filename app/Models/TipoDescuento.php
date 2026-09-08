<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TipoDescuento extends Model
{
    use SoftDeletes;
    protected $table = 'tipo_descuentos';
    protected $fillable = ['nombre'];
}
