<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Empleado;
use PhpOffice\PhpSpreadsheet\IOFactory;

class ActualizarManoObraAdministrativaSeeder extends Seeder
{
    public function run()
    {
        // Ubicación del archivo
        $filePath = storage_path('app/ObraAdministrativa.xlsx');

        if (!file_exists($filePath)) {
            $this->command->error("El archivo no existe en: {$filePath}");
            return;
        }

        // Leer Excel
        $spreadsheet = IOFactory::load($filePath);
        $sheet = $spreadsheet->getActiveSheet();
        $rows = $sheet->toArray();

        // Eliminar la fila de los encabezados
        array_shift($rows);

        $contador = 0;

        foreach ($rows as $row) {
            // Mapeo según el Excel: Index 0 es codigo_empleado
            $codigoEmpleado = $row[0] ?? null;

            if (empty($codigoEmpleado)) {
                continue;
            }

            // Buscar el empleado por su código
            $empleado = Empleado::where('codigo_empleado', $codigoEmpleado)->first();

            if ($empleado) {
                // Actualizar el campo mano_obra_id
                $empleado->update([
                    'mano_obra_id' => 2,
                ]);
                
                $contador++;
            }
        }

        $this->command->info("¡Éxito! Se actualizó la mano_obra_id a 2 para {$contador} empleados.");
    }
}