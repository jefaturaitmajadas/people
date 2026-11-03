<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Empleado;
use App\Models\Descuento;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Carbon\Carbon;

class CargarSeguroCarroSeeder extends Seeder
{
    public function run()
    {
        // Ubicación del archivo
        $filePath = storage_path('app/SeguroCarro.xlsx');

        if (!file_exists($filePath)) {
            $this->command->error("El archivo no existe en: {$filePath}");
            return;
        }

        // Leer Excel
        $spreadsheet = IOFactory::load($filePath);
        $sheet = $spreadsheet->getActiveSheet();
        $rows = $sheet->toArray();

        // Eliminar fila de encabezados
        array_shift($rows);

        $contador = 0;
        
        // Fechas solicitadas
        $fechaInicio = '2026-11-01';
        $fechaAuditoria = Carbon::parse('2026-10-16 00:00:00');

        // Congelamos el tiempo de Laravel para que los created_at y updated_at queden exactos
        Carbon::setTestNow($fechaAuditoria);

        foreach ($rows as $row) {
            // Mapeo según el Excel: Index 0 es codigo_empleado, Index 1 es monto
            $codigoEmpleado = $row[0] ?? null;
            $montoCuota     = (float) ($row[1] ?? 0);

            if (empty($codigoEmpleado) || $montoCuota <= 0) {
                continue;
            }

            // Buscar el empleado por su código
            $empleado = Empleado::where('codigo_empleado', $codigoEmpleado)->first();

            if ($empleado) {
                Descuento::create([
                    'empleado_id'       => $empleado->id,
                    'tipo_descuento_id' => 4,
                    'es_recurrente'     => 1,
                    'descripcion'       => 'Seguro Auto',
                    'cuota'             => $montoCuota,
                    'fecha_inicio'      => $fechaInicio,
                    'estado'            => 1,
                ]);
                
                $contador++;
            }
        }

        // Restaurar el tiempo normal de Laravel
        Carbon::setTestNow();

        $this->command->info("¡Éxito! Se insertaron {$contador} descuentos recurrentes de Seguro Auto.");
    }
}