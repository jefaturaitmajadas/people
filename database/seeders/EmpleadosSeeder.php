<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Empleado;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\DB;

class EmpleadosSeeder extends Seeder
{
    public function run()
    {
        // Ubicación del archivo
        $filePath = storage_path('app/ListadoEmpleadosColapersa.xlsx');

        if (!file_exists($filePath)) {
            $this->command->error("El archivo no existe en: {$filePath}");
            return;
        }

        // Leer el Excel utilizando la librería que ya tienes de Maatwebsite/Excel
        $spreadsheet = IOFactory::load($filePath);
        $sheet = $spreadsheet->getActiveSheet();
        $rows = $sheet->toArray();

        // Eliminar la primera fila (los encabezados)
        array_shift($rows);

        // Desactivar validación de llaves foráneas temporalmente por si el ID 1 no existe en alguna tabla
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        $contador = 0;

        foreach ($rows as $row) {
            if (empty($row[0])) continue; // Saltar si no hay código de colaborador

            $codigo = $row[0];
            $nombreCompleto = trim($row[1]);
            
            // Lógica rápida para dividir el nombre completo
            $partes = explode(' ', $nombreCompleto);
            $primer_nombre = $partes[0] ?? '';
            $segundo_nombre = $partes[1] ?? '';
            $primer_apellido = $partes[2] ?? '';
            // Si tiene más de 3 palabras, el resto se va al segundo apellido
            $segundo_apellido = isset($partes[3]) ? implode(' ', array_slice($partes, 3)) : '';

            Empleado::create([
                // Datos del Excel
                'codigo_empleado'  => $codigo,
                'primer_nombre'    => $primer_apellido,
                'segundo_nombre'   => $segundo_apellido,
                'primer_apellido'  => $primer_nombre,
                'segundo_apellido' => $segundo_nombre,
                
                // Datos obligatorios mínimos por defecto
                'identificacion'   => null, 
                'fecha_inicio'     => now()->format('Y-m-d'), 
                'salario'          => 3500.00, 
                'genera_planilla'  => true,
                
                // --- RELACIONES FORÁNEAS FORZADAS AL ID 1 ---
                'empresa_id'               => 6, // 6 para Colapersa (cámbialo a 1 si lo prefieres)
                'identificacion_id'        => 1,
                'tipo_contrato_id'         => 1,
                'turno_id'                 => 1,
                'tipo_salario_id'          => 1,
                'departamento_id'          => 1,
                'genero_id'                => 1,
                'estado_civil_id'          => 1,
                'religion_id'              => 1,
                'pais_nacimiento_id'       => 1,
                'municipio_nacimiento_id'  => 1,
                'nivel_educativo_id'       => 1,
                'pueblo_id'                => 1,
                'comunidad_linguistica_id' => 1,
                'tipo_casa_id'             => 1,
                'tipo_transporte_id'       => 1,
                'pais_residencia_id'       => 1,
                'discapacidad_id'          => 1,
                'ocupacion_id'             => 1,
                'tipo_pago_id'             => 1,
                'moneda_id'                => 1,
                'seguro_id'                => 1,
                'banco_id'                 => 1,
                'tipo_cuenta_id'           => 1,
                'puesto_id'                => 1,
                'igss_tipo_planilla_id'    => 1,
                'centro_costo_id'          => 1,
                'mano_obra_id'             => 1,
                'profesion_id'             => 1,
                'jefe_id'                  => null, // Dejar null para evitar errores circulares
            ]);

            $contador++;
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $this->command->info("¡Se importaron {$contador} empleados correctamente!");
    }
}