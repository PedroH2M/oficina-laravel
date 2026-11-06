<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Usuario;

class UsuarioSeeder extends Seeder
{
    public function run()
    {
        Usuario::create([
            'nome' => 'Administrador',
            'email' => 'admin@oficina.com.br',
            'senha' => Hash::make('senha123'),
            'perfil' => 'administrador',
        ]);
    }
}
