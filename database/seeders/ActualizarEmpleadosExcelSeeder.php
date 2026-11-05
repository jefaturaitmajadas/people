<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Banco;
use App\Models\Departamento;
use App\Models\Empleado;
use App\Models\Puesto;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Shared\Date;

/**
 * Actualiza empleados desde storage/app/ActualizacionBDempleados.xlsx (Código = codigo_empleado).
 *
 * - Una celda vacía nunca borra lo que ya está en la base, incluida la fecha de baja.
 * - Una identificación repetida se queda en el registro activo o en el más reciente
 *   (recontrataciones); si hay dos activos con la misma, no se asigna a ninguno.
 * - Turno y ocupación no se cargan: sus códigos no existen en los catálogos.
 */
class ActualizarEmpleadosExcelSeeder extends Seeder
{
    private const EMPRESA_ID = 6;

    // Mismo puesto con otro nombre en el catálogo
    private const PUESTOS_EQUIVALENTES = [
        'COORDINADOR DE CONTROL Y PREVENCION DE RIESGOS' => 'COOR. DE CONTROL Y PREVENCION DE RIESGOS',
    ];

    // En estos el Excel trae el dato incompleto o junta el apellido de casada: se conserva la base
    private const CONSERVAR_NOMBRES = ['22', '250'];

    private array $puestos = [];
    private array $departamentos = [];
    private array $avisos = [];

    public function run()
    {
        $filePath = storage_path('app/ActualizacionBDempleados.xlsx');

        if (!file_exists($filePath)) {
            $this->command->error("El archivo no existe en: {$filePath}");
            return;
        }

        $reader = IOFactory::createReaderForFile($filePath);
        $reader->setReadDataOnly(true);
        $rows = $reader->load($filePath)->getSheet(0)->toArray(null, true, false, false);
        $encabezados = array_shift($rows);

        $filas = [];
        foreach ($rows as $row) {
            if ($row[0] !== null && $row[0] !== '') {
                $filas[] = array_combine($encabezados, $row);
            }
        }

        foreach (Puesto::all() as $puesto) {
            $this->puestos[$puesto->nombre] = $puesto->id;
            $this->puestos[$this->clave($puesto->nombre)] ??= $puesto->id;
        }
        foreach (Departamento::all() as $departamento) {
            $this->departamentos[$this->clave($departamento->nombre)] = $departamento->id;
        }

        $bancos = Banco::pluck('id', 'nombre');
        $identificaciones = $this->resolverIdentificaciones($filas);

        $actualizados = 0;
        $creados = 0;

        DB::transaction(function () use ($filas, $bancos, $identificaciones, &$actualizados, &$creados) {
            foreach ($filas as $fila) {
                $codigo = (string) $fila['Código'];
                $empleado = Empleado::where('codigo_empleado', $codigo)->first();
                $datos = $this->mapearDatos($fila, $bancos, $identificaciones[$codigo] ?? null);

                if ($empleado) {
                    // Si alguien fuera del Excel ya tiene esta identificación no se puede repetir
                    if (isset($datos['identificacion']) && Empleado::where('identificacion', $datos['identificacion'])->where('id', '!=', $empleado->id)->exists()) {
                        $this->avisos[] = "Código {$codigo}: su identificación ya la tiene otro empleado, no se asignó.";
                        unset($datos['identificacion']);
                    }

                    if ($empleado->fecha_salida && isset($datos['fecha_salida']) && $empleado->fecha_salida != $datos['fecha_salida']) {
                        $this->avisos[] = "Código {$codigo}: fecha de baja cambió de {$empleado->fecha_salida} a {$datos['fecha_salida']}.";
                    }

                    if ($empleado->fecha_salida && !isset($datos['fecha_salida'])) {
                        $this->avisos[] = "Código {$codigo}: tiene baja {$empleado->fecha_salida} en la base y vacía en el Excel, se conservó.";
                    }

                    $empleado->update(array_merge($datos, $this->mapearNombres($fila, $codigo)));
                    $actualizados++;
                } else {
                    Empleado::create(array_merge($this->valoresNuevo($fila), $datos, $this->mapearNombres($fila, $codigo, true)));
                    $creados++;
                }
            }

            // Segunda pasada: los jefes ya existen todos, incluidos los recién creados
            $ids = Empleado::pluck('id', 'codigo_empleado');

            foreach ($filas as $fila) {
                $manager = (string) $fila['codigo_manager'];

                if ($manager === '') {
                    continue;
                }

                if (!isset($ids[$manager])) {
                    $this->avisos[] = "Código {$fila['Código']}: su jefe {$manager} no existe.";
                    continue;
                }

                Empleado::where('codigo_empleado', (string) $fila['Código'])->update(['jefe_id' => $ids[$manager]]);
            }
        });

        foreach ($this->avisos as $aviso) {
            $this->command->warn($aviso);
        }

        $this->command->info("Actualizados: {$actualizados} | Creados: {$creados} | Avisos: " . count($this->avisos));
    }

    private function mapearDatos(array $fila, $bancos, ?string $identificacion): array
    {
        $datos = [
            'genero_id' => (int) $fila['id_genero'] ?: null,
            'puesto_id' => $this->buscarPuesto($fila),
            'departamento_id' => $this->departamentos[$this->clave($fila['Departamento'])] ?? null,
            'telefono' => $this->telefono($fila['telefono']),
            'correo_personal' => $this->texto($fila['correo_personal']),
            'fecha_inicio' => $this->fechaExcel($fila['fecha_inicio']),
            'fecha_salida' => $this->fechaBaja($fila['fecha_salida']),
            'banco_id' => $bancos[$this->texto($fila['banco'])] ?? null,
            'numero_cuenta' => $this->texto($fila['numero_cuenta']),
            'fecha_nacimiento' => $this->fechaExcel($fila['fecha_nacimiento']),
            'cantidad_hijos' => is_numeric($fila['cantidad_hijos']) ? (int) $fila['cantidad_hijos'] : null,
            'identificacion' => $identificacion,
            'salario_por_hora' => is_numeric($fila['salario_por_hora']) && $fila['salario_por_hora'] > 0 ? round($fila['salario_por_hora'], 2) : null,
        ];

        // Lo que viene vacío no sobrescribe
        return array_filter($datos, fn ($valor) => $valor !== null);
    }

    private function mapearNombres(array $fila, string $codigo, bool $nuevo = false): array
    {
        if (!$nuevo && in_array($codigo, self::CONSERVAR_NOMBRES)) {
            return [];
        }

        $partes = preg_split('/\s+/', trim($fila['Nombre']), 2);

        $datos = [
            'primer_nombre' => $partes[0],
            'segundo_nombre' => $partes[1] ?? null,
            'primer_apellido' => $this->texto($fila['Primer Apellido']),
        ];

        $segundoApellido = $this->texto($fila['Segundo Apellido']);

        if ($segundoApellido !== null || $nuevo) {
            $datos['segundo_apellido'] = $segundoApellido ?? '';
        }

        return $datos;
    }

    // Mismos catálogos por defecto del resto de empleados. Sin mano de obra ni planilla
    // hasta que RRHH complete su salario, porque el Excel no lo trae
    private function valoresNuevo(array $fila): array
    {
        return [
            'codigo_empleado' => (string) $fila['Código'],
            'empresa_id' => self::EMPRESA_ID,
            'genera_planilla' => false,
            'identificacion_id' => 1,
            'tipo_contrato_id' => 1,
            'turno_id' => 1,
            'tipo_salario_id' => 1,
            'estado_civil_id' => 1,
            'religion_id' => 1,
            'pais_nacimiento_id' => 1,
            'municipio_nacimiento_id' => 1,
            'nivel_educativo_id' => 1,
            'pueblo_id' => 1,
            'comunidad_linguistica_id' => 1,
            'tipo_casa_id' => 1,
            'tipo_transporte_id' => 1,
            'pais_residencia_id' => 1,
            'discapacidad_id' => 1,
            'ocupacion_id' => 1,
            'tipo_pago_id' => 1,
            'moneda_id' => 1,
            'seguro_id' => 1,
            'tipo_cuenta_id' => 1,
            'igss_tipo_planilla_id' => 1,
            'centro_costo_id' => 1,
            'profesion_id' => 1,
        ];
    }

    private function resolverIdentificaciones(array $filas): array
    {
        $grupos = [];
        foreach ($filas as $fila) {
            $grupos[(string) $fila['identificacion']][] = $fila;
        }

        $resultado = [];

        foreach ($grupos as $identificacion => $grupo) {
            if (count($grupo) === 1) {
                $resultado[(string) $grupo[0]['Código']] = (string) $identificacion;
                continue;
            }

            $codigos = implode(' y ', array_column($grupo, 'Código'));
            $activos = array_filter($grupo, fn ($fila) => empty($fila['fecha_salida']));

            if (count($activos) > 1) {
                $this->avisos[] = "Códigos {$codigos}: activos con la misma identificación, no se asignó a ninguno.";
                continue;
            }

            usort($grupo, fn ($a, $b) => $b['fecha_inicio'] <=> $a['fecha_inicio']);
            $elegido = $activos ? reset($activos) : $grupo[0];

            $resultado[(string) $elegido['Código']] = (string) $identificacion;
            $this->avisos[] = "Códigos {$codigos}: misma identificación (recontratación), quedó en el {$elegido['Código']}.";
        }

        return $resultado;
    }

    private function buscarPuesto(array $fila): ?int
    {
        $nombre = preg_replace('/\s+/', ' ', trim((string) $fila['Puesto']));
        $nombre = self::PUESTOS_EQUIVALENTES[$nombre] ?? $nombre;

        return $this->puestos[$nombre] ?? $this->puestos[$this->clave($nombre)] ?? null;
    }

    private function clave($valor): string
    {
        return mb_strtolower(preg_replace('/\s+/', ' ', trim((string) $valor)));
    }

    private function texto($valor): ?string
    {
        $valor = preg_replace('/\s+/', ' ', trim((string) $valor));

        return $valor === '' || $valor === 'N/D' ? null : $valor;
    }

    private function telefono($valor): ?string
    {
        $valor = rtrim((string) $this->texto($valor), '.');

        return $valor === '' || $valor === '0' ? null : $valor;
    }

    private function fechaExcel($valor): ?string
    {
        return is_numeric($valor) ? Date::excelToDateTimeObject($valor)->format('Y-m-d') : null;
    }

    // Viene como texto mes/día/año, a veces con hora
    private function fechaBaja($valor): ?string
    {
        $valor = $this->texto($valor);

        return $valor ? Carbon::createFromFormat('n/j/Y', explode(' ', $valor)[0])->format('Y-m-d') : null;
    }
}
