<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class IgssTipoPlanilla extends Model
{
    use SoftDeletes;

    protected $table = 'igss_tipo_planilla';

    protected $fillable = [
        'id_empresa',
        'identificacion',
        'nombre',
        'tipo_afiliado',
        'periodo',
        'id_igss_departamento',
        'id_actividad',
        'clase_planilla',
        'tiempo_contrato',
        'cadena_concatenada'
    ];

    public function empresa()
    {
        return $this->belongsTo(Empresa::class, 'id_empresa');
    }

    public function igssDepartamento()
    {
        return $this->belongsTo(IgssDepartamento::class, 'id_igss_departamento');
    }

    public function igssActividadEconomica()
    {
        return $this->belongsTo(IgssActividadEconomica::class, 'id_actividad');
    }
}
