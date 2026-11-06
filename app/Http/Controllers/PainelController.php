<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Veiculo;
use App\Models\OrdemServico;
use App\Models\Orcamento;
use App\Models\Peca;

class PainelController extends Controller
{
    public function index()
    {
        $dados = [
            'totalClientes' => Cliente::count(),
            'totalVeiculos' => Veiculo::count(),
            'osAbertas' => OrdemServico::whereIn('status', ['aberta', 'em_diagnostico', 'aguardando_pecas', 'em_execucao'])->count(),
            'osFinalizadas' => OrdemServico::where('status', 'finalizada')->count(),
            'orcamentosPendentes' => Orcamento::where('status', 'pendente')->count(),
            'pecasEstoqueBaixo' => Peca::whereColumn('quantidade_estoque', '<=', 'estoque_minimo')->get(),
            'ultimasOS' => OrdemServico::with(['cliente', 'veiculo'])->latest()->take(5)->get(),
        ];

        return view('painel.index', $dados);
    }
}
