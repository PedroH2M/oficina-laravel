<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrdemServicoRequisicao;
use App\Models\Cliente;
use App\Models\Veiculo;
use App\Models\Peca;
use App\Models\Servico;
use App\Models\Usuario;
use App\Services\Contratos\OrdemServicoServicoInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrdemServicoController extends Controller
{
    protected $osServico;

    public function __construct(OrdemServicoServicoInterface $osServico)
    {
        $this->osServico = $osServico;
    }

    public function index()
    {
        $ordens = $this->osServico->paginarOrdensServico();
        return view('ordens_servico.index', compact('ordens'));
    }

    public function criar()
    {
        $clientes = Cliente::orderBy('nome')->get();
        $veiculos = Veiculo::orderBy('marca')->get();
        $pecas = Peca::where('quantidade_estoque', '>', 0)->orderBy('nome')->get();
        $servicos = Servico::orderBy('nome')->get();
        $mecanicos = Usuario::whereIn('perfil', ['mecanico', 'administrador'])->get();
        return view('ordens_servico.criar', compact('clientes', 'veiculos', 'pecas', 'servicos', 'mecanicos'));
    }

    public function armazenar(OrdemServicoRequisicao $request)
    {
        $dados = $request->except('itens');
        if (empty($dados['usuario_mecanico_id'])) {
            $dados['usuario_mecanico_id'] = Auth::id() ?? 1;
        }

        try {
            $os = $this->osServico->abrirOrdemServico($dados, $request->input('itens', []));
            return redirect()->route('ordens_servico.detalhes', $os->id)
                ->with('sucesso', "Ordem de Serviço {$os->numero} aberta com sucesso!");
        } catch (\InvalidArgumentException $e) {
            return back()->withInput()->with('erro', $e->getMessage());
        }
    }

    public function detalhes($id)
    {
        $ordemServico = $this->osServico->obterOrdemServico($id);
        if (!$ordemServico) {
            return redirect()->route('ordens_servico.index')->with('erro', 'Ordem de Serviço não encontrada.');
        }
        return view('ordens_servico.detalhes', compact('ordemServico'));
    }

    public function atualizarStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:aberta,em_diagnostico,aguardando_pecas,em_execucao,finalizada,cancelada',
            'solucao_aplicada' => 'nullable|string',
        ]);

        $this->osServico->atualizarStatus($id, $request->input('status'), $request->input('solucao_aplicada'));
        return back()->with('sucesso', 'Status da O.S. atualizado!');
    }

    public function finalizar(Request $request, $id)
    {
        $request->validate([
            'solucao_aplicada' => 'required|string',
        ]);

        $this->osServico->finalizarOrdemServico($id, $request->input('solucao_aplicada'));
        return back()->with('sucesso', 'Ordem de serviço finalizada e entregue!');
    }

    public function cancelar(Request $request, $id)
    {
        $request->validate([
            'motivo' => 'required|string',
        ]);

        $this->osServico->cancelarOrdemServico($id, $request->input('motivo'));
        return back()->with('sucesso', 'Ordem de serviço cancelada e estoque estornado!');
    }
}
