<?php

namespace App\Http\Controllers;

use App\Http\Requests\ServicoRequisicao;
use App\Services\Contratos\ServicoServicoInterface;
use Illuminate\Http\Request;

class ServicoController extends Controller
{
    protected $servicoServico;

    public function __construct(ServicoServicoInterface $servicoServico)
    {
        $this->servicoServico = $servicoServico;
    }

    public function index()
    {
        $servicos = $this->servicoServico->paginarServicos();
        return view('servicos.index', compact('servicos'));
    }

    public function criar()
    {
        return view('servicos.criar');
    }

    public function armazenar(ServicoRequisicao $request)
    {
        $this->servicoServico->cadastrarServico($request->validated());
        return redirect()->route('servicos.index')->with('sucesso', 'Serviço/Mão de Obra cadastrado com sucesso!');
    }

    public function editar($id)
    {
        $servico = $this->servicoServico->obterServico($id);
        if (!$servico) {
            return redirect()->route('servicos.index')->with('erro', 'Serviço não encontrado.');
        }
        return view('servicos.editar', compact('servico'));
    }

    public function atualizar(ServicoRequisicao $request, $id)
    {
        $this->servicoServico->atualizarServico($id, $request->validated());
        return redirect()->route('servicos.index')->with('sucesso', 'Serviço atualizado com sucesso!');
    }

    public function excluir($id)
    {
        $this->servicoServico->excluirServico($id);
        return redirect()->route('servicos.index')->with('sucesso', 'Serviço removido com sucesso!');
    }
}
