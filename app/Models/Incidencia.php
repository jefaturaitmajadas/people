<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Incidencia extends Model
{
    use SoftDeletes;
    protected $table = 'incidencias';

    protected $fillable = [
        'empleado_id',
        'tipo_incidencia_id',
        'usuario_creador_id',
        'fecha_inicio',
        'fecha_fin',
        'cantidad_dias',
        'observaciones',
        'documento_adjunto',
        'aplicado_en_planilla',
        'fecha_aplicacion',
        'usuario_confirma_id'
    ];

    public function empleado() { return $this->belongsTo(Empleado::class); }
    public function tipoIncidencia() { return $this->belongsTo(TipoIncidencia::class); }
    public function usuarioCreador() { return $this->belongsTo(User::class, 'usuario_creador_id'); }
    public function usuarioConfirma() { return $this->belongsTo(User::class, 'usuario_confirma_id'); }
}
