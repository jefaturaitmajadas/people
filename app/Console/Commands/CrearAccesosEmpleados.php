<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Empleado;
use App\Models\User;

class CrearAccesosEmpleados extends Command
{
    protected $signature = 'empleados:crear-accesos';

    protected $description = 'Crea el usuario de los colaboradores activos (usuario y primera clave: su identificación)';

    public function handle()
    {
        // Todas las empresas: se ignora el filtro por empresa activa
        $empleados = Empleado::withoutGlobalScope('empresa_activa')->whereNull('fecha_salida')->get();

        $creados = 0;
        $existentes = 0;
        $sinIdentificacion = 0;
        $duplicados = 0;

        foreach ($empleados as $empleado) {
            if (User::normalizarUsuario($empleado->identificacion) === '') {
                $sinIdentificacion++;
                continue;
            }

            $user = User::sincronizarAccesoEmpleado($empleado);

            if (!$user) {
                $duplicados++;
                $this->warn("Identificación repetida: {$empleado->nombre_completo} (código {$empleado->codigo_empleado})");
            } elseif ($user->wasRecentlyCreated) {
                $creados++;
            } else {
                $existentes++;
            }
        }

        $this->info("Activos: {$empleados->count()} | Creados: {$creados} | Ya tenían usuario: {$existentes} | Sin identificación: {$sinIdentificacion} | Identificación repetida: {$duplicados}");

        return self::SUCCESS;
    }
}
