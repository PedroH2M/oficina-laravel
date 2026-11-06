<?php

namespace App\Http\Controllers;

use App\Http\Requests\VeiculoRequisicao;
use App\Models\Cliente;
use App\Services\Contratos\VeiculoServicoInterface;
use Illuminate\Http\Request;

class VeiculoController extends Controller
{
    protected $veiculoServico;

    public function __construct(VeiculoServicoInterface $veiculoServico)
    {
        $this->veiculoServico = $veiculoServico;
    }

    public function index()
    {
        $veiculos = $this->veiculoServico->paginarVeiculos();
        return view('veiculos.index', compact('veiculos'));
    }

    public function criar()
    {
        $clientes = Cliente::orderBy('nome')->get();
        return view('veiculos.criar', compact('clientes'));
    }

    public function armazenar(VeiculoRequisicao $request)
    {
        $this->veiculoServico->cadastrarVeiculo($request->validated());
        return redirect()->route('veiculos.index')->with('sucesso', 'Veículo cadastrado com sucesso!');
    }

    public function historico($id)
    {
        $dados = $this->veiculoServico->obterHistoricoCompleto($id);
        if (!$dados['veiculo']) {
            return redirect()->route('veiculos.index')->with('erro', 'Veículo não encontrado.');
        }
        return view('veiculos.historico', $dados);
    }
}
