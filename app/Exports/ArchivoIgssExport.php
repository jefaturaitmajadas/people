<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Carbon\Carbon;

class ArchivoIgssExport implements FromCollection, WithHeadings, WithMapping, WithColumnFormatting
{
    protected $planilla;

    public function __construct($planilla)
    {
        $this->planilla = $planilla;
    }

    public function collection()
    {
        // Traemos todos los detalles de la planilla junto con la info del empleado
        return $this->planilla->detalles()->with('empleado')->get();
    }

   

        public function headings(): array
    {
        // 1. Obtenemos la empresa desde la planilla
        $empresa = $this->planilla->empresa;

       // dd($this->planilla->empresa);

        // 2. Formateamos las variables necesarias
        $fechaGeneracion = now()->format('d/m/Y');
        $numeroPatronal  = $empresa->numero_patronal ?? '';
        $mes             = Carbon::parse($this->planilla->fecha_inicio)->format('m'); // Asegura 2 dígitos (ej: 08)
        $anio            = Carbon::parse($this->planilla->fecha_inicio)->format('Y');;
        $nombreEmpresa   = $empresa->nombre ?? '';
        $nitEmpresa      = str_replace('-', '', $empresa->nit ?? ''); // Sin guiones
        $correo          = $empresa->correo ?? '';
        
        $razonSocial = mb_strtoupper($empresa->razon_social ?? $empresa->nombre, 'UTF-8');
        $direccion = mb_strtoupper($empresa->direccion ?? '', 'UTF-8');
        $telefono = $empresa->telefono ?? '';
        
        $fechaInicio = \Carbon\Carbon::parse($this->planilla->fecha_inicio)->format('d/m/Y');
        $fechaFin = \Carbon\Carbon::parse($this->planilla->fecha_fin)->format('d/m/Y');

        // 3. Armamos las líneas de texto del IGSS
        $lineaEncabezado = "2.2.0|{$fechaGeneracion}|{$numeroPatronal}|{$mes}|{$anio}|{$nombreEmpresa}|{$nitEmpresa}||{$correo}|0";
        $lineaCentros = "1|{$razonSocial}|{$direccion}|11|{$telefono}||||1|1|701000|";
        $lineaTiposPlanilla = "1|PLANILLA MENSUAL " . mb_strtoupper($nombreEmpresa, 'UTF-8') . "|C|M|1|701000|N|TC|";
        $lineaLiquidaciones = "1|1|{$fechaInicio}|{$fechaFin}|O||";

        // 4. Retornamos un arreglo de arreglos (cada sub-arreglo es una fila en Excel)
        return [
            [$lineaEncabezado],
            ['[centros]'],
            [$lineaCentros],
            ['[tiposplanilla]'],
            [$lineaTiposPlanilla],
            ['[liquidaciones]'],
            [$lineaLiquidaciones],
            ['[empleados]'],
            
            // Títulos de las columnas (Esta será la fila 9 en el Excel)
            [
                '1. Número o identificación de la liquidación.',
                '2. Numero de Afiliacion',
                '3. Primer Nombre de afiliado',
                '4. Segundo Nombre de afiliado',
                '5. Primer Apellido de Afiliado',
                '6. Segundo Apellido de Afiliado',
                '7. Apellido de Casada',
                '8. Sueldo devengado en el periodo',
                '9. Fecha de alta',
                '10. Fecha de baja',
                '11. Código de Centro de trabajo asignado',
                '12. Nit ',
                '13. Código Ocupación',
                '14. Condición Laboral (P = Permanente   T = Temporal)',
                '15. Deducciones',
                '16. Tipo salario',
                '17. Horas laboradas',
                '18. Tiempo de contrato, (TP) tiempo parcial, (TC) Tiempo completo',
                '19. Dias laborados',
                'ARCHIVO DE TEXTO'
            ]
        ];
    }

    public function map($detalle): array
    {
        $empleado = $detalle->empleado;

        // Validamos el campo de afiliación (Ajusta 'no_afiliacion' si en tu BD se llama diferente, ej: 'igss', 'numero_igss')
        $numeroAfiliacion = $empleado->no_igss ?? ''; 
        $primerNombre = $empleado->primer_nombre ?? '';
        $segundoNombre = $empleado->segundo_nombre ?? ''; 
        $primerApellido = $empleado->primer_apellido ?? ''; 
        $segundoApellido = $empleado->segundo_apellido ?? ''; 
        $casadaApellido = $empleado->casada_apellido ?? ''; 
        $sueldo = $detalle->liquido_a_recibir ?? ''; 
        $fechaAlta = $empleado->fecha_inicio ?? ''; 
        $fechaBaja = $empleado->fecha_salida ?? ''; 
        $nit = $empleado->nit ?? ''; 
        $ocupacion = $empleado->ocupacion->igssOcupacion->grupo_primario ?? ''; 
        $salarioTipo = $empleado->tipoSalario->igssTipoSalario->codigo ?? ''; 
        $contratoTipo = $empleado->tipoContrato->emplTemporalidadContrato->codigo ?? '';
        
        $dias = $detalle->dias_trabajados ?? ''; 

        return [
            1, // Columna 1: Siempre es 1
            $numeroAfiliacion, // Columna 2: Número de afiliación del empleado
            $primerNombre,
            $segundoNombre,
            $primerApellido,
            $segundoApellido,
            $casadaApellido,
            $sueldo,
            $fechaAlta,
            $fechaBaja,
            1,
            $nit,
            $ocupacion,
            'P',
            '',
            $salarioTipo,
            '',
            'TC',
            $dias,
            
            '1|'. 
            $numeroAfiliacion.'|'. 
            $primerNombre.'|'.
            $segundoNombre.'|'.
            $primerApellido.'|'.
            $segundoApellido.'|'.
            $casadaApellido.'|'.
            $sueldo.'|'.
            $fechaAlta.'|'.
            $fechaBaja.'|'.
            '1|'.
            $nit.'|'.
            $ocupacion.'|'.
            'P'.'|'.
            ''.'|'.
            $salarioTipo.'|'.
            ''.'|'.
            'TC'.'|'.
            $dias.'|'



        ];
    }

    public function columnFormats(): array
    {
        return [
            // Forzamos que el número de afiliación sea texto para no perder los ceros a la izquierda
            'B' => NumberFormat::FORMAT_TEXT, 
        ];
    }
}