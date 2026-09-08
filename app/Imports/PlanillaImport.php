<?php

namespace App\Imports;

use App\Models\Empleado;
use App\Models\DetalleCarga;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class PlanillaImport implements ToCollection, WithHeadingRow
{
    protected $carga_id;

    // Recibimos el ID de la carga maestra al instanciar la clase
    public function __construct($carga_id)
    {
        $this->carga_id = $carga_id;
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            // Buscamos al empleado por su código. 
            // Nota: El Excel debe tener una columna llamada "codigo_empleado"
            $empleado = Empleado::where('codigo_empleado', $row['codigo_empleado'])->first();

            // Solo guardamos el detalle si el empleado existe en el sistema
            if ($empleado) {
                DetalleCarga::create([
                    'carga_id'           => $this->carga_id,
                    'empleado_id'        => $empleado->id,
                    'sueldo_base'        => $row['sueldo_base'] ?? 0,
                    'salario_diario'     => $row['salario_diario'] ?? 0,
                    'dias_laborados'     => $row['dias_laborados'] ?? 0,
                    'horas_extras'       => $row['horas_extras'] ?? 0,
                    'salario'            => $row['salario'] ?? 0,
                    'desc_igss'          => $row['desc_igss'] ?? 0,
                    'salario_liquido'    => $row['salario_liquido'] ?? 0,
                    'bonificacion_ley'   => $row['bonificacion_ley'] ?? 0,
                    'bonificacion_extra' => $row['bonificacion_extra'] ?? 0,
                    'descuentos_varios'  => $row['descuentos_varios'] ?? 0,
                    'isr'                => $row['isr'] ?? 0,
                    'liquido'            => $row['liquido'] ?? 0,
                ]);
            }
        }
    }
}
