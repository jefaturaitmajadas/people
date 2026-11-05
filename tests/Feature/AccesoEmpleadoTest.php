<?php

namespace Tests\Feature;

use App\Models\Empleado;
use App\Models\Empresa;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class AccesoEmpleadoTest extends TestCase
{
    use DatabaseTransactions;

    private const DPI = '1234567890101';

    private function crearEmpleado(array $datos = []): Empleado
    {
        Schema::disableForeignKeyConstraints();

        $id = DB::table('empresas')->insertGetId(['nombre' => 'Empresa Prueba'] + $this->requeridos('empresas', ['id', 'nombre']));

        $empleado = new Empleado();
        $empleado->forceFill(array_merge($this->requeridos('empleados', ['id']), [
            'empresa_id' => $id,
            'primer_nombre' => 'Ana',
            'primer_apellido' => 'Lopez',
            'identificacion' => self::DPI,
        ], $datos))->save();

        return $empleado;
    }

    // Valores mínimos para las columnas NOT NULL sin valor por defecto
    private function requeridos(string $tabla, array $excepto): array
    {
        $valores = [];

        foreach (Schema::getColumns($tabla) as $columna) {
            if ($columna['nullable'] || $columna['default'] !== null || in_array($columna['name'], $excepto)) {
                continue;
            }
            $valores[$columna['name']] = str_contains($columna['type'], 'int') ? 1 : (str_contains($columna['type'], 'date') ? '2020-01-01' : 'x');
        }

        return $valores;
    }

    public function test_primer_ingreso_obliga_a_cambiar_la_contrasena(): void
    {
        $empleado = $this->crearEmpleado();
        $user = User::sincronizarAccesoEmpleado($empleado);

        $this->post('/login', ['email' => '1234 56789 0101', 'password' => self::DPI])->assertRedirect('/dashboard');
        $this->assertAuthenticatedAs($user);

        $this->get('/dashboard')->assertRedirect(route('password.cambiar'));
        $this->get(route('empleados.edit', $empleado->id))->assertRedirect(route('password.cambiar'));

        // No puede dejar la identificación como clave
        $this->put(route('password.cambiar.update'), [
            'current_password' => self::DPI, 'password' => self::DPI, 'password_confirmation' => self::DPI,
        ])->assertSessionHasErrors('password');

        $this->put(route('password.cambiar.update'), [
            'current_password' => self::DPI, 'password' => 'ClaveNueva2026', 'password_confirmation' => 'ClaveNueva2026',
        ])->assertRedirect(route('dashboard'));

        $user->refresh();
        $this->assertFalse($user->debe_cambiar_password);
        $this->assertTrue(Hash::check('ClaveNueva2026', $user->password));

        $this->get('/dashboard')->assertRedirect(route('empleados.edit', $empleado->id));
    }

    public function test_solo_ve_su_ficha_y_en_modo_lectura(): void
    {
        $empleado = $this->crearEmpleado();
        $otro = $this->crearEmpleado(['empresa_id' => $empleado->empresa_id, 'identificacion' => '9999999999999', 'primer_nombre' => 'OtroColaborador']);
        $user = User::sincronizarAccesoEmpleado($empleado);
        $user->update(['debe_cambiar_password' => false]);

        $this->actingAs($user);
        $ficha = route('empleados.edit', $empleado->id);

        $this->get($ficha)->assertOk()->assertSee('Mi Ficha')->assertSee('<fieldset disabled', false)
            ->assertDontSee('Actualizar Empleado')->assertDontSee('Gestión de Nómina')->assertDontSee('OtroColaborador');

        foreach ([route('empleados.edit', $otro->id), route('empleados.index'), route('planillas.index'), route('users.index'), route('profile.edit'), route('reportes.cumpleanos'), route('empleados.constancia', $empleado->id)] as $url) {
            $this->get($url)->assertRedirect($ficha);
        }

        $this->put(route('empleados.update', $empleado->id), ['salario' => 99999])->assertForbidden();
        $this->delete(route('empleados.destroy', $otro->id))->assertForbidden();
        $this->delete(route('profile.destroy'))->assertForbidden();
        $this->post(route('empleados.restablecer_acceso', $empleado->id))->assertForbidden();
    }

    public function test_con_fecha_de_baja_pierde_el_acceso(): void
    {
        $empleado = $this->crearEmpleado();
        $user = User::sincronizarAccesoEmpleado($empleado);
        $user->update(['debe_cambiar_password' => false]);

        $empleado->forceFill(['fecha_salida' => '2026-01-01'])->save();

        $this->actingAs($user)->get(route('empleados.edit', $empleado->id))->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_recursos_humanos_restablece_la_clave(): void
    {
        $empleado = $this->crearEmpleado();
        $user = User::sincronizarAccesoEmpleado($empleado);
        $user->update(['password' => 'OtraClave2026', 'debe_cambiar_password' => false]);

        $admin = User::factory()->create(['rol' => 'administrador']);

        $this->actingAs($admin)->withSession(['empresa_activa_id' => $empleado->empresa_id])
            ->get(route('empleados.edit', $empleado->id))->assertOk()
            ->assertSee('Acceso del colaborador')->assertSee('Actualizar Empleado')->assertDontSee('<fieldset disabled', false);

        $this->post(route('empleados.restablecer_acceso', $empleado->id))->assertSessionHas('success');

        $user->refresh();
        $this->assertTrue($user->debe_cambiar_password);
        $this->assertTrue(Hash::check(self::DPI, $user->password));
    }

    public function test_identificacion_repetida_o_vacia_no_crea_acceso(): void
    {
        $empleado = $this->crearEmpleado();
        User::sincronizarAccesoEmpleado($empleado);

        $repetido = $this->crearEmpleado(['empresa_id' => $empleado->empresa_id, 'identificacion' => '1234-56789-0101']);
        $sinIdentificacion = $this->crearEmpleado(['empresa_id' => $empleado->empresa_id, 'identificacion' => null]);

        $this->assertNull(User::sincronizarAccesoEmpleado($repetido));
        $this->assertNull(User::sincronizarAccesoEmpleado($sinIdentificacion));
        $this->assertSame(1, User::where('rol', 'empleado')->count());
    }
}
