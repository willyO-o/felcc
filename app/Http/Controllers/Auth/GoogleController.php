<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Http\RedirectResponse;
use Throwable;

class GoogleController extends Controller
{
    /**
     * Redirige al usuario a la pantalla de autenticación de Google.
     */
    public function redirect(): RedirectResponse
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Procesa la respuesta de Google y autentica al usuario si corresponde.
     *
     * No se permite registro: el usuario debe existir previamente en el
     * sistema (creado por un administrador) y debe estar activo.
     */
    public function callback(): RedirectResponse
    {
        try {
            $googleUser = Socialite::driver('google')->stateless()->user();
        } catch (Throwable $e) {
            Log::warning('Fallo la autenticación con Google: ' . $e->getMessage());

            return redirect()->route('login')
                ->with('message', 'No se pudo completar la autenticación con Google. Inténtalo nuevamente.');
        }

        $email = $googleUser->getEmail();

        if (empty($email)) {
            return redirect()->route('login')
                ->with('message', 'No se pudo obtener el correo electrónico de la cuenta de Google.');
        }

        $user = User::where('email', $email)->first();

        // No se permite el auto-registro: el usuario debe existir previamente.
        if (!$user) {
            return redirect()->route('login')
                ->with('message', 'No existe una cuenta registrada con este correo. Contacta con el administrador.');
        }

        if (!$user->isActive()) {
            return redirect()->route('login')
                ->with('message', 'Tu cuenta ha sido desactivada. Por favor, contacta con el administrador.');
        }

        Auth::login($user, true);

        // Añadir el rol de usuario a la sesión, igual que en el login tradicional.
        session(['user_role' => $user->role]);

        return redirect()->intended('/dashboard');
    }
}
