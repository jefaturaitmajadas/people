<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Prestamo;
use App\Models\PrestamoCuota;
use PhpOffice\PhpSpreadsheet\IOFactory;

class CargarPagosAnterioresPrestamosSeeder extends Seeder
{
    public function run()
    {
        // Ubicación del archivo
        $filePath = storage_path('app/PrestamosPagado.xlsx');

        if (!file_exists($filePath)) {
            $this->command->error("El archivo no existe en: {$filePath}");
            return;
        }

        // Fechas solicitadas para el historial
        $fechaHistorica = '2026-10-01 00:00:00';
        $fechaAplicacion = '2026-10-01';

        // Leer Excel
        $spreadsheet = IOFactory::load($filePath);
        $sheet = $spreadsheet->getActiveSheet();
        $rows = $sheet->toArray();

        // Eliminar fila de encabezados
        array_shift($rows);

        $contador = 0;

        foreach ($rows as $row) {
            // Mapeo según el Excel: Index 1 es ID_Prestamo, Index 2 es monto_total
            $idPrestamoExcel = $row[1] ?? null;
            $montoPagado     = (float) ($row[2] ?? 0);

            if (empty($idPrestamoExcel) || $montoPagado <= 0) {
                continue;
            }

            // Buscar el préstamo mediante el campo id_prestamo
            $prestamo = Prestamo::where('id_prestamo', $idPrestamoExcel)->first();

            if ($prestamo) {
                PrestamoCuota::create([
                    'prestamo_id'      => $prestamo->id,
                    'numero_cuota'     => 0, // Identificador de saldo acumulado anterior
                    'fecha_aplicacion' => $fechaAplicacion,
                    'monto_capital'    => $montoPagado,
                    'monto_ahorro'     => 0,
                    'monto_total'      => $montoPagado,
                    'pagado'           => true,
                    'fecha_pagado'     => $fechaAplicacion, // Dejamos registro de cuándo se "pagó"
                    'created_at'       => $fechaHistorica,
                    'updated_at'       => $fechaHistorica,
                ]);
                
                $contador++;
            }
        }

        $this->command->info("¡Éxito! Se insertaron {$contador} cuotas unificadas (cuota 0) de pagos anteriores.");
    }
}