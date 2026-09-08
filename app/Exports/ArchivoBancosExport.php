<?php


namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class ArchivoBancosExport implements FromCollection, WithHeadings, WithMapping, WithColumnFormatting
{
    protected $planilla;
    protected $cuentaEmpresa;

    public function __construct($planilla, $cuentaEmpresa)
    {
        $this->planilla = $planilla;
        $this->cuentaEmpresa = $cuentaEmpresa;
    }

    public function collection()
    {
        // Devolvemos los detalles de la planilla
        return $this->planilla->detalles()->with('empleado')->get();
    }

    public function headings(): array
    {
        return [
            'Cuenta debito',
            'Cuenta credito',
            'Fecha',
            'Monto',
            'Referencia',
            'Beneficiario',
        ];
    }

    public function map($detalle): array
    {
        $empleado = $detalle->empleado;
        
        $nombreCompleto = trim(
            ($empleado->primer_nombre ?? '') . ' ' . 
            ($empleado->segundo_nombre ?? '') . ' ' . 
            ($empleado->primer_apellido ?? '') . ' ' . 
            ($empleado->segundo_apellido ?? '')
        );
        $nombreCompleto = preg_replace('/\s+/', ' ', $nombreCompleto);
        $beneficiario = mb_strtoupper($nombreCompleto, 'UTF-8');

        $cuentaCredito = $empleado->numero_cuenta ?? 'FALTA_CUENTA';

        return [
            $this->cuentaEmpresa->numero_cuenta,
            $cuentaCredito,
            date('d/m/Y'),
            $detalle->liquido_a_recibir,
            $cuentaCredito,
            $beneficiario,
        ];
    }

    public function columnFormats(): array
    {
        return [
            // La columna A, B y E (cuentas) se formatean como TEXTO para no perder ceros
            'A' => NumberFormat::FORMAT_TEXT,
            'B' => NumberFormat::FORMAT_TEXT,
            'E' => NumberFormat::FORMAT_TEXT,
            // La columna D (monto) como número con 2 decimales
            'D' => NumberFormat::FORMAT_NUMBER_00, 
        ];
    }
}
