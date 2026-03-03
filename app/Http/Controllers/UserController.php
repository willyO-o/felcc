<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Listar usuarios (con soporte AJAX para DataTable).
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = User::with('role')
                ->when($request->filled('search'), function ($q) use ($request) {
                    $search = $request->search;
                    $q->where(function ($q2) use ($search) {
                        $q2->where('name', 'like', "%{$search}%")
                           ->orWhere('email', 'like', "%{$search}%");
                    });
                })
                ->when($request->filled('role_id'), function ($q) use ($request) {
                    $q->where('role_id', $request->role_id);
                })
                ->orderBy('id', 'desc');

            $users = $query->paginate($request->get('size', 10), ['*'], 'page', $request->get('page', 1));

            return response()->json([
                'datos' => $users->items(),
                'total' => $users->total(),
                'page' => $users->currentPage(),
            ]);
        }

        $roles = Role::all();
        return view('usuarios.index', compact('roles'));
    }

    /**
     * Mostrar formulario de creación (retorna parcial para modal).
     */
    public function create()
    {
        $user = new User();
        $roles = Role::all();
        return view('usuarios.formulario', compact('user', 'roles'));
    }

    /**
     * Almacenar nuevo usuario.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'role_id'  => 'required|exists:roles,id',
        ], [
            'name.required'      => 'El nombre es obligatorio.',
            'email.required'     => 'El correo electrónico es obligatorio.',
            'email.unique'       => 'Este correo electrónico ya está registrado.',
            'password.required'  => 'La contraseña es obligatoria.',
            'password.min'       => 'La contraseña debe tener al menos 6 caracteres.',
            'password.confirmed' => 'Las contraseñas no coinciden.',
            'role_id.required'   => 'Debe seleccionar un rol.',
            'role_id.exists'     => 'El rol seleccionado no es válido.',
        ]);

        try {
            DB::beginTransaction();

            $user = User::create([
                'name'     => $request->name,
                'email'    => $request->email,
                'password' => $request->password,
                'role_id'  => $request->role_id,
            ]);

            DB::commit();

            $user->load('role');

            return response()->json([
                'success' => 'Usuario creado correctamente.',
                'datos'   => $user,
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'error' => 'Error al crear el usuario: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Mostrar detalle de usuario.
     */
    public function show(string $id)
    {
        $user = User::with('role')->findOrFail($id);
        return response()->json(['datos' => $user], 200);
    }

    /**
     * Obtener datos de usuario para edición.
     */
    public function edit(string $id)
    {
        $user = User::with('role')->findOrFail($id);
        $roles = Role::all();
        return view('usuarios.formulario', compact('user', 'roles'));
    }

    /**
     * Actualizar usuario existente.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => ['required', 'email', 'max:255', Rule::unique('users')->ignore($id)],
            'password' => 'nullable|string|min:6|confirmed',
            'role_id'  => 'required|exists:roles,id',
        ], [
            'name.required'      => 'El nombre es obligatorio.',
            'email.required'     => 'El correo electrónico es obligatorio.',
            'email.unique'       => 'Este correo electrónico ya está registrado.',
            'password.min'       => 'La contraseña debe tener al menos 6 caracteres.',
            'password.confirmed' => 'Las contraseñas no coinciden.',
            'role_id.required'   => 'Debe seleccionar un rol.',
            'role_id.exists'     => 'El rol seleccionado no es válido.',
        ]);

        try {
            DB::beginTransaction();

            $user = User::findOrFail($id);

            $data = [
                'name'    => $request->name,
                'email'   => $request->email,
                'role_id' => $request->role_id,
            ];

            // Solo actualizar contraseña si se proporcionó una nueva
            if ($request->filled('password')) {
                $data['password'] = $request->password;
            }

            $user->update($data);

            DB::commit();

            $user->load('role');

            return response()->json([
                'success' => 'Usuario actualizado correctamente.',
                'datos'   => $user,
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'error' => 'Error al actualizar el usuario: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Eliminar usuario.
     */
    public function destroy(string $id)
    {
        try {
            $user = User::findOrFail($id);

            // No permitir eliminarse a sí mismo
            if (auth()->id() === $user->id) {
                return response()->json([
                    'error' => 'No puedes eliminar tu propia cuenta.',
                ], 403);
            }

            $user->delete();

            return response()->json([
                'success' => 'Usuario eliminado correctamente.',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al eliminar el usuario: ' . $e->getMessage(),
            ], 500);
        }
    }
}
