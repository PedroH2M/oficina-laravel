<?php

use Illuminate\Database\Seeder;
use App\Models\Peca;

class PecaSeeder extends Seeder
{
    public function run()
    {
        $pecas = [
            [
                'codigo' => 'PEC-OLEO-5W40',
                'nome' => 'Óleo Sintético 5W40 1L',
                'descricao' => 'Lubrificante de motor para alto desempenho',
                'preco_custo' => 28.50,
                'preco_venda' => 45.00,
                'quantidade_estoque' => 40,
                'estoque_minimo' => 10,
                'unidade_medida' => 'L',
            ],
            [
                'codigo' => 'PEC-FIL-OLEO',
                'nome' => 'Filtro de Óleo Blindado',
                'descricao' => 'Filtro lubrificante automotivo',
                'preco_custo' => 15.00,
                'preco_venda' => 32.00,
                'quantidade_estoque' => 15,
                'estoque_minimo' => 5,
                'unidade_medida' => 'UN',
            ],
            [
                'codigo' => 'PEC-PAST-FREIO-D',
                'nome' => 'Jogo de Pastilhas de Freio Dianteiro',
                'descricao' => 'Pastilhas de freio cerâmica/metálica',
                'preco_custo' => 65.00,
                'preco_venda' => 130.00,
                'quantidade_estoque' => 3, // Estoque baixo para testar alerta!
                'estoque_minimo' => 5,
                'unidade_medida' => 'JG',
            ],
            [
                'codigo' => 'PEC-VELA-IGN',
                'nome' => 'Jogo de Velas de Ignição Iridium',
                'descricao' => 'Velas de ignição para motor 3 e 4 cilindros',
                'preco_custo' => 90.00,
                'preco_venda' => 180.00,
                'quantidade_estoque' => 8,
                'estoque_minimo' => 4,
                'unidade_medida' => 'JG',
            ],
            [
                'codigo' => 'PEC-AMORT-D',
                'nome' => 'Amortecedor Dianteiro Pressurizado',
                'descricao' => 'Amortecedor a gás dianteiro',
                'preco_custo' => 190.00,
                'preco_venda' => 340.00,
                'quantidade_estoque' => 4,
                'estoque_minimo' => 2,
                'unidade_medida' => 'UN',
            ],
        ];

        foreach ($pecas as $peca) {
            Peca::create($peca);
        }
    }
}
