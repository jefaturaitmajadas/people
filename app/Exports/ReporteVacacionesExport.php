<?php

namespace App\Exports;

use App\Models\Empleado;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ReporteVacacionesExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    public function collection()
    {
        // Traemos a los empleados activos (sin fecha de salida) de la empresa actual
        // con sus vacaciones pre-cargadas para máxima velocidad
        return Empleado::with('vacaciones')
            ->whereNull('fecha_salida')
            ->orderBy('primer_nombre', 'asc')
            ->get();
    }

    public function headings(): array
    {
        return [
            'Código Empleado',
            'Primer Nombre',
            'Segundo Nombre',
            'Primer Apellido',
            'Segundo Apellido',
            'Días Disponibles',
            'Días Con Goze de Salario',
            'Días Acumulado',
            'Días Utilizados',
            'Días Disponibles (Saldo)'
        ];
    }

    public function map($empleado): array
    {
        // Usamos nuestro cerebro matemático
        $calculo = $empleado->calcularSaldoVacaciones();

        return [
            $empleado->codigo_empleado ?? $empleado->id, // Ajusta si tu campo se llama distinto
            $empleado->primer_nombre,
            $empleado->segundo_nombre,
            $empleado->primer_apellido,
            $empleado->segundo_apellido,
            $calculo['dias_ley'],
            $calculo['dias_goze'],
            $calculo['dias_acumulados'],
            $calculo['dias_tomados'],
            $calculo['saldo_disponible']
        ];
    }
}
