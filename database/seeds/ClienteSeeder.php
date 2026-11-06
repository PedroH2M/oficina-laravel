<?php

use Illuminate\Database\Seeder;
use App\Models\Cliente;

class ClienteSeeder extends Seeder
{
    public function run()
    {
        Cliente::create([
            'nome' => 'Carlos Silva',
            'cpf_cnpj' => '12345678901',
            'telefone' => '(11) 98765-4321',
            'email' => 'carlos.silva@exemplo.com.br',
            'endereco' => 'Av. Paulista, 1000',
            'cidade' => 'São Paulo',
            'estado' => 'SP',
            'cep' => '01310-100',
            'observacoes' => 'Cliente fiel da oficina.',
        ]);

        Cliente::create([
            'nome' => 'Mariana Oliveira',
            'cpf_cnpj' => '98765432100',
            'telefone' => '(11) 91234-5678',
            'email' => 'mariana.oliveira@exemplo.com.br',
            'endereco' => 'Rua das Flores, 250',
            'cidade' => 'Campinas',
            'estado' => 'SP',
            'cep' => '13010-050',
            'observacoes' => 'Revisão periódica.',
        ]);
    }
}
