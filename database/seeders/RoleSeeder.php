<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            [
                'nombre' => 'superadmin',
                'descripcion' => 'Super Administrador con acceso total al sistema',
            ],
            [
                'nombre' => 'administrador',
                'descripcion' => 'Administrador con acceso a la gestión general',
            ],
            [
                'nombre' => 'tecnico',
                'descripcion' => 'Técnico con acceso limitado a operaciones',
            ],
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate(['nombre' => $role['nombre']], $role);
        }
    }
}
