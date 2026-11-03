<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Empleado;
use PhpOffice\PhpSpreadsheet\IOFactory;

class ActualizarCorreosSeeder extends Seeder
{
    public function run()
    {
        // Ubicación del archivo (Asegúrate de que esté dentro de storage/app/)
        $filePath = storage_path('app/ActualizarCorreos.xlsx');

        if (!file_exists($filePath)) {
            $this->command->error("El archivo no existe en: {$filePath}");
            return;
        }

        // Leer el Excel utilizando PhpSpreadsheet
        $spreadsheet = IOFactory::load($filePath);
        $sheet = $spreadsheet->getActiveSheet();
        $rows = $sheet->toArray();

        // Eliminar la primera fila correspondiente a los encabezados
        array_shift($rows);

        $contador = 0;

        foreach ($rows as $row) {
            // Mapeo de columnas según el Excel: 
            // Index 0: Código, Index 1: Correo
            $codigoEmpleado = $row[0] ?? null;
            $correoPersonal = $row[1] ?? null;

            if (empty($codigoEmpleado) || empty($correoPersonal)) {
                continue; // Saltar si la fila no tiene código o correo
            }

            // Buscar al empleado por su código_empleado
            $empleado = Empleado::where('codigo_empleado', $codigoEmpleado)->first();
            
            if ($empleado) {
                $empleado->update([
                    'correo_personal' => $correoPersonal,
                ]);
                $contador++;
            }
        }

        $this->command->info("¡Se actualizaron {$contador} correos de empleados exitosamente!");
    }
}