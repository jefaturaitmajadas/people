<?php

namespace Tests\Feature;

use App\Exports\ReporteEmpleadosExport;
use App\Models\Empleado;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Maatwebsite\Excel\Facades\Excel;
use Tests\TestCase;

class ReporteEmpleadosTest extends TestCase
{
    use DatabaseTransactions;

    private int $empresaId;

    protected function setUp(): void
    {
        parent::setUp();

        Schema::disableForeignKeyConstraints();
        $this->empresaId = DB::table('empresas')->insertGetId(['nombre' => 'Empresa Prueba'] + $this->requeridos('empresas', ['id', 'nombre']));
        $this->withSession(['empresa_activa_id' => $this->empresaId]);
    }

    private function requeridos(string $tabla, array $excepto): array
    {
        $valores = [];

        foreach (Schema::getColumns($tabla) as $columna) {
            if ($columna['nullable'] || $columna['default'] !== null || in_array($columna['name'], $excepto)) {
                continue;
            }
            $valores[$columna['name']] = str_contains($columna['type'], 'int') ? 1 : 'x';
        }

        return $valores;
    }

    private function crearEmpleado(array $datos): Empleado
    {
        $empleado = new Empleado();
        $empleado->forceFill(array_merge($this->requeridos('empleados', ['id']), ['empresa_id' => $this->empresaId], $datos))->save();

        return $empleado;
    }

    private function usuario(string $rol = 'administrador', ?int $empleadoId = null): User
    {
        $usuario = new User();
        $usuario->forceFill(['name' => 'Prueba', 'email' => uniqid() . '@prueba.test', 'password' => 'clave-prueba', 'rol' => $rol, 'empleado_id' => $empleadoId])->save();

        return $usuario;
    }

    public function test_filtra_por_estado(): void
    {
        $this->crearEmpleado(['primer_nombre' => 'Xiomara', 'primer_apellido' => 'Uno']);
        $this->crearEmpleado(['primer_nombre' => 'Retirada', 'primer_apellido' => 'Dos', 'fecha_salida' => '2026-01-15']);
        $admin = $this->usuario();

        $this->actingAs($admin)->get('/reportes/empleados')->assertOk()->assertSee('Xiomara')->assertSee('Retirada');
        $this->actingAs($admin)->get('/reportes/empleados?estado=activos')->assertOk()->assertSee('Xiomara')->assertDontSee('Retirada');
        $this->actingAs($admin)->get('/reportes/empleados?estado=baja')->assertOk()->assertDontSee('Xiomara')->assertSee('15/01/2026');
        $this->actingAs($admin)->get('/reportes/empleados?estado=otro')->assertOk()->assertSee('Xiomara')->assertSee('Retirada');
    }

    public function test_busca_por_nombre_codigo_e_identificacion(): void
    {
        $this->crearEmpleado(['primer_nombre' => 'Xiomara', 'primer_apellido' => 'Quintana', 'codigo_empleado' => 'P-901', 'identificacion' => '9876543210101']);
        $this->crearEmpleado(['primer_nombre' => 'Wendolyn', 'primer_apellido' => 'Zamora', 'codigo_empleado' => 'P-902']);
        $admin = $this->usuario();

        foreach (['xiomara quintana', 'quintana xiomara', 'P-901', '9876543210101'] as $texto) {
            $this->actingAs($admin)->get('/reportes/empleados?' . http_build_query(['buscar' => $texto]))
                ->assertOk()->assertSee('Xiomara')->assertDontSee('Wendolyn');
        }

        $this->actingAs($admin)->get('/reportes/empleados?buscar=' . urlencode('%'))->assertOk()->assertSee('No se encontraron');
        $this->actingAs($admin)->get('/reportes/empleados?buscar[]=x&estado[]=y')->assertOk()->assertSee('Xiomara');
    }

    public function test_la_busqueda_respeta_el_estado_y_llega_al_excel(): void
    {
        $this->crearEmpleado(['primer_nombre' => 'Xiomara', 'primer_apellido' => 'Quintana', 'fecha_salida' => '2026-01-15']);
        $this->crearEmpleado(['primer_nombre' => 'Xiomara', 'primer_apellido' => 'Zamora']);
        $admin = $this->usuario();

        $this->actingAs($admin)->get('/reportes/empleados?estado=baja&buscar=xiomara')
            ->assertOk()->assertSee('Quintana')->assertDontSee('Zamora')
            ->assertSee(route('reportes.empleados.excel', ['estado' => 'baja', 'buscar' => 'xiomara']));

        session(['empresa_activa_id' => $this->empresaId]);
        $this->assertCount(1, (new ReporteEmpleadosExport('baja', 'xiomara'))->collection());
        $this->assertCount(2, (new ReporteEmpleadosExport('todos', 'xiomara'))->collection());
    }

    public function test_muestra_nombres_de_catalogos_y_no_ids(): void
    {
        $bancoId = DB::table('banco')->insertGetId(['nombre' => 'Banco de Prueba'] + $this->requeridos('banco', ['id', 'nombre']));
        $jefe = $this->crearEmpleado(['primer_nombre' => 'Jefa', 'primer_apellido' => 'Mayor']);
        $empleado = $this->crearEmpleado(['primer_nombre' => 'Ana', 'primer_apellido' => 'Lopez', 'banco_id' => $bancoId, 'jefe_id' => $jefe->id, 'vehiculo' => true]);

        $reporte = new ReporteEmpleadosExport();
        session(['empresa_activa_id' => $this->empresaId]);
        $fila = array_combine($reporte->headings(), $reporte->map($empleado->fresh()));

        $this->assertSame('Banco de Prueba', $fila['Banco']);
        $this->assertStringContainsString('Jefa', $fila['Jefe']);
        $this->assertSame('Sí', $fila['Vehículo']);
        $this->assertSame('Activo', $fila['Estado']);
    }

    public function test_exporta_excel_con_el_filtro_elegido(): void
    {
        Excel::fake();

        $this->actingAs($this->usuario())->get('/reportes/empleados/excel?estado=baja')->assertOk();

        Excel::assertDownloaded('Reporte_General_Empleados_De_Baja.xlsx');
    }

    public function test_solo_ve_empleados_de_la_empresa_activa(): void
    {
        $otra = DB::table('empresas')->insertGetId(['nombre' => 'Otra Empresa'] + $this->requeridos('empresas', ['id', 'nombre']));
        $ajena = $this->crearEmpleado(['primer_nombre' => 'Ajena', 'primer_apellido' => 'Tres']);
        // Al crear, el modelo le pone la empresa activa
        DB::table('empleados')->where('id', $ajena->id)->update(['empresa_id' => $otra]);

        $this->actingAs($this->usuario())->get('/reportes/empleados')->assertOk()->assertDontSee('Ajena');
    }

    public function test_el_colaborador_no_accede_al_reporte(): void
    {
        $empleado = $this->crearEmpleado(['primer_nombre' => 'Ana', 'primer_apellido' => 'Lopez']);
        $colaborador = $this->usuario('empleado', $empleado->id);

        foreach (['/reportes/empleados', '/reportes/empleados/excel'] as $ruta) {
            $this->actingAs($colaborador)->get($ruta)->assertRedirect(route('empleados.edit', $empleado->id));
        }
    }
}
