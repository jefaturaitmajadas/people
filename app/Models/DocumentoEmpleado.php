<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DocumentoEmpleado extends Model
{
    use SoftDeletes;
    
    // Forzamos el nombre exacto de la tabla
    protected $table = 'documento_empleado';

    protected $fillable = [
        'empleado_id', 
        'tipo_documento_id', 
        'descripcion', 
        'fecha_emision', 
        'fecha_vencimiento',
        'observaciones',
        'archivo_ruta'
    ];

    // Relación: este documento pertenece a un empleado
    public function empleado()
    {
        return $this->belongsTo(Empleado::class);
    }

    // Relación: este documento tiene un tipo de documento
    public function tipoDocumento()
    {
        return $this->belongsTo(TipoDocumento::class, 'tipo_documento_id');
    }
}
