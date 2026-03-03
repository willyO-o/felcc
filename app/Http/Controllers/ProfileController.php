<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    /**
     * Mostrar página de perfil.
     */
    public function index()
    {
        $user = Auth::user();
        return view('perfil.index', compact('user'));
    }

    /**
     * Actualizar contraseña.
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password'         => 'required|string|min:6|confirmed',
        ], [
            'current_password.required' => 'La contraseña actual es obligatoria.',
            'password.required'         => 'La nueva contraseña es obligatoria.',
            'password.min'              => 'La nueva contraseña debe tener al menos 6 caracteres.',
            'password.confirmed'        => 'Las contraseñas no coinciden.',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json([
                'errors' => ['current_password' => ['La contraseña actual es incorrecta.']],
            ], 422);
        }

        $user->update([
            'password' => $request->password,
        ]);

        return response()->json([
            'success' => 'Contraseña actualizada correctamente.',
        ]);
    }
}
