<?php

namespace App\Exports;

use App\Models\Empleado;
use Carbon\Carbon;
use DateTimeInterface;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Cell\Cell;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Cell\DefaultValueBinder;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

/**
 * Sábana de empleados de la empresa activa. La misma definición de columnas
 * alimenta la pantalla y el Excel, para que ambos muestren lo mismo.
 */
class ReporteEmpleadosExport extends DefaultValueBinder implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithCustomValueBinder, WithStyles
{
    public const ESTADOS = ['todos' => 'Todos', 'activos' => 'Activos', 'baja' => 'De Baja'];

    // Encabezado => atributo; "relacion.campo" muestra el nombre del catálogo
    private const COLUMNAS = [
        'Nombre Completo' => 'nombre_completo',
        'Código' => 'codigo_empleado',
        'Estado' => 'estado',
        'Primer Nombre' => 'primer_nombre',
        'Segundo Nombre' => 'segundo_nombre',
        'Primer Apellido' => 'primer_apellido',
        'Segundo Apellido' => 'segundo_apellido',
        'Apellido de Casada' => 'casada_apellido',
        'Tipo de Identificación' => 'tipoIdentificacion.documento_identificacion',
        'Identificación' => 'identificacion',
        'NIT' => 'nit',
        'No. IGSS' => 'no_igss',
        'IRTRA' => 'irtra',
        'Teléfono' => 'telefono',
        'Teléfono de Casa' => 'telefono_casa',
        'Correo Corporativo' => 'correo_corporativo',
        'Correo Personal' => 'correo_personal',
        'Fecha de Inicio' => 'fecha_inicio',
        'Fecha de Baja' => 'fecha_salida',
        'Puesto' => 'puesto.nombre',
        'Departamento' => 'departamento.nombre',
        'Jefe' => 'jefe.nombre_completo',
        'Profesión' => 'profesion.nombre',
        'Ocupación' => 'ocupacion.nombre',
        'Tipo de Contrato' => 'tipoContrato.nombre',
        'Turno' => 'turno.nombre',
        'Contratable' => 'contratable',
        'Póliza' => 'poliza',
        'Genera Planilla' => 'genera_planilla',
        'Marca Reloj' => 'marca_reloj',
        'Mano de Obra' => 'manoObra.nombre',
        'Centro de Costo' => 'centroCosto.nombre',
        'Tipo de Planilla IGSS' => 'igssTipoPlanilla.nombre',
        'Género' => 'genero.nombre',
        'Estado Civil' => 'estadoCivil.nombre',
        'Religión' => 'religion.nombre',
        'Fecha de Nacimiento' => 'fecha_nacimiento',
        'País de Nacimiento' => 'paisNacimiento.nombre',
        'Municipio de Nacimiento' => 'municipioNacimiento.nombre',
        'Nivel Educativo' => 'nivelEducativo.nombre',
        'Pueblo' => 'pueblo.nombre',
        'Comunidad Lingüística' => 'comunidadLinguistica.nombre',
        'Discapacidad' => 'discapacidad.nombre',
        'Aplica Crédito Fiscal ISR' => 'aplica_credito_fiscal_isr',
        'Vive Con' => 'vive_con',
        'Tipo de Casa' => 'tipoCasa.nombre',
        'Vehículo' => 'vehiculo',
        'Tipo de Transporte' => 'tipoTransporte.nombre',
        'Cantidad de Hijos' => 'cantidad_hijos',
        'Cantidad de Dependientes' => 'cantidad_dependientes',
        'Contacto de Emergencia' => 'contacto_emergencia',
        'Teléfono de Emergencia' => 'telefono_contacto_emergencia',
        'País de Residencia' => 'paisResidencia.nombre',
        'Dirección' => 'direccion_exacta',
        'Nombre del Padre' => 'nombre_padre',
        'Teléfono del Padre' => 'telefono_padre',
        'Nombre de la Madre' => 'nombre_madre',
        'Teléfono de la Madre' => 'telefono_madre',
        'Nombre del Cónyuge' => 'nombre_conyuge',
        'Teléfono del Cónyuge' => 'telefono_conyuge',
        'Salario Base' => 'salario',
        'Salario por Hora' => 'salario_por_hora',
        'Salario Fijo' => 'salario_fijo',
        'Tipo de Salario' => 'tipoSalario.nombre',
        'Tipo de Pago' => 'tipoPago.nombre',
        'Moneda' => 'moneda.nombre',
        'Seguro' => 'seguro.nombre',
        'Días Adicionales de Vacaciones' => 'dias_adicionales_vacaciones',
        'Pertenece a Asociación' => 'pertenece_asociacion',
        'Pensión Alimenticia' => 'pension_alimentaria',
        'Monto de Pensión' => 'monto_pension',
        'Embargo' => 'embargo',
        'Monto de Embargo' => 'monto_embargo',
        'Banco' => 'banco.nombre',
        'Número de Cuenta' => 'numero_cuenta',
        'Tipo de Cuenta' => 'tipoCuenta.nombre',
    ];

    private const FECHAS = ['fecha_inicio', 'fecha_salida', 'fecha_nacimiento'];

    private const MONTOS = ['salario', 'salario_por_hora', 'dias_adicionales_vacaciones', 'monto_pension', 'monto_embargo'];

    private const BOOLEANOS = [
        'contratable', 'genera_planilla', 'marca_reloj', 'aplica_credito_fiscal_isr', 'vehiculo',
        'salario_fijo', 'pertenece_asociacion', 'pension_alimentaria', 'embargo',
    ];

    private const CAMPOS_BUSQUEDA = [
        'primer_nombre', 'segundo_nombre', 'primer_apellido', 'segundo_apellido', 'casada_apellido',
        'codigo_empleado', 'identificacion',
    ];

    public function __construct(private string $estado = 'todos', private string $buscar = '')
    {
    }

    public function collection()
    {
        $relaciones = collect(self::COLUMNAS)
            ->filter(fn ($columna) => str_contains($columna, '.'))
            ->map(fn ($columna) => strtok($columna, '.'))
            ->unique()
            ->values()
            ->all();

        return Empleado::with($relaciones)
            ->when($this->estado === 'activos', fn ($query) => $query->whereNull('fecha_salida'))
            ->when($this->estado === 'baja', fn ($query) => $query->whereNotNull('fecha_salida'))
            ->tap(fn ($query) => $this->aplicarBusqueda($query))
            ->orderBy('primer_nombre')
            ->orderBy('primer_apellido')
            ->get();
    }

    // Cada palabra debe aparecer en algún campo, así "ana lopez" encuentra nombre y apellido
    private function aplicarBusqueda($query): void
    {
        foreach (preg_split('/\s+/', $this->buscar, -1, PREG_SPLIT_NO_EMPTY) as $palabra) {
            $patron = '%' . addcslashes($palabra, '\\%_') . '%';

            $query->where(function ($grupo) use ($patron) {
                foreach (self::CAMPOS_BUSQUEDA as $campo) {
                    $grupo->orWhere($campo, 'like', $patron);
                }
            });
        }
    }

    public function headings(): array
    {
        return array_keys(self::COLUMNAS);
    }

    public function map($empleado): array
    {
        return array_map(fn ($columna) => $this->valor($empleado, $columna), array_values(self::COLUMNAS));
    }

    private function valor(Empleado $empleado, string $columna)
    {
        if ($columna === 'estado') {
            return $empleado->fecha_salida ? 'De Baja' : 'Activo';
        }

        $valor = data_get($empleado, $columna);

        if (in_array($columna, self::BOOLEANOS)) {
            return $valor ? 'Sí' : 'No';
        }

        if ($valor === null) {
            return null;
        }

        if (in_array($columna, self::FECHAS)) {
            return Carbon::parse($valor);
        }

        return in_array($columna, self::MONTOS) ? (float) $valor : $valor;
    }

    // Identificaciones, cuentas y teléfonos van como texto: como número Excel
    // les quita los ceros iniciales y deforma los de muchos dígitos
    public function bindValue(Cell $cell, $value)
    {
        if ($value instanceof DateTimeInterface) {
            $cell->setValueExplicit(Date::PHPToExcel($value), DataType::TYPE_NUMERIC);
            $cell->getStyle()->getNumberFormat()->setFormatCode('dd/mm/yyyy');

            return true;
        }

        if (is_float($value)) {
            $cell->setValueExplicit($value, DataType::TYPE_NUMERIC);
            $cell->getStyle()->getNumberFormat()->setFormatCode('#,##0.00');

            return true;
        }

        if (is_string($value)) {
            $cell->setValueExplicit($value, DataType::TYPE_STRING);

            return true;
        }

        return parent::bindValue($cell, $value);
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->freezePane('B2');
        $sheet->setAutoFilter($sheet->calculateWorksheetDimension());

        return [1 => ['font' => ['bold' => true]]];
    }
}
