<?php

namespace Database\Seeders;

use App\Models\Empleado;
use App\Models\Prestamo;
use App\Models\PrestamoCuota;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\IOFactory;

/**
 * Aplica los descuentos de octubre 2026 de storage/app/PagoCuotaOctubre2026.xlsx.
 *
 * - Cada línea del archivo paga la siguiente cuota pendiente del préstamo del colaborador.
 * - El archivo trae lo descontado completo, igual que monto_total del préstamo y la cuota 0:
 *   la cuota pagada queda con capital y total iguales a lo descontado y ahorro 0.
 * - Saldo = monto_total del préstamo menos todo lo pagado. Si el préstamo no tiene monto_total
 *   se parte de la suma del capital pendiente.
 * - Si el saldo no coincide con el capital pendiente, se reparte en partes iguales entre las
 *   cuotas que quedan, con las mismas fechas y el mismo porcentaje de ahorro.
 * - Se puede ejecutar más de una vez: un préstamo con pagos de octubre ya aplicados se omite.
 */
class AplicarPagosOctubrePrestamosSeeder extends Seeder
{
    private const ARCHIVO = 'app/PagoCuotaOctubre2026.xlsx';
    private const RESPALDO = 'respaldos/prestamos_antes_pagos_octubre_2026.json';
    private const PRIMERA_QUINCENA = '2026-10-15';
    private const FIN_DE_MES = '2026-10-31';

    private array $avisos = [];

    public function run()
    {
        $filePath = storage_path(self::ARCHIVO);

        if (!file_exists($filePath)) {
            $this->command->error("El archivo no existe en: {$filePath}");
            return;
        }

        $pagos = $this->leerPagos($filePath);
        $this->respaldar();

        $aplicados = 0;
        $reorganizados = 0;
        $liquidados = 0;

        DB::transaction(function () use ($pagos, &$aplicados, &$reorganizados, &$liquidados) {
            foreach ($pagos as $codigo => $montos) {
                $prestamo = $this->buscarPrestamo((string) $codigo, $montos);

                if (!$prestamo) {
                    continue;
                }

                $pendientes = $prestamo->cuotas()->where('pagado', false)
                    ->orderBy('fecha_aplicacion')->orderBy('numero_cuota')->get();

                $saldo = round($this->saldoActual($prestamo, $pendientes) - array_sum($montos), 2);

                if ($pendientes->count() < count($montos) || $saldo < 0) {
                    $this->avisos[] = "Código {$codigo}: los pagos superan las cuotas o el saldo del préstamo, no se aplicó.";
                    continue;
                }

                $ahorro = $pendientes->sum('monto_capital') > 0
                    ? round($pendientes->sum('monto_ahorro') / $pendientes->sum('monto_capital'), 2)
                    : 0;

                $this->marcarPagadas($pendientes->take(count($montos)), $montos);
                $restantes = $pendientes->slice(count($montos))->values();

                if ($restantes->isEmpty()) {
                    $prestamo->update(['estado' => 'Pagado']);
                    $liquidados++;

                    if ($saldo != 0) {
                        $this->avisos[] = "Código {$codigo}: quedó liquidado con una diferencia de Q{$saldo} sin cuota donde cargarla.";
                    }
                } elseif (abs($restantes->sum('monto_capital') - $saldo) >= 0.005) {
                    $this->repartirSaldo($restantes, $saldo, $ahorro);
                    $reorganizados++;
                }

                $aplicados++;
            }
        });

        foreach ($this->avisos as $aviso) {
            $this->command->warn($aviso);
        }

        $this->command->info("Préstamos aplicados: {$aplicados} | Reorganizados: {$reorganizados} | Liquidados: {$liquidados} | Avisos: " . count($this->avisos));
    }

    private function leerPagos(string $filePath): array
    {
        $reader = IOFactory::createReaderForFile($filePath);
        $reader->setReadDataOnly(true);
        $filas = $reader->load($filePath)->getSheet(0)->toArray(null, true, false, false);
        array_shift($filas);

        $pagos = [];

        foreach ($filas as $fila) {
            if ($fila[0] !== null && $fila[0] !== '') {
                $pagos[(string) $fila[0]][] = round((float) $fila[1], 2);
            }
        }

        return $pagos;
    }

    // El respaldo se hace una sola vez para que una segunda corrida no lo pise
    private function respaldar(): void
    {
        $disco = Storage::build(['driver' => 'local', 'root' => storage_path('app')]);

        if (!$disco->exists(self::RESPALDO)) {
            $disco->put(self::RESPALDO, json_encode([
                'prestamos' => DB::table('prestamos')->get(),
                'prestamo_cuotas' => DB::table('prestamo_cuotas')->get(),
            ]));
        }
    }

    private function buscarPrestamo(string $codigo, array $montos): ?Prestamo
    {
        $total = number_format(array_sum($montos), 2, '.', '');
        $empleado = Empleado::where('codigo_empleado', $codigo)->first();

        if (!$empleado) {
            $this->avisos[] = "Código {$codigo}: no existe el empleado, Q{$total} sin aplicar.";
            return null;
        }

        $yaAplicado = PrestamoCuota::whereHas('prestamo', fn ($query) => $query->where('empleado_id', $empleado->id))
            ->where('pagado', true)
            ->where('numero_cuota', '>', 0)
            ->whereBetween('fecha_pagado', [self::PRIMERA_QUINCENA, self::FIN_DE_MES])
            ->exists();

        if ($yaAplicado) {
            return null;
        }

        $prestamos = Prestamo::where('empleado_id', $empleado->id)->where('estado', 'Activo')->get();

        if ($prestamos->count() !== 1) {
            $this->avisos[] = "Código {$codigo} ({$empleado->primer_nombre} {$empleado->primer_apellido}): tiene {$prestamos->count()} préstamos activos, Q{$total} sin aplicar.";
            return null;
        }

        return $prestamos->first();
    }

    private function saldoActual(Prestamo $prestamo, Collection $pendientes): float
    {
        if ($prestamo->monto_total <= 0) {
            $this->avisos[] = "Préstamo {$prestamo->id_prestamo}: no tiene monto total, el saldo sale del capital pendiente.";
            return $pendientes->sum('monto_capital');
        }

        return $prestamo->monto_total - $prestamo->cuotas()->where('pagado', true)->sum('monto_total');
    }

    private function marcarPagadas(Collection $cuotas, array $montos): void
    {
        $fechas = count($montos) > 1 ? [self::PRIMERA_QUINCENA, self::FIN_DE_MES] : [self::FIN_DE_MES];

        foreach ($cuotas->values() as $indice => $cuota) {
            $cuota->update([
                'monto_capital' => $montos[$indice],
                'monto_ahorro' => 0,
                'monto_total' => $montos[$indice],
                'pagado' => true,
                'fecha_pagado' => $fechas[$indice],
            ]);
        }
    }

    // La última cuota absorbe los centavos para que el capital sume exactamente el saldo
    private function repartirSaldo(Collection $cuotas, float $saldo, float $ahorro): void
    {
        $capital = round($saldo / $cuotas->count(), 2);

        foreach ($cuotas as $indice => $cuota) {
            $monto = $indice === $cuotas->count() - 1
                ? round($saldo - $capital * ($cuotas->count() - 1), 2)
                : $capital;

            $montoAhorro = round($monto * $ahorro, 2);

            $cuota->update([
                'monto_capital' => $monto,
                'monto_ahorro' => $montoAhorro,
                'monto_total' => round($monto + $montoAhorro, 2),
            ]);
        }
    }
}
