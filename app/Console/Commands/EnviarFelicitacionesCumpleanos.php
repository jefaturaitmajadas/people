<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Models\Empleado;
use App\Models\CumpleanosEnvio;
use App\Mail\FelizCumpleanosMail;

class EnviarFelicitacionesCumpleanos extends Command
{
    protected $signature = 'cumpleanos:enviar
                            {--fecha= : Fecha a procesar (Y-m-d). Por defecto hoy}
                            {--forzar : Reenviar aunque ya se haya enviado este año}';

    protected $description = 'Envía el correo de feliz cumpleaños a los colaboradores activos de todas las empresas';

    public function handle()
    {
        $fecha = $this->option('fecha') ? Carbon::parse($this->option('fecha')) : Carbon::today();

        // Todas las empresas: se ignora el filtro por empresa activa
        $empleados = Empleado::withoutGlobalScope('empresa_activa')
            ->whereNotNull('fecha_nacimiento')
            ->whereNull('fecha_salida')
            ->where(function ($q) use ($fecha) {
                $q->where(function ($q) use ($fecha) {
                    $q->whereMonth('fecha_nacimiento', $fecha->month)
                      ->whereDay('fecha_nacimiento', $fecha->day);
                });

                // Los nacidos el 29 de febrero se felicitan el 28 en años no bisiestos
                if ($fecha->month == 2 && $fecha->day == 28 && !$fecha->isLeapYear()) {
                    $q->orWhere(function ($q) {
                        $q->whereMonth('fecha_nacimiento', 2)->whereDay('fecha_nacimiento', 29);
                    });
                }
            })
            ->get();

        $enviados = 0;
        $sinCorreo = 0;
        $omitidos = 0;
        $fallidos = 0;

        foreach ($empleados as $empleado) {
            $correo = $empleado->correo_notificacion;

            if (!$correo) {
                $sinCorreo++;
                $this->line("Sin correo: {$empleado->nombre_completo}");
                continue;
            }

            $yaEnviado = CumpleanosEnvio::where('empleado_id', $empleado->id)
                ->where('anio', $fecha->year)
                ->exists();

            if ($yaEnviado && !$this->option('forzar')) {
                $omitidos++;
                continue;
            }

            try {
                Mail::to($correo)->send(new FelizCumpleanosMail($empleado));

                CumpleanosEnvio::updateOrCreate(
                    ['empleado_id' => $empleado->id, 'anio' => $fecha->year],
                    ['empresa_id' => $empleado->empresa_id, 'correo' => $correo, 'enviado_at' => now()]
                );

                $enviados++;
                $this->info("Enviado: {$empleado->nombre_completo} <{$correo}>");
            } catch (\Exception $e) {
                // Si falla un correo, lo saltamos y seguimos con los demás
                $fallidos++;
                $this->error("Error con {$empleado->nombre_completo}: {$e->getMessage()}");
                report($e);
            }
        }

        $this->info("Fecha {$fecha->format('d/m/Y')} | Cumpleañeros: {$empleados->count()} | Enviados: {$enviados} | Sin correo: {$sinCorreo} | Ya enviados: {$omitidos} | Fallidos: {$fallidos}");

        return self::SUCCESS;
    }
}
