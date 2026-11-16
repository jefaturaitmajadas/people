<?php

namespace Database\Seeders;

use App\Models\Empleado;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\IOFactory;

/**
 * Carga octubre 2026 en planilla_detalles (planilla 139) para que el cálculo del ISR lo tome
 * como histórico.
 *
 * - SalarioOctubre2026.xlsx (Hoja1) trae la planilla mensual y la segunda quincena.
 * - SalarioOctubre2026V2.xlsx (Hoja1) trae la primera quincena: los quincenales quedan con dos líneas.
 * - salario_base_mes lleva el líquido a recibir, igual que los archivos. OJO: el histórico del ISR
 *   necesita el bruto; después de este seeder correr CorregirSalarioBrutoOctubrePlanillaSeeder.
 * - Días trabajados: 30 para la planilla mensual (mano de obra Administrativa) y 15 por quincena.
 * - La planilla queda cerrada del 01/10 al 31/10: el histórico solo cuenta planillas cerradas.
 *   No se ejecuta el cierre de la aplicación, que marcaría préstamos y horas extras como pagados.
 */
class CargarSalariosOctubrePlanillaSeeder extends Seeder
{
    private const PLANILLA_ID = 139;
    private const MANO_OBRA_ADMINISTRATIVA = 2;

    private const ARCHIVOS = [
        'app/SalarioOctubre2026V2.xlsx', // Primera quincena
        'app/SalarioOctubre2026.xlsx',   // Mensual y segunda quincena
    ];

    private const COLUMNAS = [
        'salario_base_mes', 'bono_decreto', 'total_horas_extras', 'total_ingresos_adicionales',
        'igss_laboral', 'isr', 'liquido_a_recibir',
    ];

    public function run()
    {
        foreach (self::ARCHIVOS as $archivo) {
            if (!file_exists(storage_path($archivo))) {
                $this->command->error('El archivo no existe en: ' . storage_path($archivo));
                return;
            }
        }

        if (!DB::table('planillas')->where('id', self::PLANILLA_ID)->exists()) {
            $this->command->error('No existe la planilla ' . self::PLANILLA_ID . '.');
            return;
        }

        if (DB::table('planilla_detalles')->where('planilla_id', self::PLANILLA_ID)->exists()) {
            $this->command->warn('La planilla ' . self::PLANILLA_ID . ' ya tiene detalles, no se cargó nada.');
            return;
        }

        $filas = [];
        foreach (self::ARCHIVOS as $archivo) {
            array_push($filas, ...$this->leerHoja(storage_path($archivo), 'Hoja1'));
        }

        $empleados = Empleado::withoutGlobalScopes()->get(['id', 'codigo_empleado', 'mano_obra_id'])->keyBy('codigo_empleado');
        $faltantes = array_unique(array_filter(array_map(fn ($fila) => (string) $fila['codigo_empleado'], $filas), fn ($codigo) => !isset($empleados[$codigo])));

        if ($faltantes) {
            $this->command->error('No existen los empleados con código: ' . implode(', ', $faltantes) . '. No se cargó nada.');
            return;
        }

        $ahora = now();
        $registros = array_map(function ($fila) use ($empleados, $ahora) {
            $empleado = $empleados[(string) $fila['codigo_empleado']];

            $registro = [
                'planilla_id' => self::PLANILLA_ID,
                'empleado_id' => $empleado->id,
                'dias_trabajados' => $empleado->mano_obra_id == self::MANO_OBRA_ADMINISTRATIVA ? 30 : 15,
                'salario_proporcional' => 0,
                'pago_prestamos' => 0,
                'total_descuentos' => 0,
                'created_at' => $ahora,
                'updated_at' => $ahora,
            ];

            foreach (self::COLUMNAS as $columna) {
                $registro[$columna] = round((float) $fila[$columna], 2);
            }

            return $registro;
        }, $filas);

        DB::transaction(function () use ($registros) {
            foreach (array_chunk($registros, 100) as $bloque) {
                DB::table('planilla_detalles')->insert($bloque);
            }

            DB::table('planillas')->where('id', self::PLANILLA_ID)->update([
                'estado' => 'cerrada',
                'fecha_inicio' => '2026-10-01',
                'fecha_fin' => '2026-10-31',
                'total_nomina' => round(array_sum(array_column($registros, 'liquido_a_recibir')), 2),
                'updated_at' => now(),
            ]);
        });

        $this->command->info('Líneas cargadas: ' . count($registros) . ' | Colaboradores: ' . count(array_unique(array_column($registros, 'empleado_id'))) . ' | Planilla ' . self::PLANILLA_ID . ' cerrada del 01/10 al 31/10.');
    }

    private function leerHoja(string $archivo, string $hoja): array
    {
        $reader = IOFactory::createReaderForFile($archivo);
        $reader->setReadDataOnly(true);
        $libro = $reader->load($archivo);
        $filas = $libro->getSheetByName($hoja)->toArray(null, true, false, false);
        $encabezados = array_shift($filas);

        $resultado = [];

        foreach ($filas as $fila) {
            if ($fila[0] === null || $fila[0] === '') {
                continue;
            }

            $resultado[] = array_combine($encabezados, $fila);
        }

        return $resultado;
    }
}
