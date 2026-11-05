<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ControlAccesoEmpleado
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user) {
            return $next($request);
        }

        $empleado = null;

        if ($user->isEmpleado()) {
            $empleado = $user->empleado;

            // Si ya no pertenece a la empresa pierde el acceso, aunque tenga la sesión abierta
            if (!$empleado || $empleado->fecha_salida) {
                Auth::guard('web')->logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return redirect()->route('login')->withErrors(['email' => 'Tu acceso está deshabilitado. Comunícate con Recursos Humanos.']);
            }

            // El colaborador no elige entorno: siempre trabaja en su empresa
            if (session('empresa_activa_id') != $empleado->empresa_id) {
                session(['empresa_activa_id' => $empleado->empresa_id]);
                session(['empresa_activa_nombre' => $empleado->empresa?->nombre]);
                session(['empresa_activa_logo' => $empleado->empresa?->logo]);
            }
        }

        if ($request->routeIs('logout', 'password.cambiar', 'password.cambiar.update')) {
            return $next($request);
        }

        if ($user->debe_cambiar_password) {
            return redirect()->route('password.cambiar');
        }

        if ($empleado) {
            // Todo lo que no sea su propia ficha queda bloqueado
            $ficha = $request->route('empleado');
            $fichaId = is_object($ficha) ? $ficha->getKey() : $ficha;

            if ($request->routeIs('empleados.edit') && (int) $fichaId === $empleado->id) {
                return $next($request);
            }

            if (!$request->isMethod('GET')) {
                abort(403, 'No tienes permiso para realizar esta acción.');
            }

            return redirect()->route('empleados.edit', $empleado->id);
        }

        return $next($request);
    }
}
