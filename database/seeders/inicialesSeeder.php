<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class inicialesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // generame u seeder cone stos datos para la tabla tipo_mandamiento:
        // MANDAMIENTO DE APREHENSION ,ORDEN DE APREHENSION,PROFUGO KL80, MANDAMIENTO DE CAPTURA,MANDAMIENTO DE APREMIO

        $tiposMandamientos = [
            ['tipo_mandamiento' => 'MANDAMIENTO DE APREHENSION', 'estado_tipo_mandamiento' => 'ACTIVO', 'descripcion_tipo_mandamiento' => 'Mandamiento para la aprehensión de una persona.'],
            ['tipo_mandamiento' => 'ORDEN DE APREHENSION', 'estado_tipo_mandamiento' => 'ACTIVO', 'descripcion_tipo_mandamiento' => 'Orden emitida por una autoridad judicial para la aprehensión.'],
            ['tipo_mandamiento' => 'PROFUGO KL80', 'estado_tipo_mandamiento' => 'ACTIVO', 'descripcion_tipo_mandamiento' => 'Mandamiento para la captura de un prófugo.'],
            ['tipo_mandamiento' => 'MANDAMIENTO DE CAPTURA', 'estado_tipo_mandamiento' => 'ACTIVO', 'descripcion_tipo_mandamiento' => 'Mandamiento para la captura de una persona buscada por la justicia.'],
            ['tipo_mandamiento' => 'MANDAMIENTO DE APREMIO', 'estado_tipo_mandamiento' => 'ACTIVO', 'descripcion_tipo_mandamiento' => 'Mandamiento para ejercer presión legal sobre una persona.'],
        ];

        foreach ($tiposMandamientos as $tipo) {
            \App\Models\TipoMandamiento::create($tipo);
        }

        // generame otro seeder para tabla delitos cone stos datos y omite los repetidos:

        $delitos = [
            ['nombre_delito' => 'ESTAFA'],
            ['nombre_delito' => 'PATROCINIO INFIEL RECPTACION'],
            ['nombre_delito' => 'ROBO'],
            ['nombre_delito' => 'RAPTO IMPROPIO'],
            ['nombre_delito' => 'ROBO AGRAVADO'],
            ['nombre_delito' => 'EVASIÓN'],
            ['nombre_delito' => 'EVASIÓN, FAVORECIMIENTO A LA EVASIÓN, INCUMPLIMIENTO DE DEBERES.'],
            ['nombre_delito' => 'ASISTENCIA FAMILIAR'],
            ['nombre_delito' => 'ASESINATO'],
            ['nombre_delito' => 'VIOLACIÓN A NIÑO, NIÑA, ADOLESCENTE'],
            ['nombre_delito' => 'VIOLACIÓN'],
        ];

        foreach ($delitos as $delito) {
            \App\Models\Delito::firstOrCreate($delito);
        }

        // generame otro seeder para tabla juzgados cone stos datos y omite los repetidos:


        $juzgados = [
            ['nombre_juzgado' => 'PRIMERO DE INSTRUCCIÓN EN LO PENAL CAUTELAR'],
            ['nombre_juzgado' => 'FISCALIA'],
            ['nombre_juzgado' => 'JUZGADO PRIMERO DE EJECUCION PENAL'],
            ['nombre_juzgado' => 'JUZGADO DE TRABAJO Y SEGURIDAD SOCIAL No. 2 DEL DPTO DE CBBA.'],
            ['nombre_juzgado' => 'JUZGADO 3RO. DE EJECUCION PENAL'],
            ['nombre_juzgado' => 'JUZGADO DE PARTIDO Y SENTENCIA CHUQUISACA'],
            ['nombre_juzgado' => 'JUZGADO 5TO. PARTIDO DE FAMILIA'],
            ['nombre_juzgado' => 'JUZGADO 4TO DE TRABAJO Y SEGURIDAD SOCIAL'],
            ['nombre_juzgado' => 'JUZGADO PUBLICO DE MATERIA NIÑEZ Y ADOLESCENCIA'],
            ['nombre_juzgado' => 'JUZGADO PUBLICO DE FAMILIA'],
            ['nombre_juzgado' => 'JUZGADO DE INSTRUCCIÓN DE CULPINA - CHUQUISACA'],
            ['nombre_juzgado' => 'TRIBUNAL 7MO. DE SENTENCIA Y SUSTANCIAS CONTROLADAS'],
        ];

        foreach ($juzgados as $juzgado) {
            \App\Models\Juzgado::firstOrCreate($juzgado);
        }

    }
}
