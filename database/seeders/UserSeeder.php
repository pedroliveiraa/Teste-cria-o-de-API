<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Criar usuário administrador padrão
        User::create([
            'name' => 'Administrador',
            'email' => 'admin@proxxy.com',
            'password' => Hash::make('123456'),
        ]);

        // Criar usuário de teste
        User::create([
            'name' => 'Usuário Teste',
            'email' => 'teste@proxxy.com',
            'password' => Hash::make('123456'),
        ]);
    }
}

