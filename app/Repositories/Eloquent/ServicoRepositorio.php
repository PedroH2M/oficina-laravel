<?php

namespace App\Repositories\Eloquent;

use App\Models\Servico;
use App\Repositories\Contratos\ServicoRepositorioInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class ServicoRepositorio implements ServicoRepositorioInterface
{
    protected $modelo;

    public function __construct(Servico $modelo)
    {
        $this->modelo = $modelo;
    }

    public function listarTodos(): Collection
    {
        return $this->modelo->orderBy('nome')->get();
    }

    public function paginar(int $porPagina = 15): LengthAwarePaginator
    {
        return $this->modelo->orderBy('nome')->paginate($porPagina);
    }

    public function buscarPorId(int $id): ?Servico
    {
        return $this->modelo->find($id);
    }

    public function buscarPorCodigo(string $codigo): ?Servico
    {
        return $this->modelo->where('codigo', $codigo)->first();
    }

    public function criar(array $dados): Servico
    {
        return $this->modelo->create($dados);
    }

    public function atualizar(int $id, array $dados): bool
    {
        $servico = $this->buscarPorId($id);
        return $servico ? $servico->update($dados) : false;
    }

    public function excluir(int $id): bool
    {
        $servico = $this->buscarPorId($id);
        return $servico ? $servico->delete() : false;
    }
}
