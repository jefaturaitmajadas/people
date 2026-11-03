<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Empleado;
use App\Models\Prestamo;
use App\Models\PrestamoCuota;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Carbon\Carbon;

class CargarPrestamosSeeder extends Seeder
{
    public function run()
    {
        // Ubicación del NUEVO archivo
        $filePath = storage_path('app/DetallePrestamos.xlsx');

        if (!file_exists($filePath)) {
            $this->command->error("El archivo no existe en: {$filePath}");
            return;
        }

        // Fecha general para created_at y updated_at (Hoy a las 08:00:00)
        $fechaAuditoria = Carbon::today()->setTime(8, 0, 0);

        // Leer Excel
        $spreadsheet = IOFactory::load($filePath);
        $sheet = $spreadsheet->getActiveSheet();
        $rows = $sheet->toArray();

        // Eliminar la fila de los encabezados
        array_shift($rows);

        $contadorPrestamos = 0;
        $contadorCuotas = 0;

        foreach ($rows as $row) {
            // Mapeo de columnas (Asumiendo que 'frecuencia' la agregaste al final en la columna 9, índice 8)
            // Si la pusiste en otra posición, solo ajusta el número del índice.
            $codigoEmpleado  = $row[0] ?? null;
            $idPrestamoExcel = $row[1] ?? null;
            $montoSolicitado = $row[2] ?? 0;
            $montoTotalPrest = $row[3] ?? 0;
            $cantidadCuotas  = $row[4] ?? 0;
            $montoCapital    = $row[5] ?? 0;
            $montoAhorro     = $row[6] ?? 0;
            $montoTotalCuota = $row[7] ?? 0;
            $frecuencia      = $row[8] ?? 'Mensual'; // <-- NUEVO CAMPO

            if (empty($codigoEmpleado)) continue;

            // Buscar empleado por su codigo_empleado
            $empleado = Empleado::where('codigo_empleado', $codigoEmpleado)->first();

            if ($empleado) {
                // 1. Crear el Préstamo
                $prestamo = Prestamo::create([
                    'id_prestamo'      => $idPrestamoExcel,
                    'empleado_id'      => $empleado->id,
                    'monto_solicitado' => $montoSolicitado,
                    'monto_total'      => $montoTotalPrest,
                    'frecuencia'       => ucfirst(strtolower(trim($frecuencia))), // Asegura que diga 'Mensual' o 'Quincenal'
                    'cantidad_cuotas'  => $cantidadCuotas,
                    'estado'           => 'Activo',
                    'created_at'       => $fechaAuditoria,
                    'updated_at'       => $fechaAuditoria,
                ]);
                $contadorPrestamos++;

                // 2. Generar el detalle de Cuotas
                // Configuramos la fecha "semilla" justo antes del 1 de noviembre
                $fechaActual = Carbon::create(2026, 10, 31, 0, 0, 0);

                for ($i = 1; $i <= $cantidadCuotas; $i++) {
                    
                    // Lógica dinámica de salto de fechas basada en la frecuencia
                    if (strtolower(trim($frecuencia)) == 'mensual') {
                        $fechaActual->addMonthNoOverflow()->startOfMonth(); // Salta al día 1 del siguiente mes
                    } else {
                        // Quincenal: Alterna entre el 1 y el 16
                        if ($fechaActual->day < 16) {
                            $fechaActual->day = 16;
                        } else {
                            $fechaActual->addMonthNoOverflow()->startOfMonth();
                        }
                    }

                    PrestamoCuota::create([
                        'prestamo_id'      => $prestamo->id,
                        'numero_cuota'     => $i,
                        'fecha_aplicacion' => $fechaActual->format('Y-m-d'),
                        'monto_capital'    => $montoCapital,
                        'monto_ahorro'     => $montoAhorro,
                        'monto_total'      => $montoTotalCuota,
                        'pagado'           => false,
                        'fecha_pagado'     => null,
                        'created_at'       => $fechaAuditoria,
                        'updated_at'       => $fechaAuditoria,
                    ]);
                    $contadorCuotas++;
                }
            }
        }

        $this->command->info("¡Éxito! Se generaron {$contadorPrestamos} préstamos activos y {$contadorCuotas} cuotas proyectadas (Mensuales y Quincenales).");
    }
}