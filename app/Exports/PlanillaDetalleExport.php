<?php

namespace App\Exports;

use App\Models\Planilla;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

/**
 * Reporte de totales de transacciones por pago: encabezado de la planilla,
 * un renglón por recibo (mismo orden que la pantalla) y fila de totales.
 *
 * Total Ingresos - Total Deducciones = Líquido a Recibir.
 */
class PlanillaDetalleExport implements FromCollection, WithHeadings, WithMapping, WithColumnFormatting, WithCustomStartCell, WithEvents, ShouldAutoSize, WithStrictNullComparison
{
    private const FORMATO_MONTO = '#,##0.00';

    // Fila donde van los títulos de columna (arriba queda el encabezado del reporte)
    private const FILA_TITULOS = 8;

    private const ULTIMA_COLUMNA = 'O';

    // Columnas con montos: se les da formato y se suman en la fila de totales
    private const COLUMNAS_MONTO = ['D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O'];

    // Firmas al pie: [rango de celdas combinadas, texto bajo la línea]
    private const FIRMAS = [
        ['B', 'C', 'Elaborado por: Kevin Ochoa'],
        ['F', 'H', 'Autorizado: Rolando Ramazzini'],
        ['K', 'M', 'Autorizado: Lic. Rudy Calderón'],
    ];

    // Filas en blanco entre los totales y la línea de firma (espacio para firmar)
    private const ESPACIO_FIRMAS = 4;

    private const NOMBRE_PLANILLA = [
        'quincenal' => '2-Planilla Quincenal Operativa',
        'mensual' => '1-Planilla Mensual Administrativa',
    ];

    private int $secuencia = 0;

    public function __construct(private Planilla $planilla)
    {
    }

    public function collection()
    {
        // Ordenado por código de empleado ascendente (orden natural: 2 antes que 10)
        return $this->planilla->detalles()->with('empleado')->get()
            ->sortBy(fn ($detalle) => (string) ($detalle->empleado->codigo_empleado ?? ''), SORT_NATURAL)
            ->values();
    }

    public function startCell(): string
    {
        return 'A' . self::FILA_TITULOS;
    }

    public function headings(): array
    {
        return [
            'No.',
            'Código Empleado',
            'Nombre',
            'Salario Base Mes',
            'Salario Devengado',
            'Bono Decreto',
            'Total Horas Extras',
            'Total Ingresos Adicionales',
            'Total Ingresos',
            'IGSS Laboral',
            'ISR',
            'Préstamos',
            'Otros Descuentos',
            'Total Deducciones',
            'Líquido a Recibir',
        ];
    }

    public function map($detalle): array
    {
        $empleado = $detalle->empleado;

        $salarioDevengado = (float) $detalle->salario_proporcional;
        $bonoDecreto = (float) $detalle->bono_decreto;
        $horasExtras = (float) $detalle->total_horas_extras;
        $ingresosAdicionales = (float) $detalle->total_ingresos_adicionales;

        $igss = (float) $detalle->igss_laboral;
        $isr = (float) $detalle->isr;
        $prestamos = (float) $detalle->pago_prestamos;
        // total_descuentos ya incluye ISR y préstamos; lo demás son descuentos y seguros
        $otrosDescuentos = round((float) $detalle->total_descuentos - $isr - $prestamos, 2);

        return [
            ++$this->secuencia,
            $empleado->codigo_empleado ?? '',
            $this->nombreCompleto($empleado),
            (float) $detalle->salario_base_mes,
            $salarioDevengado,
            $bonoDecreto,
            $horasExtras,
            $ingresosAdicionales,
            round($salarioDevengado + $bonoDecreto + $horasExtras + $ingresosAdicionales, 2),
            $igss,
            $isr,
            $prestamos,
            $otrosDescuentos,
            round($igss + $isr + $prestamos + $otrosDescuentos, 2),
            (float) $detalle->liquido_a_recibir,
        ];
    }

    public function columnFormats(): array
    {
        return array_fill_keys(self::COLUMNAS_MONTO, self::FORMATO_MONTO);
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $hoja = $event->sheet->getDelegate();
                $ultima = self::ULTIMA_COLUMNA;
                $filaTitulos = self::FILA_TITULOS;

                // Encabezado del reporte (celdas combinadas para no afectar el ancho de las columnas)
                foreach ($this->encabezado() as $i => $texto) {
                    $fila = $i + 1;
                    $hoja->setCellValue("A{$fila}", $texto);
                    $hoja->mergeCells("A{$fila}:{$ultima}{$fila}");
                }
                $hoja->getStyle("A1:{$ultima}6")->getFont()->setBold(true);
                $hoja->getStyle("A1")->getFont()->setSize(13);
                $hoja->getStyle("A1")->getFont()->getColor()->setRGB('C00000');

                // Títulos de columna
                $hoja->getStyle("A{$filaTitulos}:{$ultima}{$filaTitulos}")->applyFromArray([
                    'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '1F2937']],
                    'alignment' => ['wrapText' => true, 'vertical' => 'center', 'horizontal' => 'center'],
                ]);

                // Fila de totales
                $primeraFila = $filaTitulos + 1;
                $ultimaFila = $filaTitulos + $this->secuencia;
                $filaTotales = $ultimaFila + 1;

                $hoja->setCellValue("A{$filaTotales}", 'TOTALES');
                $hoja->mergeCells("A{$filaTotales}:C{$filaTotales}");

                foreach (self::COLUMNAS_MONTO as $columna) {
                    $hoja->setCellValue(
                        "{$columna}{$filaTotales}",
                        $this->secuencia > 0 ? "=SUM({$columna}{$primeraFila}:{$columna}{$ultimaFila})" : 0
                    );
                    $hoja->getStyle("{$columna}{$filaTotales}")->getNumberFormat()->setFormatCode(self::FORMATO_MONTO);
                }

                $hoja->getStyle("A{$filaTotales}:{$ultima}{$filaTotales}")->applyFromArray([
                    'font' => ['bold' => true],
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'E5E7EB']],
                    'borders' => ['top' => ['borderStyle' => Border::BORDER_DOUBLE]],
                ]);

                // Firmas: espacio en blanco, línea y debajo el nombre
                $filaLinea = $filaTotales + self::ESPACIO_FIRMAS + 1;
                $filaNombre = $filaLinea + 1;

                foreach (self::FIRMAS as [$desde, $hasta, $texto]) {
                    $hoja->mergeCells("{$desde}{$filaLinea}:{$hasta}{$filaLinea}");
                    $hoja->getStyle("{$desde}{$filaLinea}:{$hasta}{$filaLinea}")
                        ->getBorders()->getBottom()->setBorderStyle(Border::BORDER_THIN);

                    $hoja->setCellValue("{$desde}{$filaNombre}", $texto);
                    $hoja->mergeCells("{$desde}{$filaNombre}:{$hasta}{$filaNombre}");
                    $hoja->getStyle("{$desde}{$filaNombre}")->applyFromArray([
                        'font' => ['bold' => true],
                        'alignment' => ['horizontal' => 'center'],
                    ]);
                }

                $hoja->freezePane('A' . $primeraFila);
            },
        ];
    }

    private function nombreCompleto($empleado): string
    {
        if (!$empleado) {
            return '';
        }

        $partes = [
            $empleado->primer_nombre,
            $empleado->segundo_nombre,
            $empleado->primer_apellido,
            $empleado->segundo_apellido,
        ];

        return trim(preg_replace('/\s+/', ' ', implode(' ', array_filter($partes))));
    }

    private function encabezado(): array
    {
        $empresa = mb_strtoupper($this->planilla->empresa->nombre ?? '', 'UTF-8');
        $inicio = Carbon::parse($this->planilla->fecha_inicio);
        $fin = Carbon::parse($this->planilla->fecha_fin);

        return [
            "REPORTE DE TOTALES DE TRANSACCIONES POR PAGO - {$empresa} ({$empresa})",
            'Planilla: ' . (self::NOMBRE_PLANILLA[$this->planilla->tipo_periodo] ?? ucfirst($this->planilla->tipo_periodo)),
            'Tipo: Normal',
            'Pago: ' . $inicio->format('dmy'),
            'Descripción: ' . $this->planilla->descripcion,
            'Desde: ' . $inicio->format('j/n/Y') . ' Hasta: ' . $fin->format('j/n/Y'),
        ];
    }
}
