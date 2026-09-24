<?php

if (!function_exists('hasPermission')) {
    /**
     * Verificar si el usuario autenticado tiene permisos.
     *
     * @param string|array $permissions
     * @param string $mode - 'any' ó 'all'
     * @return bool
     */
    function hasPermission($permissions, string $mode = 'any'): bool
    {
        $user = auth()->user();

        if (!$user) {
            return false;
        }

        return $user->hasPermission($permissions, $mode);
    }
}

if (!function_exists('hasAnyPermission')) {
    /**
     * Verificar si el usuario tiene al menos uno de los permisos.
     *
     * @param string|array $permissions
     * @return bool
     */
    function hasAnyPermission($permissions): bool
    {
        return hasPermission($permissions, 'any');
    }
}

if (!function_exists('hasAllPermissions')) {
    /**
     * Verificar si el usuario tiene todos los permisos.
     *
     * @param array $permissions
     * @return bool
     */
    function hasAllPermissions(array $permissions): bool
    {
        return hasPermission($permissions, 'all');
    }
}

if (!function_exists('getUserPermissions')) {
    /**
     * Obtener todos los permisos del usuario autenticado.
     *
     * @return array
     */
    function getUserPermissions(): array
    {
        $user = auth()->user();

        if (!$user) {
            return [];
        }

        return $user->getPermissions();
    }
}
