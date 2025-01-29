<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Hash;
use App\Models\SiteOption;
use App\Models\AreaOption;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Usar opciones existentes
        $siteId = SiteOption::first()->id;
        $areaId = AreaOption::first()->id;

        $admin = User::create([
            'name' => 'Admin Temporal',
            'email' => 'admin_temporal@test.com',
            'password' => bcrypt('temp123'), // HASHEO CORRECTO
            'role' => 'admin',
            'site_option_id' => $siteId,
            'area_option_id' => $areaId,
            'is_active' => true,
            'is_approved' => true,
        ]);

        $admin->assignRole('admin');
    }
}
