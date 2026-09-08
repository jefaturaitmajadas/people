<?php

namespace App\Http\Controllers;

use App\Models\Ingreso;
use Illuminate\Http\Request;

class IngresoPagoController extends Controller
{
    public function index(Ingreso $ingreso)
    {
        $ingreso->load('empleado', 'tipoIngreso');
        $pagos = $ingreso->pagosDetalle()->orderBy('numero_cuota', 'asc')->get();
        
        return view('ingreso_pagos.index', compact('ingreso', 'pagos'));
    }
}
