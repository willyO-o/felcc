<?php

namespace App\Models;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements AuditableContract
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, SoftDeletes;
    use \OwenIt\Auditing\Auditable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
        'is_active',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Rol del usuario.
     */
    public function role()
    {
        return $this->belongsTo(\App\Models\Role::class, 'role_id');
    }

    /**
     * Verificar si el usuario tiene un rol específico.
     */
    public function hasRole(string $roleName): bool
    {
        return $this->role && $this->role->nombre === $roleName;
    }

    /**
     * Verificar si el usuario es superadmin.
     */
    public function isSuperAdmin(): bool
    {
        return $this->hasRole('superadmin');
    }

    /**
     * Verificar si el usuario es administrador.
     */
    public function isAdmin(): bool
    {
        return $this->hasRole('administrador') || $this->hasRole('superadmin');
    }

    /**
     * Verificar si el usuario está activo.
     */
    public function isActive(): bool
    {
        return $this->is_active === true;
    }

    /**
     * Activar usuario.
     */
    public function activate(): void
    {
        $this->update(['is_active' => true]);
    }

    /**
     * Desactivar usuario.
     */
    public function deactivate(): void
    {
        $this->update(['is_active' => false]);
    }

    /**
     * Obtener los permisos del usuario basados en su rol.
     *
     * @return array
     */
    public function getPermissions(): array
    {
        if (!$this->role) {
            return [];
        }

        $acl = config('acl.permissions');
        return $acl[$this->role->nombre] ?? [];
    }

    /**
     * Verificar si el usuario tiene uno o varios permisos.
     *
     * @param string|array $permissions - Uno o varios permisos a verificar
     * @param string $mode - 'any' para verificar si tiene AL MENOS UNO, 'all' para verificar si tiene TODOS
     * @return bool
     */
    public function hasPermission($permissions, string $mode = 'any'): bool
    {
        // Convertir a array si es string
        $permissions = is_array($permissions) ? $permissions : [$permissions];

        // Obtener los permisos del usuario
        $userPermissions = $this->getPermissions();

        // Si está vacío, no tiene permisos
        if (empty($userPermissions) || empty($permissions)) {
            return false;
        }

        if ($mode === 'all') {
            // Verificar que TODOS los permisos solicitados estén en los permisos del usuario
            return count(array_intersect($permissions, $userPermissions)) === count($permissions);
        } else {
            // Verificar que AL MENOS UNO de los permisos esté en los permisos del usuario (por defecto)
            return !empty(array_intersect($permissions, $userPermissions));
        }
    }

    /**
     * Verificar si el usuario tiene al menos uno de los permisos (alias para hasPermission con 'any').
     *
     * @param string|array $permissions
     * @return bool
     */
    public function hasAnyPermission($permissions): bool
    {
        return $this->hasPermission($permissions, 'any');
    }

    /**
     * Verificar si el usuario tiene todos los permisos especificados.
     *
     * @param array $permissions
     * @return bool
     */
    public function hasAllPermissions(array $permissions): bool
    {
        return $this->hasPermission($permissions, 'all');
    }
}
