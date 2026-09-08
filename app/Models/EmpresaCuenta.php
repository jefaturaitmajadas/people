<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\ScopesByEmpresa; // <-- Agregamos el Trait de Multi-empresa

class EmpresaCuenta extends Model
{
    use ScopesByEmpresa;

    protected $fillable = ['empresa_id', 'banco_id', 'numero_cuenta', 'descripcion', 'estado'];

    public function banco()
    {
        return $this->belongsTo(Banco::class);
    }
}
