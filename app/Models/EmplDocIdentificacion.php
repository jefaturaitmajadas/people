<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmplDocIdentificacion extends Model
{
    use SoftDeletes;

    protected $table = 'empl_doc_identificacion';

    protected $fillable = [
        'codigo',
        'documento_identificacion'
    ];
}
