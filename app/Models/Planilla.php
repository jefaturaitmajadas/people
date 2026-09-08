<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\ScopesByEmpresa;
use Illuminate\Database\Eloquent\SoftDeletes;

class Planilla extends Model
{
    use SoftDeletes;
    use ScopesByEmpresa;
    protected $table = 'planillas';

    protected $fillable = [
        'codigo', 'descripcion', 'fecha_inicio', 'fecha_fin', 
        'tipo_periodo', 'estado', 'total_nomina', 
        'usuario_creador_id', 'usuario_cierre_id', 'fecha_cierre','empresa_id'
    ];

    public function detalles()
    {
        return $this->hasMany(PlanillaDetalle::class);
    }

    public function creador()
    {
        return $this->belongsTo(User::class, 'usuario_creador_id');
    }

    public function cerrador()
    {
        return $this->belongsTo(User::class, 'usuario_cierre_id');
    }

    public function empresa()
    {
        return $this->belongsTo(Empresa::class, 'empresa_id');
    }
}
