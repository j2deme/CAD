<?php

namespace Database\Seeders;

use App\Models\Datos_generale;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Role;
use App\Models\User;
use App\Models\user_role;
use App\Models\Departamento;
use App\Models\Instructore;
use App\Models\Participante;

class InitialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $users = [
            ['email' => 'admin@tecvalles.mx', 'password' => bcrypt('12345678'), 'tipo' => '1']
        ];

        foreach ($users as $user) {
            User::firstOrCreate(
                ['email' => $user['email']],
                ['password' => $user['password'], 'tipo' => $user['tipo']]
            );
        }

        $this->command->info('Usuarios creados');

        $roles = [
            ['nombre' => 'admin'],
            ['nombre' => 'Jefe Departamento'],
            ['nombre' => 'Subdirector Academico'],
            ['nombre' => 'CAD'],
            ['nombre' => 'Instructor']
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate(['nombre' => $role['nombre']]);
        }

        $this->command->info('Roles creados');

        $user_roles = [
            ['user_id' => '1', 'role_id' => '1'],
        ];

        foreach ($user_roles as $user_role) {
            user_role::firstOrCreate([
                'user_id' => $user_role['user_id'],
                'role_id' => $user_role['role_id']
            ]);
        }

        $this->command->info('Usuarios con roles creados');

        $departamentos = [
            ['nombre' => 'Ciencias Basicas'],
            ['nombre' => 'Ciencias Economico - Administrativas'],
            ['nombre' => 'Sistema y computacion'],
            ['nombre' => 'Industrial'],
            ['nombre' => 'Ingenierias'],
            ['nombre' => 'Agronomia']
        ];

        foreach ($departamentos as $departamento) {
            Departamento::firstOrCreate(['nombre' => $departamento['nombre']]);
        }

        $this->command->info('Departamentos creados');

        $datos_generales = [
            ['user_id' => '1', 'nombre' => 'admin', 'departamento_id' => '1']
        ];

        foreach ($datos_generales as $datos) {
            Datos_generale::firstOrCreate(
                ['user_id' => $datos['user_id']],
                ['nombre' => $datos['nombre'], 'departamento_id' => $datos['departamento_id']]
            );
        }

        $this->command->info('Datos generales creados');

        $participantes = [
            ['user_id' => '1', 'plantel' => '.', 'horas' => '0', 'puesto' => 'admin']
        ];

        foreach ($participantes as $participante) {
            Participante::firstOrCreate(
                ['user_id' => $participante['user_id']],
                ['plantel' => $participante['plantel'], 'horas' => $participante['horas'], 'puesto' => $participante['puesto']]
            );
        }

        $this->command->info('Participantes creados');

    }
}
