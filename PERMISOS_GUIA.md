# Guía de Permisos - Sistema ACL

## Descripción General

Se ha creado un sistema de verificación de permisos basado en roles. Cada rol tiene asociados ciertos permisos que están definidos en `config/acl.php`.

## Métodos Disponibles

### 1. **Desde el modelo User directamente**

#### `getPermissions()`
Obtiene todos los permisos del usuario.
```php
$user = Auth::user();
$permisos = $user->getPermissions();
// Retorna: ['usuarios_all', 'mandamientos_all', ...]
```

#### `hasPermission($permissions, $mode = 'any')`
Verifica si el usuario tiene permisos. El parámetro `$mode` puede ser:
- `'any'` (por defecto): Verifica si tiene AL MENOS UNO de los permisos
- `'all'`: Verifica si tiene TODOS los permisos

```php
$user = Auth::user();

// Verificar un permiso
$user->hasPermission('usuarios_all');

// Verificar varios permisos (al menos uno)
$user->hasPermission(['usuarios_all', 'mandamientos_all']);

// Verificar que tenga todos los permisos específicos
$user->hasPermission(['usuarios_all', 'mandamientos_all'], 'all');
```

#### `hasAnyPermission($permissions)` 
Alias para verificar si tiene AL MENOS UNO de los permisos.
```php
$user = Auth::user();
$user->hasAnyPermission(['usuarios_all', 'mandamientos_all']);
```

#### `hasAllPermissions($permissions)`
Verifica si tiene TODOS los permisos especificados.
```php
$user = Auth::user();
$user->hasAllPermissions(['usuarios_all', 'mandamientos_all']);
```

---

### 2. **Funciones Globales Helper**

Puedes usar estas funciones en cualquier lugar sin importar la clase:

#### `hasPermission($permissions, $mode = 'any')`
```php
// Verificar un permiso
if (hasPermission('usuarios_all')) {
    // Hacer algo
}

// Verificar varios (al menos uno)
if (hasPermission(['usuarios_all', 'mandamientos_all'])) {
    // Hacer algo
}

// Verificar que tenga TODOS
if (hasPermission(['usuarios_all', 'mandamientos_all'], 'all')) {
    // Hacer algo
}
```

#### `hasAnyPermission($permissions)`
```php
if (hasAnyPermission(['usuarios_all', 'mandamientos_all'])) {
    // Tiene al menos uno
}
```

#### `hasAllPermissions($permissions)`
```php
if (hasAllPermissions(['usuarios_all', 'mandamientos_all'])) {
    // Tiene todos
}
```

#### `getUserPermissions()`
```php
$permisos = getUserPermissions();
// Retorna: ['usuarios_all', 'mandamientos_all', ...]
```

---

### 3. **Desde la clase Helper**

```php
use App\Helpers\PermissionHelper;

PermissionHelper::hasPermission('usuarios_all');
PermissionHelper::hasAnyPermission(['usuarios_all', 'mandamientos_all']);
PermissionHelper::hasAllPermissions(['usuarios_all', 'mandamientos_all']);
PermissionHelper::getPermissions();
```

---

## Ejemplos en Controladores

### Ejemplo 1: Verificar un permiso simple

```php
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        // Método original (aún funciona)
        if ($request->user()->can(['usuarios_all'])) {
            // Tu código
        }

        // Nuevo método - Opción 1: Desde el modelo
        if ($request->user()->hasPermission('usuarios_all')) {
            // Tu código
        }

        // Nuevo método - Opción 2: Función global
        if (hasPermission('usuarios_all')) {
            // Tu código
        }

        // Nuevo método - Opción 3: Helper class
        if (\App\Helpers\PermissionHelper::hasPermission('usuarios_all')) {
            // Tu código
        }
    }
}
```

### Ejemplo 2: Verificar múltiples permisos (AL MENOS UNO)

```php
public function create(Request $request)
{
    // Verificar si tiene al menos uno de estos permisos
    if ($request->user()->hasPermission(['usuarios_all', 'administrador'])) {
        // Puede crear usuarios
        return view('users.create');
    }

    return abort(403, 'No autorizado');
}

// O usando la función global
public function create()
{
    if (hasPermission(['usuarios_all', 'administrador'])) {
        return view('users.create');
    }

    return abort(403, 'No autorizado');
}
```

### Ejemplo 3: Verificar que tenga TODOS los permisos

```php
public function softDelete(Request $request, User $user)
{
    // Requiere TODOS estos permisos
    if (!$request->user()->hasPermission(['usuarios_all', 'mandamientos_all'], 'all')) {
        return abort(403, 'Permisos insuficientes');
    }

    $user->delete();
    return back()->with('success', 'Usuario eliminado');
}

// O usando función global
public function softDelete(User $user)
{
    if (!hasAllPermissions(['usuarios_all', 'mandamientos_all'])) {
        return abort(403, 'Permisos insuficientes');
    }

    $user->delete();
    return back()->with('success', 'Usuario eliminado');
}
```

### Ejemplo 4: En vistas (Blade)

```blade
@if (hasPermission('usuarios_all'))
    <a href="{{ route('users.create') }}" class="btn btn-primary">
        Crear Usuario
    </a>
@endif

@if (hasAnyPermission(['usuarios_all', 'mandamientos_all']))
    <a href="{{ route('dashboard') }}" class="btn btn-secondary">
        Panel de Control
    </a>
@endif

@if (hasAllPermissions(['usuarios_all', 'mandamientos_all']))
    <button class="btn btn-danger">Acción Crítica</button>
@endif
```

---

## Permisos Definidos en `config/acl.php`

| Rol | Permisos |
|-----|----------|
| **superadmin** | `users_all`, `mandamientos_all`, `personas_all`, `vehiculos_all`, `telefonos_all`, `importar_all`, `consulta_all` |
| **administrador** | `users_all`, `mandamientos_all`, `personas_all`, `vehiculos_all`, `telefonos_all`, `importar_all`, `consulta_all` |
| **tecnico_felcc** | `consulta_mandamientos`, `personas_crear`, `mandamientos_crear` |
| **tecnico_daci** | `registro-criminal_crear`, `registro-criminal_listar`, `personas_crear`, `personas_listar`, `mandamientos_listar`, `vehiculos_listar`, `vehiculos_crear`, `telefonos_listar`, `telefonos_crear` |
| **consultor_felcc** | `consulta_mandamientos` |
| **consultor_daci** | `consulta_mandamientos`, `consulta_personas`, `consulta_registro-criminal`, `consulta_vehiculos`, `consulta_telefonos` |

---

## Actualización Requerida

Después de estos cambios, ejecuta:

```bash
composer dump-autoload
```

Esto actualizará el autoloader para incluir los nuevos helpers.

---

## Ventajas del Sistema

✅ **Flexible**: Verifica uno o varios permisos  
✅ **Fácil de usar**: Funciones globales disponibles en cualquier lugar  
✅ **Escalable**: Basado en ACL centralizado  
✅ **Seguro**: Valida automáticamente contra el archivo de configuración  
✅ **Legible**: Código claro y fácil de mantener
