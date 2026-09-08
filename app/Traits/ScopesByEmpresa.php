<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

trait ScopesByEmpresa
{
    protected static function bootScopesByEmpresa()
    {
        // 1. EL FILTRO DE LECTURA (Global Scope)
        static::addGlobalScope('empresa_activa', function (Builder $builder) {
            if (session()->has('empresa_activa_id')) {
                // Filtra automáticamente por la empresa de la sesión
                // Usa getQuery()->from para evitar errores si haces un "Join" con otras tablas
                $builder->where($builder->getQuery()->from . '.empresa_id', session('empresa_activa_id'));
            }
        });

        // 2. LA INYECCIÓN AUTOMÁTICA EN CREACIÓN
        static::creating(function ($model) {
            if (session()->has('empresa_activa_id')) {
                // Cuando hagas Empleado::create(), le inyecta la empresa por debajo
                $model->empresa_id = session('empresa_activa_id');
            }
        });
    }
}