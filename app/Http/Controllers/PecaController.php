<?php

namespace App\Http\Controllers;

use App\Http\Requests\PecaRequisicao;
use App\Services\Contratos\PecaServicoInterface;
use Illuminate\Http\Request;

class PecaController extends Controller
{
    protected $pecaServico;

    public function __construct(PecaServicoInterface $pecaServico)
    {
        $this->pecaServico = $pecaServico;
    }

    public function index()
    {
        $pecas = $this->pecaServico->paginarPecas();
        $alertas = $this->pecaServico->listarAlertasEstoque();
        return view('pecas.index', compact('pecas', 'alertas'));
    }

    public function criar()
    {
        return view('pecas.criar');
    }

    public function armazenar(PecaRequisicao $request)
    {
        $this->pecaServico->cadastrarPeca($request->validated());
        return redirect()->route('pecas.index')->with('sucesso', 'Peça cadastrada com sucesso!');
    }

    public function editar($id)
    {
        $peca = $this->pecaServico->obterPeca($id);
        if (!$peca) {
            return redirect()->route('pecas.index')->with('erro', 'Peça não encontrada.');
        }
        return view('pecas.editar', compact('peca'));
    }

    public function atualizar(PecaRequisicao $request, $id)
    {
        $this->pecaServico->atualizarPeca($id, $request->validated());
        return redirect()->route('pecas.index')->with('sucesso', 'Peça atualizada com sucesso!');
    }

    public function excluir($id)
    {
        $this->pecaServico->excluirPeca($id);
        return redirect()->route('pecas.index')->with('sucesso', 'Peça excluída com sucesso!');
    }
}
