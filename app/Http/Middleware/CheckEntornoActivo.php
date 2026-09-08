<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckEntornoActivo
{
    public function handle(Request $request, Closure $next): Response
    {
        // Si no hay una empresa activa en la sesión, lo regresamos al selector
        if (!session()->has('empresa_activa_id')) {
            return redirect()->route('dashboard')->with('error', 'Por favor, selecciona una empresa para comenzar a trabajar.');
        }

        return $next($request);
    }
}