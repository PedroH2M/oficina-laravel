<?php

namespace App\Repositories\Eloquent;

use App\Models\Cliente;
use App\Repositories\Contratos\ClienteRepositorioInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class ClienteRepositorio implements ClienteRepositorioInterface
{
    protected $modelo;

    public function __construct(Cliente $modelo)
    {
        $this->modelo = $modelo;
    }

    public function listarTodos(): Collection
    {
        return $this->modelo->with('veiculos')->orderBy('nome')->get();
    }

    public function paginar(int $porPagina = 15): LengthAwarePaginator
    {
        return $this->modelo->with('veiculos')->orderBy('nome')->paginate($porPagina);
    }

    public function buscarPorId(int $id): ?Cliente
    {
        return $this->modelo->with(['veiculos', 'ordensServico'])->find($id);
    }

    public function buscarPorCpfCnpj(string $cpfCnpj): ?Cliente
    {
        return $this->modelo->where('cpf_cnpj', $cpfCnpj)->first();
    }

    public function criar(array $dados): Cliente
    {
        return $this->modelo->create($dados);
    }

    public function atualizar(int $id, array $dados): bool
    {
        $cliente = $this->buscarPorId($id);
        return $cliente ? $cliente->update($dados) : false;
    }

    public function excluir(int $id): bool
    {
        $cliente = $this->buscarPorId($id);
        return $cliente ? $cliente->delete() : false;
    }
}
