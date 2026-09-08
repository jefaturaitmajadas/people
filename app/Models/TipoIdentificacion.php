<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TipoIdentificacion extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'nombre',
        'id_empl_doc_ident' // ¡Agregado!
    ];

    // Relación nueva
    public function emplDocIdentificacion()
    {
        return $this->belongsTo(EmplDocIdentificacion::class, 'id_empl_doc_ident');
    }
}
