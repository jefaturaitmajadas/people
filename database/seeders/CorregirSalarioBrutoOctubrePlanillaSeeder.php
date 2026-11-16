<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\IOFactory;

/**
 * Corrige salario_base_mes de la planilla 139 (octubre 2026).
 *
 * CargarSalariosOctubrePlanillaSeeder guardó en salario_base_mes el líquido a recibir. El histórico
 * del ISR necesita el salario BRUTO del período, que viene en la columna "Salario Base" de
 * PlanillaOctubre2026.xlsx (hoja Datos: PlanillaMensual, PrimeraQuincena y SegundaQuincena).
 *
 * Cada registro se cruza por código de empleado + IGSS laboral + ISR. Cuando un empleado tiene dos
 * quincenas con el mismo IGSS e ISR, el salario base de ambas es igual, así que se asignan en orden.
 * Antes de actualizar se guarda un respaldo en storage/app/respaldos.
 */
class CorregirSalarioBrutoOctubrePlanillaSeeder extends Seeder
{
    private const PLANILLA_ID = 139;
    private const ARCHIVO = 'app/PlanillaOctubre2026.xlsx';
    private const RESPALDO = 'app/respaldos/planilla_139_antes_salario_bruto.json';

    // Índices de columna en la hoja "Datos" (hay encabezados repetidos, por eso se usan índices)
    private const COL_CODIGO = 0;
    private const COL_SALARIO_BASE = 4;
    private const COL_IGSS = 26;
    private const COL_ISR = 27;

    public function run()
    {
        $archivo = storage_path(self::ARCHIVO);
        if (!file_exists($archivo)) {
            $this->command->error("El archivo no existe en: {$archivo}");
            return;
        }

        $reader = IOFactory::createReaderForFile($archivo);
        $reader->setReadDataOnly(true);
        $filas = $reader->load($archivo)->getSheetByName('Datos')->toArray(null, true, false, false);
        array_shift($filas);
        $filas = array_values(array_filter($filas, fn ($fila) => $fila[self::COL_CODIGO] !== null && $fila[self::COL_CODIGO] !== ''));

        $detalles = DB::table('planilla_detalles')
            ->join('empleados', 'empleados.id', 'planilla_detalles.empleado_id')
            ->where('planilla_detalles.planilla_id', self::PLANILLA_ID)
            ->orderBy('planilla_detalles.id')
            ->get(['planilla_detalles.*', 'empleados.codigo_empleado']);

        if ($detalles->count() !== count($filas)) {
            $this->command->error("La planilla tiene {$detalles->count()} registros y el archivo " . count($filas) . '. No se actualizó nada.');
            return;
        }

        $usadas = [];
        $cambios = [];
        $sinCruce = [];

        foreach ($detalles as $detalle) {
            $indice = null;
            foreach ($filas as $i => $fila) {
                if (isset($usadas[$i])) {
                    continue;
                }
                if ((string) $fila[self::COL_CODIGO] === (string) $detalle->codigo_empleado
                    && abs($fila[self::COL_IGSS] - $detalle->igss_laboral) < 0.02
                    && abs($fila[self::COL_ISR] - $detalle->isr) < 0.02) {
                    $indice = $i;
                    break;
                }
            }

            if ($indice === null) {
                $sinCruce[] = $detalle->codigo_empleado;
                continue;
            }

            $usadas[$indice] = true;
            $cambios[$detalle->id] = round((float) $filas[$indice][self::COL_SALARIO_BASE], 2);
        }

        if ($sinCruce) {
            $this->command->error('No se encontró en el archivo: ' . implode(', ', $sinCruce) . '. No se actualizó nada.');
            return;
        }

        $respaldo = storage_path(self::RESPALDO);
        if (!file_exists($respaldo)) {
            file_put_contents($respaldo, $detalles->toJson(JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        }

        DB::transaction(function () use ($cambios) {
            foreach ($cambios as $id => $salarioBruto) {
                DB::table('planilla_detalles')->where('id', $id)->update([
                    'salario_base_mes' => $salarioBruto,
                    'updated_at' => now(),
                ]);
            }
        });

        $this->command->info('Registros actualizados: ' . count($cambios)
            . ' | Antes (líquido): Q' . number_format($detalles->sum('salario_base_mes'), 2)
            . ' | Ahora (bruto): Q' . number_format(array_sum($cambios), 2)
            . ' | Respaldo: ' . self::RESPALDO);
    }
}
