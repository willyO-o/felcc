<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class EnsureUserIsActive
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Si el usuario está autenticado, verificar que esté activo
        if (Auth::check() && !Auth::user()->isActive()) {
            // Desloguear al usuario inactivo
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')
                ->with('message', 'Tu cuenta ha sido desactivada. Por favor, contacta con el administrador.');
        }

        return $next($request);
    }
}
