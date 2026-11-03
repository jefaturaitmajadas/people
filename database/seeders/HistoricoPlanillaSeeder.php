<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PlanillaDetalle;
use App\Models\Planilla;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\DB;

class HistoricoPlanillaSeeder extends Seeder
{
    public function run()
    {
        // 1. Ubicación del archivo
        $filePath = storage_path('app/HistoricoAgostoColapersa.xlsx');

        if (!file_exists($filePath)) {
            $this->command->error("El archivo no existe en: {$filePath}");
            return;
        }

        // 2. Crear la Planilla Padre (Encabezado) en estado "Cerrada"
        // Validamos si ya existe la ID 5, si no la creamos para que cuadre con el Excel.
        Planilla::firstOrCreate(
            ['id' => 5], // El Excel dice que la planilla_id es 5
            [
                'empresa_id' => 6, // 6 = Colapersa
                'codigo' => 'PLA-HISTORICO-AGOSTO',
                'tipo_periodo' => 'mensual',
                'mes' => 8,
                'anio' => 2026,
                'fecha_inicio' => '2026-08-01',
                'fecha_fin' => '2026-08-31',
                'estado' => 'cerrada', // CRÍTICO: Debe estar cerrada para que tu ISR la lea
                'descripcion' => 'Carga de Histórico Agosto 2026',
            ]
        );

        // 3. Leer el Excel
        $spreadsheet = IOFactory::load($filePath);
        $sheet = $spreadsheet->getActiveSheet();
        $rows = $sheet->toArray();

        // Eliminar la primera fila (encabezados)
        array_shift($rows);

        // Desactivamos llaves foráneas para evitar conflictos
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        $contador = 0;

        foreach ($rows as $row) {
            if (empty($row[2])) continue; // Saltar si la columna "empleado_id" está vacía

            PlanillaDetalle::create([
                'planilla_id'                => $row[1] ?? 5,
                'empleado_id'                => $row[2],
                'dias_trabajados'            => $row[3] ?? 30,
                
                // Omitimos $row[4] porque es el nombre del colaborador y no va en esta tabla
                
                'salario_base_mes'           => $row[5] ?? 0, 
                'bono_decreto'               => $row[6] ?? 0,
                'salario_proporcional'       => $row[7] ?? 0,
                'total_horas_extras'         => $row[8] ?? 0,
                'total_ingresos_adicionales' => $row[9] ?? 0,
                'igss_laboral'               => $row[10] ?? 0,
                'isr'                        => $row[11] ?? 0,
                'pago_prestamos'             => $row[12] ?? 0,
                'total_descuentos'           => $row[13] ?? 0,
                'liquido_a_recibir'          => $row[14] ?? 0,
            ]);

            $contador++;
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $this->command->info("¡Se importó 1 planilla histórica con {$contador} detalles de pago correctamente!");
    }
}