<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PrestamoCuota;
use Carbon\Carbon;

class ActualizarFechasCuotasOperativosSeeder extends Seeder
{
    public function run()
    {
        // 1. Buscamos todas las cuotas (que no sean la cuota 0 histórica)
        // vinculadas a préstamos de empleados con mano_obra_id = 1 (Operativos)
        $cuotas = PrestamoCuota::where('numero_cuota', '>', 0)
            ->whereHas('prestamo.empleado', function($query) {
                // Ajusta este número si tu ID de operativos es diferente a 1
                $query->where('mano_obra_id', 1); 
            })
            ->get();

        $contador = 0;

        foreach ($cuotas as $cuota) {
            if (empty($cuota->fecha_aplicacion)) continue;

            $fechaOriginal = Carbon::parse($cuota->fecha_aplicacion);
            $nuevaFecha = $fechaOriginal->copy();

            // 2. Aplicamos la regla lógica de desplazamiento de quincenas
            if ($fechaOriginal->day == 1) {
                // Si es día 1 (Ej: 2026-11-01) -> Retrocede un mes y se vuelve día 15 (2026-10-15)
                $nuevaFecha->subMonthNoOverflow();
                $nuevaFecha->day = 15;
            } 
            elseif ($fechaOriginal->day == 15 || $fechaOriginal->day == 16) {
                // Si es día 15 o 16 (Ej: 2026-11-15) -> Se vuelve día 1 del mismo mes (2026-11-01)
                $nuevaFecha->day = 1;
            } 
            else {
                // Si tiene un día atípico, simplemente le restamos 15 días matemáticos
                $nuevaFecha->subDays(15);
            }

            // 3. Guardamos la actualización
            $cuota->update([
                'fecha_aplicacion' => $nuevaFecha->format('Y-m-d')
            ]);

            $contador++;
        }

        $this->command->info("¡Éxito! Se retrocedió una quincena a {$contador} cuotas de préstamos para el personal Operativo.");
    }
}