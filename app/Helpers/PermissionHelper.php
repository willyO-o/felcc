<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Auth;

class PermissionHelper
{
    /**
     * Verificar si el usuario autenticado tiene permisos.
     *
     * @param string|array $permissions - Uno o varios permisos a verificar
     * @param string $mode - 'any' para verificar al menos uno, 'all' para verificar todos
     * @return bool
     */
    public static function hasPermission($permissions, string $mode = 'any'): bool
    {
        $user = Auth::user();

        if (!$user) {
            return false;
        }

        return $user->hasPermission($permissions, $mode);
    }

    /**
     * Verificar si el usuario tiene al menos uno de los permisos.
     *
     * @param string|array $permissions
     * @return bool
     */
    public static function hasAnyPermission($permissions): bool
    {
        return static::hasPermission($permissions, 'any');
    }

    /**
     * Verificar si el usuario tiene todos los permisos.
     *
     * @param array $permissions
     * @return bool
     */
    public static function hasAllPermissions(array $permissions): bool
    {
        return static::hasPermission($permissions, 'all');
    }

    /**
     * Obtener todos los permisos del usuario autenticado.
     *
     * @return array
     */
    public static function getPermissions(): array
    {
        $user = Auth::user();

        if (!$user) {
            return [];
        }

        return $user->getPermissions();
    }
}
