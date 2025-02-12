<?php

namespace Database\Seeders;

use App\Models\Sede;
use Illuminate\Database\Seeder;

class SedeSeeder extends Seeder
{
    public function run()
    {
        $sedes = [
            'Acarigua/Portuguesa',
            'Achaguas/Apure',
            'Altagracia de Orituco/Guárico',
            'Amazonas',
            'Anaco/Anzoátegui',
            'Apure',
            'Barinas',
            'Barquisimeto/Lara',
            'Biscucuy',
            'Cagua/Aragua',
            'Calabozo/Guárico',
            'Camaguan/Guárico',
            'Capacho/Táchira',
            'Caracas/Distrito Capital',
            'Carora',
            'Ciudad Bolívar/Bolívar',
            'Ciudad Ojeda/Zulia',
            'Cojedes',
            'Coro/Falcón',
            'Cua/Miranda',
            'Cumaná/Sucre',
            'Distrito Capital/Caracas',
            'El Consejo/Aragua',
            'El Sombrero/Guárico',
            'El Tigre',
            'El Tocuyo/Lara',
            'Gamaguan/Guárico',
            'Guacara/Carabobo',
            'Guanare/Portuguesa',
            'Guarico/Lara',
            'Guasdualito/Apure',
            'Guasipati/Bolívar',
            'Guasipatti/Monagas',
            'Higuerote',
            'Humocaro',
            'La Guaira/Vargas',
            'La Morita/Aragua',
            'La Trinidad/Caracas',
            'La Victoria/Aragua',
            'Las Mercedes del Llano',
            'Los Teques/Miranda',
            'Mantecal',
            'Mapire',
            'Maracay/Aragua',
            'Maturín/Monagas',
            'Mérida',
            'Naguanagua/Carabobo',
            'Ortiz',
            'Palo Negro/Aragua',
            'Pariaguán/Anzoátegui',
            'Pto. Píritu',
            'Puerto Ayacucho/Amazonas',
            'Puerto Cabello/Carabobo',
            'Puerto La Cruz/Anzoátegui',
            'Puerto Ordaz/Bolívar',
            'Quíbor/Lara',
            'San Carlos/Cojedes',
            'San Cristóbal/Táchira',
            'San Felipe/Yaracuy',
            'San Fernando de Apure/Apure',
            'San José de Guanipa/Anzoátegui',
            'San José de Guaribe',
            'San Sebastián de los Reyes/Aragua',
            'Santa Cruz/Aragua',
            'Santa Rita/Maracay',
            'Sede Central/San Juan de los Morros',
            'Tinaquillo',
            'Tucupita/Delta Amacuro',
            'Turmero/Aragua',
            'Upata/Bolívar',
            'Valencia/Carabobo',
            'Valle de la Pascua/Guárico',
            'Villa de Cura/Aragua',
            'Zaraza/Guárico'
        ];
        foreach ($sedes as $nombre) {
            Sede::firstOrCreate(
                ['nombre' => $nombre]
            );
        }
    }
}
