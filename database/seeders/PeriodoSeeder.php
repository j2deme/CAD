<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Periodo;
class PeriodoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $periodos = [
            ['periodo' => 'AGOSTO-DICIEMBRE 2023'],
            ['periodo' => 'ENERO-JULIO 2024'],
            ['periodo' => 'AGOSTO-DICIEMBRE 2024']
        ];

        foreach ($periodos as $periodo) {
            // Extraer año y trimestre del texto del periodo
            $texto = $periodo['periodo'];
            preg_match('/(\d{4})/', $texto, $matches);
            $anio = $matches[0] ?? date('Y');

            // Determinar trimestre basado en texto
            $trimestre = 0;
            if (stripos($texto, 'ENERO') !== false) {
                $trimestre = 1;
            } elseif (stripos($texto, 'AGOSTO') !== false) {
                $trimestre = 3;
            }

            Periodo::create([
                'periodo' => $texto,
                'anio' => intval($anio),
                'trimestre' => $trimestre
            ]);
        }

        $this->command->info('Periodos creados');
    }
}
