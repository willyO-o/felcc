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
                'id' => 1,
                'nombre' => 'superadmin',
                'descripcion' => 'Superadmin',
            ],
            [
                'id' => 2,
                'nombre' => 'administrador',
                'descripcion' => 'Administrador',
            ],
            [
                'id' => 3,
                'nombre' => 'tecnico_felcc',
                'descripcion' => 'Técnico FELCC',
            ],

            [
                'id' => 4,
                'nombre' => 'consultor_felcc',
                'descripcion' => 'Consultor FELCC',
            ],
            [
                'id' => 5,
                'nombre' => 'tecnico_daci',
                'descripcion' => 'Técnico DACI',
            ],
            [
                'id' => 6,
                'nombre' => 'consultor_daci',
                'descripcion' => 'Consultor DACI',
            ],
        ];

        foreach ($roles as $role) {
            // Role::firstOrCreate(['nombre' => $role['nombre']], $role);
            Role::updateOrCreate(['id' => $role['id']], $role);
        }



        \App\Models\User::updateOrCreate(['email' => 'administrador@gmail.com'], [
            'name' => 'administrador',
            'email' => 'administrador@gmail.com',
            'password' => bcrypt('79515350/WillyWonka2026'),
            'role_id' => 1,
        ]);

        \App\Models\User::updateOrCreate(['email' => 'admin@gmail.com'], [
            'name' => 'admin',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('73054483/administrador2026'),
            'role_id' => 2,
        ]);
    }
}
