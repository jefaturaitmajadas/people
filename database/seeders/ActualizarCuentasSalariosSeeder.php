<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Empleado;
use PhpOffice\PhpSpreadsheet\IOFactory;

class ActualizarCuentasSalariosSeeder extends Seeder
{
    public function run()
    {
        // Ubicación del archivo (Asegúrate de que esté dentro de storage/app/)
        $filePath = storage_path('app/CuentaBancos.xlsx');

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
            // Index 0: CODIGO, Index 1: empleado_id, Index 2: numero_cuenta, Index 3: salario
            $empleadoId = $row[1] ?? null;
            $numeroCuenta = $row[2] ?? null;
            $salario = $row[3] ?? null;

            if (empty($empleadoId)) {
                continue; // Saltar si la fila no tiene ID de empleado
            }

            // Buscar al empleado y actualizar sus datos
            $empleado = Empleado::find($empleadoId);
            
            if ($empleado) {
                $empleado->update([
                    'numero_cuenta' => $numeroCuenta,
                    'salario' => (float) $salario,
                ]);
                $contador++;
            }
        }

        $this->command->info("¡Se actualizaron {$contador} empleados exitosamente con su número de cuenta y salario!");
    }
}