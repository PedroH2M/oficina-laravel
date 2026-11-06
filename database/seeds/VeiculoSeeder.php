<?php

use Illuminate\Database\Seeder;
use App\Models\Veiculo;
use App\Models\HistoricoVeiculo;

class VeiculoSeeder extends Seeder
{
    public function run()
    {
        $v1 = Veiculo::create([
            'cliente_id' => 1,
            'placa' => 'BRA2E19',
            'marca' => 'Volkswagen',
            'modelo' => 'Gol 1.6 MSI',
            'ano_fabricacao' => 2019,
            'ano_modelo' => 2020,
            'cor' => 'Prata',
            'chassi' => '9BWCA45U2KP019283',
            'quilometragem_atual' => 45000,
        ]);

        HistoricoVeiculo::create([
            'veiculo_id' => $v1->id,
            'data_evento' => now()->subMonths(3),
            'quilometragem_registrada' => 40000,
            'tipo_evento' => 'manutencao_preventiva',
            'descricao' => 'Troca de óleo sintético 5W40, filtro de óleo e filtro de ar.',
        ]);

        $v2 = Veiculo::create([
            'cliente_id' => 2,
            'placa' => 'ABC1D23',
            'marca' => 'Chevrolet',
            'modelo' => 'Onix 1.0 Turbo',
            'ano_fabricacao' => 2021,
            'ano_modelo' => 2022,
            'cor' => 'Preto',
            'chassi' => '9BGKL48B0NG382910',
            'quilometragem_atual' => 28000,
        ]);

        HistoricoVeiculo::create([
            'veiculo_id' => $v2->id,
            'data_evento' => now()->subMonths(1),
            'quilometragem_registrada' => 25000,
            'tipo_evento' => 'revisao_geral',
            'descricao' => 'Inspeção geral de freios, suspensão e alinhamento/balanceamento.',
        ]);
    }
}
