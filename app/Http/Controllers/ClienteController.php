<?php

namespace App\Http\Controllers;

use App\Http\Requests\ClienteRequisicao;
use App\Services\Contratos\ClienteServicoInterface;
use Illuminate\Http\Request;

class ClienteController extends Controller
{
    protected $clienteServico;

    public function __construct(ClienteServicoInterface $clienteServico)
    {
        $this->clienteServico = $clienteServico;
    }

    public function index()
    {
        $clientes = $this->clienteServico->paginarClientes();
        return view('clientes.index', compact('clientes'));
    }

    public function criar()
    {
        return view('clientes.criar');
    }

    public function armazenar(ClienteRequisicao $request)
    {
        $this->clienteServico->cadastrarCliente($request->validated());
        return redirect()->route('clientes.index')->with('sucesso', 'Cliente cadastrado com sucesso!');
    }

    public function editar($id)
    {
        $cliente = $this->clienteServico->obterCliente($id);
        if (!$cliente) {
            return redirect()->route('clientes.index')->with('erro', 'Cliente não encontrado.');
        }
        return view('clientes.editar', compact('cliente'));
    }

    public function atualizar(ClienteRequisicao $request, $id)
    {
        $this->clienteServico->atualizarCliente($id, $request->validated());
        return redirect()->route('clientes.index')->with('sucesso', 'Cliente atualizado com sucesso!');
    }

    public function excluir($id)
    {
        $this->clienteServico->excluirCliente($id);
        return redirect()->route('clientes.index')->with('sucesso', 'Cliente removido com sucesso!');
    }
}
