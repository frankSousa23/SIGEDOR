<?php

namespace Database\Seeders;

use App\Models\Programa;
use Illuminate\Database\Seeder;

class ProgramaSeeder extends Seeder
{
    public function run()
    {
        $programas = [
            'Administración comercial',
            'Comunicación social',
            'Contaduría pública',
            'Desarrollo comunitario',
            'Doctorado ciencias de la educación',
            'Doctorado en ciencias administrativas',
            'Economía',
            'Educación continua',
            'Educación integral',
            'Educación mención computación',
            'Enfermería',
            'Enfermería mención salud comunitaria',
            'Especialización derecho administrativo',
            'Especialización en ciencias electorales',
            'Especialización en ciencias penales y criminología',
            'Especialización en derecho laboral',
            'Especialización en derecho mercantil',
            'Especialización en derecho procesal civil',
            'Especialización en dermatología',
            'Especialización en docencia universitaria',
            'Especialización en ecosonografía diagnóstica',
            'Especialización en gestión pública',
            'Especialización en medicina legal',
            'Estudios comunes',
            'Fisioterapia',
            'Hidrocarburos mención gas y mención petróleo',
            'Histocitotecnología',
            'Historia',
            'Ingeniería agronómica producción animal',
            'Ingeniería agronómica producción vegetal',
            'Ingeniería civil',
            'Ingeniería electrónica',
            'Ingeniería en informática',
            'Ingeniería industrial',
            'Licenciatura en historia',
            'Maestría en desarrollo de sistemas de producción animal',
            'Maestría en educación',
            'Maestría en educación enseñanza a la matemática',
            'Maestría en educación mención desarrollo comunitario',
            'Maestría en educación mención investigación educativa',
            'Maestría en educación mención orientación',
            'Maestría en educación mención salud comunitaria',
            'Maestría en enfermería materno infantil mención obstetricia',
            'Maestría en enfermería mención salud comunitaria',
            'Maestría en gerencia administrativa',
            'Maestría en gerencia de la construcción',
            'Maestría en gerencia de la salud pública',
            'Maestría en historia de Venezuela',
            'Maestría en salud pública',
            'Medicina',
            'Medicina veterinaria',
            'Municipalizado de formación en derecho',
            'Municipalizado de formación en derecho/misión Sucre',
            'Nutrición y dietética',
            'Odontología',
            'Optometría y óptica',
            'Profesionalización de enfermería',
            'Profesionalización de optometría',
            'Profesionalización en radioimagenología',
            'Radiodiagnóstico',
            'Radioimagenología',
            'Terapia ocupacional'
        ];

        foreach ($programas as $nombre) {
            Programa::firstOrCreate(
                ['nombre' => $nombre]
            );
        }
    }
}
