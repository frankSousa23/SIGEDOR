<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        Permission::create(['name' => 'view teachers']);
        Permission::create(['name' => 'create teachers']);
        Permission::create(['name' => 'edit teachers']);
        Permission::create(['name' => 'delete teachers']);
        Permission::create(['name' => 'approve promotions']);
        Permission::create(['name' => 'manage roles']);
        Permission::create(['name' => 'manage system']);

        // Create roles and assign permissions
        $teacherRole = Role::create(['name' => 'teacher'])
            ->givePermissionTo(['view teachers']);

        $areaManagerRole = Role::create(['name' => 'area-manager'])
            ->givePermissionTo([
                'view teachers',
                'create teachers',
                'edit teachers',
                'approve promotions'
            ]);

        $adminRole = Role::create(['name' => 'admin'])
            ->givePermissionTo(Permission::all());

        // Create admin user
        $admin = User::create([
            'name' => 'Administrador',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
        ]);
        $admin->assignRole('admin');

        // Create area managers
        $areaManagers = [
            [
                'name' => 'Coordinador FACES',
                'email' => 'faces.coord@example.com',
                'area' => 'Ciencias Económicas y Sociales'
            ],
            [
                'name' => 'Coordinador Ingeniería',
                'email' => 'sistemas.coord@example.com',
                'area' => 'Ingeniería en Sistemas'
            ],
            [
                'name' => 'Coordinador Salud',
                'email' => 'salud.coord@example.com',
                'area' => 'Ciencias para la Salud'
            ]
        ];

        foreach ($areaManagers as $manager) {
            $user = User::create([
                'name' => $manager['name'],
                'email' => $manager['email'],
                'password' => Hash::make('password1'),
            ]);
            $user->assignRole('area-manager');
        }

        // 3. Create teachers with their basic information
        $teachersData = [
            // FACES Teachers - Variedad en escalafones y fechas
            // [cdi, nombre, apellido, género, teléfono, email, nacimiento, ingreso, asignatura, categoría, títulos]
            ['15789456', 'María', 'González', 'F', '04141234567', 'maria.gonzalez@faces.com', '1985/05/15', '2015/01/01', 'Administración', 'Agregado', ['Licenciado', 'Magister', 'Doctor']],
            ['16789123', 'Juan', 'Pérez', 'M', '04242345678', 'juan.perez@faces.com', '1982/08/20', '2019/06/15', 'Contabilidad', 'Instructor', ['Licenciado']],
            ['17891234', 'Ana', 'Martínez', 'F', '04161234567', 'ana.martinez@faces.com', '1988/03/10', '2016/09/01', 'Finanzas', 'Asistente', ['Licenciado', 'Magister']],
            ['18912345', 'Carlos', 'Rodríguez', 'M', '04145678901', 'carlos.rodriguez@faces.com', '1983/11/25', '2013/12/01', 'Mercadeo', 'Titular', ['Licenciado', 'Magister', 'Doctor']],
            ['19123456', 'Laura', 'Sánchez', 'F', '04248901234', 'laura.sanchez@faces.com', '1987/07/30', '2020/03/15', 'Auditoría', 'Asistente', ['Licenciado', 'Magister']],
            
            // Ingeniería Teachers
            ['20234567', 'Pedro', 'López', 'M', '04149876543', 'pedro.lopez@ing.com', '1984/04/18', '2017/08/20', 'Algoritmos', 'Asistente', ['Licenciado', 'Magister']],
            ['21345678', 'Carmen', 'Díaz', 'F', '04248765432', 'carmen.diaz@ing.com', '1986/09/25', '2014/05/15', 'Programación', 'Agregado', ['Licenciado', 'Magister', 'Doctor']],
            ['22456789', 'José', 'Torres', 'M', '04167654321', 'jose.torres@ing.com', '1983/12/05', '2018/11/30', 'Base de Datos', 'Asistente', ['Licenciado', 'Magister']],
            ['23567890', 'Isabel', 'Ruiz', 'F', '04246543210', 'isabel.ruiz@ing.com', '1985/06/22', '2015/03/10', 'Redes', 'Titular', ['Licenciado', 'Magister', 'Doctor']],
            ['24678901', 'Miguel', 'Castro', 'M', '04145432109', 'miguel.castro@ing.com', '1982/10/15', '2021/09/25', 'Sistemas Operativos', 'Instructor', ['Licenciado']],
            
            // Salud Teachers
            ['25789012', 'Rosa', 'Medina', 'F', '04244321098', 'rosa.medina@salud.com', '1984/08/30', '2016/12/05', 'Anatomía', 'Agregado', ['Licenciado', 'Magister', 'Doctor']],
            ['26890123', 'Luis', 'Vargas', 'M', '04163210987', 'luis.vargas@salud.com', '1986/03/12', '2019/07/20', 'Fisiología', 'Asistente', ['Licenciado', 'Magister']],
            ['27901234', 'Elena', 'Flores', 'F', '04242109876', 'elena.flores@salud.com', '1983/05/25', '2014/04/15', 'Patología', 'Titular', ['Licenciado', 'Magister', 'Doctor']],
            ['28012345', 'Daniel', 'Rojas', 'M', '04141098765', 'daniel.rojas@salud.com', '1985/11/08', '2020/10/30', 'Instructor', 'Instructor', ['Licenciado']],
            ['29123456', 'Patricia', 'Morales', 'F', '04240987654', 'patricia.morales@salud.com', '1982/07/15', '2015/06/25', 'Farmacología', 'Agregado', ['Licenciado', 'Magister', 'Doctor']]
        ];

        $teacherIds = [];
        foreach ($teachersData as $teacher) {
            // Create user account for teacher
            $user = User::create([
                'name' => $teacher[1] . ' ' . $teacher[2],
                'email' => $teacher[5],
                'password' => Hash::make('password2'),
            ]);
            $user->assignRole('teacher');

            // Create teacher record
            $teacherId = DB::table('teachers')->insertGetId([
                'cdi' => $teacher[0],
                'name' => $teacher[1],
                'surName' => $teacher[2],
                'genre' => $teacher[3],
                'phone' => $teacher[4],
                'email' => $teacher[5],
                'birthDate' => $teacher[6],
                'datePromotion' => $teacher[7],
                'asignaturePromotion' => $teacher[8],
                'user_id' => $user->id,
            ]);

            $teacherIds[$teacher[0]] = [
                'id' => $teacherId,
                'user_id' => $user->id,
                'asignature' => $teacher[8],
                'category' => $teacher[9],
                'titles' => $teacher[10]
            ];

            // Calculate promotion dates based on initial promotion date and current category
            $promotionDate = Carbon::parse($teacher[7]);
            $titles = $teacher[10];
            
            // Determine initial category and dates based on titles
            $categories = [];
            $currentDate = $promotionDate->copy();
            
            // Always set instructor date as entry date
            $categories['Instructor'] = $promotionDate->format('Y-m-d');
            
            // If they have a master's degree at entry, they start as Assistant
            if (count($titles) >= 2) {
                $categories['Asistente'] = $promotionDate->format('Y-m-d');
                $currentDate = $promotionDate->copy();
            } else {
                $categories['Asistente'] = $promotionDate->copy()->addYears(2)->format('Y-m-d');
                $currentDate = $promotionDate->copy()->addYears(2);
            }
            
            // Add 4 years for Agregado from last promotion
            $categories['Agregado'] = $currentDate->copy()->addYears(4)->format('Y-m-d');
            
            // Add 5 years for Titular from Agregado
            $categories['Titular'] = $currentDate->copy()->addYears(9)->format('Y-m-d');

            // Create categories for teachers
            DB::table('categories')->insert([
                'teacher_id' => $teacherId,
                'preTitle' => $titles[0] . ' en ' . $teacher[8],
                'lastTitle' => end($titles) . ' en ' . $teacher[8],
                'instructor' => $categories['Instructor'],
                'asistente' => $categories['Asistente'],
                'agregado' => $categories['Agregado'],
                'titular' => $categories['Titular'],
                'current_category' => $teacher[9],
                'disable_assistant_rule' => count($titles) < 2, // Disable assistant rule if they don't have a master's
            ]);
        }

        // 4. Create sites assignments
        $sitesData = [
            // FACES Sites
            ['Central', 'Ciencias Económicas y Sociales', 'Administración Comercial', 'Administración', 12, 3],
            ['Central', 'Ciencias Económicas y Sociales', 'Administración Comercial', 'Contabilidad', 10, 2],
            ['Central', 'Ciencias Económicas y Sociales', 'Administración Comercial', 'Finanzas', 8, 2],
            ['Central', 'Ciencias Económicas y Sociales', 'Administración Comercial', 'Mercadeo', 6, 1],
            ['Central', 'Ciencias Económicas y Sociales', 'Contaduría Pública', 'Auditoría', 12, 3],
            // Ingeniería Sites
            ['Central', 'Ingeniería en Sistemas', 'Ingeniería en Informática', 'Algoritmos', 8, 2],
            ['Central', 'Ingeniería en Sistemas', 'Ingeniería en Informática', 'Programación', 12, 3],
            ['Central', 'Ingeniería en Sistemas', 'Ingeniería en Informática', 'Base de Datos', 10, 2],
            ['Maracay', 'Ingeniería en Sistemas', 'Ingeniería en Informática', 'Redes', 8, 2],
            ['Maracay', 'Ingeniería en Sistemas', 'Ingeniería en Informática', 'Sistemas Operativos', 6, 1],
            // Salud Sites
            ['Maracay', 'Ciencias para la Salud', 'Medicina', 'Anatomía', 12, 3],
            ['Maracay', 'Ciencias para la Salud', 'Medicina', 'Fisiología', 10, 2],
            ['Maracay', 'Ciencias para la Salud', 'Medicina', 'Patología', 8, 2],
            ['Maracay', 'Ciencias para la Salud', 'Medicina', 'Cirugía', 12, 3],
            ['Maracay', 'Ciencias para la Salud', 'Enfermería', 'Farmacología', 10, 2]
        ];

        foreach ($teacherIds as $cdi => $teacherInfo) {
            $siteData = array_filter($sitesData, function($site) use ($teacherInfo) {
                return $site[3] === $teacherInfo['asignature'];
            });

            if (!empty($siteData)) {
                $siteData = reset($siteData);
                DB::table('sites')->insert([
                    'teacher_id' => $teacherInfo['id'],
                    'site' => $siteData[0],
                    'area' => $siteData[1],
                    'program' => $siteData[2],
                    'uc' => $siteData[3],
                    'weekHours' => $siteData[4],
                    'sections' => $siteData[5],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
