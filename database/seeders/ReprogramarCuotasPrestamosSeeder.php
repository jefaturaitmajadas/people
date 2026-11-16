<?php

namespace Database\Seeders;

use App\Models\Empleado;
use App\Models\Prestamo;
use App\Models\PrestamoCuota;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

/**
 * Reprograma las cuotas pendientes con las fechas que usa PrestamoController: quincenal el 1 y
 * el 16, mensual el 1. La planilla cobra lo que tenga fecha hasta su último día, así cada
 * quincena toma una sola cuota; con las fechas cargadas (1 y 15) una quedaba sin descuento
 * y la siguiente cobraba doble.
 *
 * Correr después de AplicarPagosOctubrePrestamosSeeder y antes de cerrar la primera planilla
 * de noviembre. Un préstamo con pagos desde noviembre se omite, por eso se puede repetir.
 */
class ReprogramarCuotasPrestamosSeeder extends Seeder
{
    private const PRIMERA_FECHA = '2026-11-01';
    private const RESPALDO = 'respaldos/prestamos_antes_reprogramar_cuotas.json';

    // Su tipo de planilla en PlanillaOctubre2026.xlsx es quincenal
    private const FRECUENCIAS_CORREGIDAS = ['50' => 'Quincenal', '123' => 'Quincenal', '209' => 'Quincenal'];

    public function run()
    {
        $pagosOctubre = PrestamoCuota::where('numero_cuota', '>', 0)->where('pagado', true)
            ->whereBetween('fecha_pagado', ['2026-10-15', '2026-10-31']);

        if (!$pagosOctubre->exists()) {
            $this->command->error('Primero corre AplicarPagosOctubrePrestamosSeeder.');
            return;
        }

        $disco = Storage::build(['driver' => 'local', 'root' => storage_path('app')]);

        if (!$disco->exists(self::RESPALDO)) {
            $disco->put(self::RESPALDO, json_encode([
                'prestamos' => DB::table('prestamos')->get(),
                'prestamo_cuotas' => DB::table('prestamo_cuotas')->get(),
            ]));
        }

        $reprogramados = 0;
        $omitidos = 0;

        DB::transaction(function () use (&$reprogramados, &$omitidos) {
            // Lo pagado en octubre deja de ocupar fechas de noviembre
            PrestamoCuota::where('numero_cuota', '>', 0)->where('pagado', true)
                ->whereBetween('fecha_pagado', ['2026-10-15', '2026-10-31'])
                ->update(['fecha_aplicacion' => DB::raw('fecha_pagado')]);

            foreach (self::FRECUENCIAS_CORREGIDAS as $codigo => $frecuencia) {
                $empleado = Empleado::where('codigo_empleado', $codigo)->first();

                if ($empleado) {
                    Prestamo::where('empleado_id', $empleado->id)->where('estado', 'Activo')->update(['frecuencia' => $frecuencia]);
                }
            }

            foreach (Prestamo::where('estado', 'Activo')->get() as $prestamo) {
                $yaCobroNoviembre = $prestamo->cuotas()->where('pagado', true)
                    ->where('fecha_aplicacion', '>=', self::PRIMERA_FECHA)
                    ->exists();

                if ($yaCobroNoviembre) {
                    $omitidos++;
                    continue;
                }

                $fecha = Carbon::parse(self::PRIMERA_FECHA);

                foreach ($prestamo->cuotas()->where('pagado', false)->orderBy('numero_cuota')->get() as $cuota) {
                    $cuota->update(['fecha_aplicacion' => $fecha->toDateString()]);
                    $fecha = $this->siguienteFecha($fecha, $prestamo->frecuencia);
                }

                $reprogramados++;
            }
        });

        $this->command->info("Préstamos reprogramados: {$reprogramados} | Omitidos: {$omitidos}");
    }

    private function siguienteFecha(Carbon $fecha, string $frecuencia): Carbon
    {
        if ($frecuencia === 'Quincenal' && $fecha->day === 1) {
            return $fecha->copy()->day(16);
        }

        return $fecha->copy()->addMonthNoOverflow()->startOfMonth();
    }
}
