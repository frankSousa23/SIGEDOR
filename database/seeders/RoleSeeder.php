<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            [
                'name' => 'Administrator',
                'slug' => 'admin',
                'description' => 'Full access to all features'
            ],
            [
                'name' => 'Area Manager',
                'slug' => 'area-manager',
                'description' => 'Access to manage teachers within their headquarters and area'
            ],
            [
                'name' => 'Teacher',
                'slug' => 'teacher',
                'description' => 'Access to view own information'
            ],
        ];

        foreach ($roles as $role) {
            Role::create($role);
        }
    }
}
