<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrcamentoRequisicao;
use App\Models\Cliente;
use App\Models\Veiculo;
use App\Models\Peca;
use App\Models\Servico;
use App\Services\Contratos\OrcamentoServicoInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrcamentoController extends Controller
{
    protected $orcamentoServico;

    public function __construct(OrcamentoServicoInterface $orcamentoServico)
    {
        $this->orcamentoServico = $orcamentoServico;
    }

    public function index()
    {
        $orcamentos = $this->orcamentoServico->paginarOrcamentos();
        return view('orcamentos.index', compact('orcamentos'));
    }

    public function criar()
    {
        $clientes = Cliente::orderBy('nome')->get();
        $veiculos = Veiculo::orderBy('marca')->get();
        $pecas = Peca::where('quantidade_estoque', '>', 0)->orderBy('nome')->get();
        $servicos = Servico::orderBy('nome')->get();
        return view('orcamentos.criar', compact('clientes', 'veiculos', 'pecas', 'servicos'));
    }

    public function armazenar(OrcamentoRequisicao $request)
    {
        $dados = $request->except('itens');
        $dados['usuario_id'] = Auth::id() ?? 1;

        $orcamento = $this->orcamentoServico->criarOrcamento($dados, $request->input('itens', []));
        return redirect()->route('orcamentos.detalhes', $orcamento->id)
            ->with('sucesso', 'Orçamento gerado com sucesso!');
    }

    public function detalhes($id)
    {
        $orcamento = $this->orcamentoServico->obterOrcamento($id);
        if (!$orcamento) {
            return redirect()->route('orcamentos.index')->with('erro', 'Orçamento não encontrado.');
        }
        return view('orcamentos.detalhes', compact('orcamento'));
    }

    public function aprovar($id)
    {
        $this->orcamentoServico->aprovarOrcamento($id);
        return back()->with('sucesso', 'Orçamento marcado como aprovado pelo cliente!');
    }

    public function rejeitar($id)
    {
        $this->orcamentoServico->rejeitarOrcamento($id);
        return back()->with('sucesso', 'Orçamento marcado como rejeitado.');
    }

    public function converterEmOS(Request $request, $id)
    {
        $request->validate([
            'quilometragem_entrada' => 'required|integer|min:0',
        ]);

        $os = $this->orcamentoServico->converterEmOrdemServico(
            $id,
            $request->input('quilometragem_entrada'),
            Auth::id()
        );

        return redirect()->route('ordens_servico.detalhes', $os->id)
            ->with('sucesso', "Orçamento convertido na Ordem de Serviço {$os->numero} com sucesso!");
    }
}
