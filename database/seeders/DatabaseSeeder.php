<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            DepartamentoSeeder::class,
            PeriodoSeeder::class,
            RoleSeeder::class,
            userSeeder::class,
                // CursoSeeder::class, // datos de ejemplo — omitido en producción
            InitialSeeder::class,
                // SugeridoSeeder::class, // datos de ejemplo — omitido en producción
            User_RoleSeeder::class,
            // Agrega aquí otros seeders, por ejemplo:
            // UserSeeder::class,
            // RoleSeeder::class,
        ]);
    }
}
