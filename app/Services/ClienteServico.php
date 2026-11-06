<?php

namespace App\Services;

use App\Models\Cliente;
use App\Repositories\Contratos\ClienteRepositorioInterface;
use App\Services\Contratos\ClienteServicoInterface;
use Illuminate\Pagination\LengthAwarePaginator;

class ClienteServico implements ClienteServicoInterface
{
    protected $clienteRepo;

    public function __construct(ClienteRepositorioInterface $clienteRepo)
    {
        $this->clienteRepo = $clienteRepo;
    }

    public function paginarClientes(int $porPagina = 15): LengthAwarePaginator
    {
        return $this->clienteRepo->paginar($porPagina);
    }

    public function cadastrarCliente(array $dados): Cliente
    {
        // Sanitizar CPF/CNPJ removendo caracteres especiais
        if (isset($dados['cpf_cnpj'])) {
            $dados['cpf_cnpj'] = preg_replace('/[^0-9]/', '', $dados['cpf_cnpj']);
        }
        return $this->clienteRepo->criar($dados);
    }

    public function atualizarCliente(int $id, array $dados): bool
    {
        if (isset($dados['cpf_cnpj'])) {
            $dados['cpf_cnpj'] = preg_replace('/[^0-9]/', '', $dados['cpf_cnpj']);
        }
        return $this->clienteRepo->atualizar($id, $dados);
    }

    public function excluirCliente(int $id): bool
    {
        return $this->clienteRepo->excluir($id);
    }

    public function obterCliente(int $id): ?Cliente
    {
        return $this->clienteRepo->buscarPorId($id);
    }
}
