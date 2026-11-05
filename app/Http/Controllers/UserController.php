<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Empresa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    // Proteger todo el controlador para que SOLO el Administrador pueda entrar
    public function __construct()
    {
        // En Laravel 11 se puede usar middleware directo aquí o en las rutas.
        // Para asegurar, validamos en cada método principal:
    }

    private function verificarAdmin()
    {
        if (Auth::user()->rol !== 'administrador') {
            abort(403, 'Acceso denegado. Solo administradores pueden gestionar usuarios.');
        }
    }

    public function index()
    {
        $this->verificarAdmin();
        // Los accesos de colaboradores se administran desde su ficha
        $usuarios = User::with('empresas')->where('rol', '!=', 'empleado')->get();
        return view('users.index', compact('usuarios'));
    }

    public function create()
    {
        $this->verificarAdmin();
        $empresas = Empresa::all();
        return view('users.create', compact('empresas'));
    }

    public function store(Request $request)
    {
        $this->verificarAdmin();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'rol' => 'required|in:administrador,gerente,generalista',
            'empresas' => 'nullable|array' // Array con los IDs de las empresas con chequecito
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'rol' => $request->rol,
        ]);

        // Si es gerente o generalista, le asignamos las empresas seleccionadas
        if ($request->rol !== 'administrador' && $request->has('empresas')) {
            $user->empresas()->attach($request->empresas);
        }

        return redirect()->route('users.index')->with('success', 'Usuario creado y empresas asignadas.');
    }

    public function edit(User $user)
    {
        $this->verificarAdmin();
        $empresas = Empresa::all();
        // Obtenemos los IDs de las empresas que ya tiene asignadas para marcar los checkboxes
        $empresasAsignadas = $user->empresas->pluck('id')->toArray();
        
        return view('users.edit', compact('user', 'empresas', 'empresasAsignadas'));
    }

    public function update(Request $request, User $user)
    {
        $this->verificarAdmin();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,'.$user->id,
            'rol' => 'required|in:administrador,gerente,generalista',
            'empresas' => 'nullable|array'
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'rol' => $request->rol,
        ]);

        if ($request->filled('password')) {
            $user->update(['password' => Hash::make($request->password)]);
        }

        // Sincronizar: Quita las empresas que desmarcó y agrega las nuevas
        if ($request->rol === 'administrador') {
            $user->empresas()->sync([]); // El admin no necesita asignación, ve todo por defecto
        } else {
            $user->empresas()->sync($request->empresas ?? []);
        }

        return redirect()->route('users.index')->with('success', 'Usuario actualizado correctamente.');
    }

    public function destroy(User $user)
    {
        $this->verificarAdmin();
        if ($user->id === Auth::id()) {
            return back()->with('error', 'No puedes eliminarte a ti mismo.');
        }
        $user->delete();
        return redirect()->route('users.index')->with('success', 'Usuario eliminado.');
    }
}