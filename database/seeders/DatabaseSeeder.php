<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use App\Models\Site;
use App\Models\Category;
use App\Models\Dedication;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Limpiar caché de roles y permisos
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // 1. Crear permisos básicos
        $permissions = [
            // Permisos de Teachers
            'view_teachers' => 'Ver profesores',
            'create_teachers' => 'Crear profesores',
            'edit_teachers' => 'Editar profesores',
            'delete_teachers' => 'Eliminar profesores',
            // Permisos de Sites
            'view_sites' => 'Ver sedes',
            'create_sites' => 'Crear sedes',
            'edit_sites' => 'Editar sedes',
            'delete_sites' => 'Eliminar sedes',
            // Permisos de Categories
            'view_categories' => 'Ver categorías',
            'create_categories' => 'Crear categorías',
            'edit_categories' => 'Editar categorías',
            'delete_categories' => 'Eliminar categorías',
            // Permisos de Dedications
            'view_dedications' => 'Ver dedicaciones',
            'create_dedications' => 'Crear dedicaciones',
            'edit_dedications' => 'Editar dedicaciones',
            'delete_dedications' => 'Eliminar dedicaciones',
            // Permisos de Sistema
            'approve_users' => 'Aprobar usuarios',
            'view_reports' => 'Ver reportes',
            'manage_reports' => 'Gestionar reportes'
        ];

        foreach ($permissions as $permission => $description) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'web'
            ]);
        }

        // 2. Crear roles con permisos específicos
        $roles = [
            'admin' => [
                'description' => 'Administrador del Sistema',
                'permissions' => Permission::all()->pluck('name')->toArray()
            ],
            'area_manager' => [
                'description' => 'Jefe de Área',
                'permissions' => [
                    'view_teachers',
                    'edit_teachers',
                    'view_sites',
                    'view_categories',
                    'view_dedications',
                    'view_reports'
                ]
            ],
            'teacher' => [
                'description' => 'Profesor',
                'permissions' => ['view_teachers']
            ]
        ];

        foreach ($roles as $roleName => $roleData) {
            $role = Role::firstOrCreate([
                'name' => $roleName,
                'guard_name' => 'web'
            ]);
            
            $role->syncPermissions($roleData['permissions']);
        }

        // 3. Crear usuario administrador (único usuario activo inicial)
        $adminData = [
            'name' => 'Administrador',
            'email' => 'admin@example.com',
            'cdi' => '12345678',
            'password' => Hash::make('password'),
            'is_active' => true,
            'is_approved' => true
        ];
        
        $admin = User::firstOrNew(['email' => $adminData['email']]);
        $admin->fill($adminData);
        $admin->save();
        $admin->assignRole('admin');

        // 4. Crear datos base (inactivos inicialmente)
        // Sites
        $sites = [
            ['name' => 'Sede Principal', 'area' => 'Académica'],
            ['name' => 'Sede Norte', 'area' => 'Investigación'],
            ['name' => 'Sede Sur', 'area' => 'Extensión']
        ];

        foreach ($sites as $siteData) {
            Site::firstOrCreate(
                ['name' => $siteData['name']],
                array_merge($siteData, ['is_active' => false, 'is_available' => false])
            );
        }

        // Categories
        $categories = [
            [
                'current_category' => 'Titular',
                'info' => 'Máxima categoría docente',
                'is_active' => true,
                'is_available' => true
            ],
            [
                'current_category' => 'Asociado',
                'info' => 'Categoría intermedia',
                'is_active' => true,
                'is_available' => true
            ],
            [
                'current_category' => 'Asistente',
                'info' => 'Categoría inicial',
                'is_active' => true,
                'is_available' => true
            ]
        ];

        foreach ($categories as $categoryData) {
            Category::firstOrCreate(
                ['current_category' => $categoryData['current_category']],
                $categoryData
            );
        }

        // Dedications
        $dedications = [
            [
                'name' => 'Tiempo Completo',
                'type' => 'TC',
                'hours' => 40,
                'description' => 'Dedicación a tiempo completo'
            ],
            [
                'name' => 'Medio Tiempo',
                'type' => 'MT',
                'hours' => 20,
                'description' => 'Dedicación a medio tiempo'
            ],
            [
                'name' => 'Tiempo Convencional',
                'type' => 'TCV',
                'hours' => 12,
                'description' => 'Dedicación parcial'
            ]
        ];

        foreach ($dedications as $dedicationData) {
            Dedication::firstOrCreate(
                ['name' => $dedicationData['name']],
                array_merge($dedicationData, ['is_active' => false, 'is_available' => false])
            );
        }
    }
}
