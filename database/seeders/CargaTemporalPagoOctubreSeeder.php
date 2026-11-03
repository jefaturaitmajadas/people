<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CargaTemporal;
use App\Models\CargaTemporalDetalle;
use PhpOffice\PhpSpreadsheet\IOFactory;

class CargaTemporalPagoOctubreSeeder extends Seeder
{
    public function run()
    {
        $filePath = storage_path('app/PagoOctubre2026(Carga).xlsx');

        if (!file_exists($filePath)) {
            $this->command->error("El archivo no existe en: {$filePath}");
            return;
        }

        // 1. Crear el Encabezado de la Carga
        $carga = CargaTemporal::create([
            'descripcion'  => 'Pago Planilla Octubre 2026',
            'fecha_inicio' => '2026-10-01',
            'fecha_fin'    => '2026-10-31',
            'estado'       => 'pendiente'
        ]);

        $spreadsheet = IOFactory::load($filePath);
        $sheet = $spreadsheet->getActiveSheet();
        $rows = $sheet->toArray();

        // Eliminar fila de encabezados
        array_shift($rows);
        $contador = 0;

        foreach ($rows as $row) {
            $codigo = $row[0] ?? null;
            if (empty($codigo)) continue;

            CargaTemporalDetalle::create([
                'carga_temporal_id'      => $carga->id,
                'codigo_empleado'        => $codigo,
                'nombres'                => $row[1] ?? null,
                'puesto'                 => $row[2] ?? null,
                'departamento'           => $row[3] ?? null,
                'dpi'                    => $row[4] ?? null,
                
                'salario_hora'           => (float) ($row[5] ?? 0),
                'cantidad_horas_global'  => (float) ($row[6] ?? 0),
                'salario_base'           => (float) ($row[7] ?? 0),
                'cantidad_horas'         => (float) ($row[8] ?? 0),
                'horas_extras'           => (float) ($row[9] ?? 0),
                'cantidad_horas_dobles'  => (float) ($row[10] ?? 0),
                'horas_dobles'           => (float) ($row[11] ?? 0),
                'comisiones'             => (float) ($row[12] ?? 0),
                'bonificacion'           => (float) ($row[13] ?? 0),
                'bonificacion_incentivo' => (float) ($row[14] ?? 0),
                'retroactivos'           => (float) ($row[15] ?? 0),
                'total_ingresos'         => (float) ($row[16] ?? 0),
                
                'igss_laboral'           => (float) ($row[17] ?? 0),
                'isr'                    => (float) ($row[18] ?? 0),
                'anticipos_salario_1'    => (float) ($row[19] ?? 0), // anticipos_salario
                'anticipos_salario_2'    => (float) ($row[20] ?? 0), // anticipo_salario (la segunda)
                'otros_descuentos'       => (float) ($row[21] ?? 0),
                'total_deducciones'      => (float) ($row[22] ?? 0),
                
                'total_neto'             => (float) ($row[23] ?? 0),
                'forma_pago'             => $row[24] ?? null,
                'tipo_planilla'          => $row[25] ?? null,
            ]);
            $contador++;
        }

        $this->command->info("¡Éxito! Se cargaron {$contador} detalles para el envío de boletas.");
    }
}