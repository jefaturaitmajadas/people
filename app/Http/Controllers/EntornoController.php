<?php

namespace App\Http\Controllers;

use App\Models\Empresa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EntornoController extends Controller
{
    // Muestra el Dashboard con las empresas permitidas
    public function seleccionarEntorno()
    {
        $user = Auth::user();

        if ($user->isAdministrador()) {
            // El admin ve TODAS las empresas
            $empresas = Empresa::all();
        } else {
            // Gerentes y Generalistas solo ven las suyas
            $empresas = $user->empresas;
        }

        return view('dashboard', compact('empresas'));
    }

    // Guarda en sesión la empresa elegida y entra al sistema
    public function activarEntorno(Empresa $empresa)
    {
        $user = Auth::user();

        // Validar por seguridad que tenga acceso
        if (!$user->isAdministrador() && !$user->empresas->contains($empresa->id)) {
            abort(403, 'No tienes permiso para acceder a esta empresa.');
        }

        // Guardar la empresa en la sesión global
        session(['empresa_activa_id' => $empresa->id]);
        session(['empresa_activa_nombre' => $empresa->nombre]);
        session(['empresa_activa_logo' => $empresa->logo]);

        // Redirigir al módulo principal (ej. Empleados)
        return redirect()->route('empleados.index')->with('success', 'Entorno cambiado a: ' . $empresa->nombre);
    }
}