<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Crear usuario admin
        DB::table('users')->insert([
            'name' => 'Administrador',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
        ]);

        // 2. Crear teachers
        $teacherIds = [];

        // Primeros 5 teachers (originales)
        $teacher1 = DB::table('teachers')->insertGetId([
            'cdi' => '1565973',
            'name' => 'Jesús',
            'surName' => 'Rivas',
            'genre' => 'M',
            'phone' => '04142375006',
            'email' => 'jesus.rivastortosa875@gmail.com',
            'birthDate' => '1950/09/15',
            'datePromotion' => '2004/12/15',
            'asignaturePromotion' => 'Comunitaria',
        ]);
        $teacherIds['1565973'] = $teacher1;

        $teacher2 = DB::table('teachers')->insertGetId([
            'cdi' => '2509861',
            'name' => 'Misael',
            'surName' => 'Pérez',
            'genre' => 'M',
            'phone' => '04144650186',
            'email' => 'misaelperezgomez@hotmail.com',
            'birthDate' => '1946/01/14',
            'datePromotion' => '2011/12/12',
            'asignaturePromotion' => 'Legislación Mercantil',
        ]);
        $teacherIds['2509861'] = $teacher2;

        $teacher3 = DB::table('teachers')->insertGetId([
            'cdi' => '2514145',
            'name' => 'Luz',
            'surName' => 'Diaz',
            'genre' => 'F',
            'phone' => '04265425923',
            'email' => 'luzadiaz05@hotmail.com',
            'birthDate' => '1948/05/28',
            'datePromotion' => '2002/03/18',
            'asignaturePromotion' => 'Lenguaje y Comunicación',
        ]);
        $teacherIds['2514145'] = $teacher3;

        $teacher4 = DB::table('teachers')->insertGetId([
            'cdi' => '2516924',
            'name' => 'Lilia',
            'surName' => 'Bastidas',
            'genre' => 'F',
            'phone' => '04144650371',
            'email' => 'liliabastidasr@gmail.com',
            'birthDate' => '1948/01/19',
            'datePromotion' => '2000/05/30',
            'asignaturePromotion' => 'Derecho',
        ]);
        $teacherIds['2516924'] = $teacher4;

        $teacher5 = DB::table('teachers')->insertGetId([
            'cdi' => '2517421',
            'name' => 'Pedro',
            'surName' => 'Gomez',
            'genre' => 'M',
            'phone' => '04149443137',
            'email' => 'pegom56@gmail.com',
            'birthDate' => '1949/10/11',
            'datePromotion' => '2002/12/09',
            'asignaturePromotion' => 'Bioquímica',
        ]);
        $teacherIds['2517421'] = $teacher5;

        // 15 nuevos teachers
        $newTeachers = [
            ['3526789', 'Ana', 'Martínez', 'F', '04161234567', 'ana.martinez@gmail.com', '1975/03/20', '2010/06/15', 'Química'],
            ['3627890', 'Carlos', 'Rodriguez', 'M', '04241234568', 'carlos.rodriguez@gmail.com', '1980/07/12', '2012/09/20', 'Física'],
            ['3728901', 'María', 'González', 'F', '04161234569', 'maria.gonzalez@gmail.com', '1978/11/05', '2011/04/10', 'Biología'],
            ['3829012', 'José', 'Hernández', 'M', '04241234570', 'jose.hernandez@gmail.com', '1982/02/15', '2013/08/25', 'Matemáticas'],
            ['3930123', 'Laura', 'Pérez', 'F', '04161234571', 'laura.perez@gmail.com', '1979/06/30', '2011/12/05', 'Literatura'],
            ['4031234', 'Roberto', 'Silva', 'M', '04241234572', 'roberto.silva@gmail.com', '1981/09/18', '2012/03/15', 'Historia'],
            ['4132345', 'Carmen', 'Torres', 'F', '04161234573', 'carmen.torres@gmail.com', '1977/04/25', '2010/11/20', 'Geografía'],
            ['4233456', 'Miguel', 'López', 'M', '04241234574', 'miguel.lopez@gmail.com', '1983/08/08', '2014/02/10', 'Informática'],
            ['4334567', 'Isabel', 'Ramírez', 'F', '04161234575', 'isabel.ramirez@gmail.com', '1976/12/12', '2009/07/30', 'Inglés'],
            ['4435678', 'Fernando', 'Castro', 'M', '04241234576', 'fernando.castro@gmail.com', '1984/01/23', '2015/05/15', 'Educación Física'],
            ['4536789', 'Patricia', 'Morales', 'F', '04161234577', 'patricia.morales@gmail.com', '1980/05/16', '2012/10/25', 'Música'],
            ['4637890', 'Ricardo', 'Flores', 'M', '04241234578', 'ricardo.flores@gmail.com', '1979/10/09', '2011/09/05', 'Arte'],
            ['4738901', 'Diana', 'Vargas', 'F', '04161234579', 'diana.vargas@gmail.com', '1981/03/28', '2013/01/20', 'Filosofía'],
            ['4839012', 'Gabriel', 'Medina', 'M', '04241234580', 'gabriel.medina@gmail.com', '1978/07/14', '2010/12/15', 'Sociología'],
            ['4940123', 'Mónica', 'Rojas', 'F', '04161234581', 'monica.rojas@gmail.com', '1982/11/30', '2014/04/10', 'Psicología']
        ];

        foreach ($newTeachers as $teacher) {
            $id = DB::table('teachers')->insertGetId([
                'cdi' => $teacher[0],
                'name' => $teacher[1],
                'surName' => $teacher[2],
                'genre' => $teacher[3],
                'phone' => $teacher[4],
                'email' => $teacher[5],
                'birthDate' => $teacher[6],
                'datePromotion' => $teacher[7],
                'asignaturePromotion' => $teacher[8],
            ]);
            $teacherIds[$teacher[0]] = $id;
        }

        // 3. Crear categories (corrigiendo fechas para ascenso inmediato)
        DB::table('categories')->insert([
            'teacher_id' => $teacherIds['1565973'],
            'preTitle' => 'Licenciado en Matemáticas',
            'lastTitle' => 'Doctor en Ciencias Matemáticas',
            'instructor' => '2004-12-15',
            'asistente' => '2004-12-15', // Fecha igual por ascenso inmediato
            'agregado' => '2010-01-15',
            'asociado' => '2014-01-15',
            'titular' => '2019-01-15',
            'disable_assistant_rule' => true,
            'current_category' => 'Titular',
        ]);

        DB::table('categories')->insert([
            'teacher_id' => $teacherIds['2509861'],
            'preTitle' => 'Abogado',
            'lastTitle' => 'Doctor en Ciencias Jurídicas',
            'instructor' => '2011-12-12',
            'asistente' => '2011-12-12', // Fecha igual por ascenso inmediato
            'agregado' => '2011-02-15',
            'asociado' => '2015-02-15',
            'titular' => '2020-02-15',
            'disable_assistant_rule' => true,
            'current_category' => 'Titular',
        ]);

        DB::table('categories')->insert([
            'teacher_id' => $teacherIds['2514145'],
            'preTitle' => 'Licenciada en Educación Mención Castellano y Literatura',
            'lastTitle' => 'Magister en Lingüística',
            'instructor' => '2002-03-18',
            'asistente' => '2004-03-18',
            'agregado' => '2008-03-18',
            'asociado' => '2012-03-18',
            'disable_assistant_rule' => false,
            'current_category' => 'Asociado',
        ]);

        DB::table('categories')->insert([
            'teacher_id' => $teacherIds['2516924'],
            'preTitle' => 'Abogada',
            'lastTitle' => 'Magister en Derecho Procesal',
            'instructor' => '2000-05-30',
            'asistente' => '2002-05-30',
            'agregado' => '2006-05-30',
            'disable_assistant_rule' => false,
            'current_category' => 'Agregado',
        ]);

        DB::table('categories')->insert([
            'teacher_id' => $teacherIds['2517421'],
            'preTitle' => 'Licenciado en Biología',
            'lastTitle' => 'Doctor en Bioquímica',
            'instructor' => '2002-12-09',
            'asistente' => '2002-12-09', // Fecha igual por ascenso inmediato
            'disable_assistant_rule' => true,
            'current_category' => 'Asistente',
        ]);
    }
}
