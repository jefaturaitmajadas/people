<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Empleado;
use PhpOffice\PhpSpreadsheet\IOFactory;

class ActualizarSalariosSeeder extends Seeder
{
    public function run()
    {
        // Ubicación del archivo
        $filePath = storage_path('app/ActualizarSalarios.xlsx');

        if (!file_exists($filePath)) {
            $this->command->error("El archivo no existe en: {$filePath}");
            return;
        }

        // Leer Excel utilizando PhpSpreadsheet
        $spreadsheet = IOFactory::load($filePath);
        $sheet = $spreadsheet->getActiveSheet();
        $rows = $sheet->toArray();

        // Eliminar la primera fila correspondiente a los encabezados
        array_shift($rows);

        $contador = 0;

        foreach ($rows as $row) {
            // Mapeo según el Excel: Index 0 es CODIGO, Index 2 es SUELDO BASE
            $codigoEmpleado = $row[0] ?? null;
            $nuevoSalario   = $row[2] ?? null;

            if (empty($codigoEmpleado) || !is_numeric($nuevoSalario)) {
                continue; // Saltar si no hay código o el salario no es un número válido
            }

            // Buscar al empleado por su codigo_empleado
            $empleado = Empleado::where('codigo_empleado', $codigoEmpleado)->first();
            
            if ($empleado) {
                // Actualizar el salario
                $empleado->update([
                    'salario' => (float) $nuevoSalario,
                ]);
                
                $contador++;
            }
        }

        $this->command->info("¡Se actualizaron los salarios de {$contador} empleados exitosamente!");
    }
}