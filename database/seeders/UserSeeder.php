<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Crear roles primero
        $roles = ['admin', 'area_manager', 'teacher'];
        foreach ($roles as $role) {
            Role::firstOrCreate([
                'name' => $role,
                'guard_name' => 'web'
            ]);
        }

        // 2. Crear usuarios base sin factory
        $users = [
            [
                'name' => 'Admin',
                'email' => 'admin@sigedor.com',
                'password' => Hash::make('password'),
                'is_active' => true,
                'is_approved' => true,
                'role' => 'admin'
            ],
            [
                'name' => 'Gestor Área',
                'email' => 'gestor@sigedor.com',
                'password' => Hash::make('password'),
                'is_active' => true,
                'is_approved' => true,
                'role' => 'area_manager'
            ],
            [
                'name' => 'Docente',
                'email' => 'docente@sigedor.com',
                'password' => Hash::make('password'),
                'is_active' => true,
                'is_approved' => true,
                'role' => 'teacher'
            ]
        ];

        foreach ($users as $userData) {
            $user = User::create([
                'name' => $userData['name'],
                'email' => $userData['email'],
                'password' => $userData['password'],
                'is_active' => $userData['is_active'],
                'is_approved' => $userData['is_approved'],
                'email_verified_at' => now(),
                'role' => $userData['role']
            ]);

            $user->assignRole($userData['role']);
        }


        // Crear usuario admin funcional
        User::create([
            'name' => 'Admin Final',
            'email' => 'admin@final.com',
            'password' => Hash::make('password'),
            'is_active' => true,
            'is_approved' => true,
            'email_verified_at' => now(),
            'role' => 'admin'
        ]);

        // Eliminar todos los usuarios existentes
        User::truncate();

        // Crear usuario temporal
        User::create([
            'name' => 'Admin Temporal',
            'email' => 'admin@temporal.com',
            'password' => 'password123', // ← Texto plano temporal
            'is_active' => true,
            'is_approved' => true,
            'email_verified_at' => now(),
            'role' => 'admin' // ← Asegurar que el rol sea 'admin'
        ]);
    }
}
