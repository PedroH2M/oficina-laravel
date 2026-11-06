<?php

namespace App\Repositories\Eloquent;

use App\Models\Peca;
use App\Repositories\Contratos\PecaRepositorioInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class PecaRepositorio implements PecaRepositorioInterface
{
    protected $modelo;

    public function __construct(Peca $modelo)
    {
        $this->modelo = $modelo;
    }

    public function listarTodas(): Collection
    {
        return $this->modelo->orderBy('nome')->get();
    }

    public function paginar(int $porPagina = 15): LengthAwarePaginator
    {
        return $this->modelo->orderBy('nome')->paginate($porPagina);
    }

    public function buscarPorId(int $id): ?Peca
    {
        return $this->modelo->find($id);
    }

    public function buscarPorCodigo(string $codigo): ?Peca
    {
        return $this->modelo->where('codigo', $codigo)->first();
    }

    public function listarEstoqueBaixo(): Collection
    {
        return $this->modelo->whereColumn('quantidade_estoque', '<=', 'estoque_minimo')->get();
    }

    public function criar(array $dados): Peca
    {
        return $this->modelo->create($dados);
    }

    public function atualizar(int $id, array $dados): bool
    {
        $peca = $this->buscarPorId($id);
        return $peca ? $peca->update($dados) : false;
    }

    public function excluir(int $id): bool
    {
        $peca = $this->buscarPorId($id);
        return $peca ? $peca->delete() : false;
    }

    public function debitarEstoque(int $id, int $quantidade): bool
    {
        $peca = $this->buscarPorId($id);
        if ($peca && $peca->quantidade_estoque >= $quantidade) {
            return $peca->decrement('quantidade_estoque', $quantidade);
        }
        return false;
    }

    public function creditarEstoque(int $id, int $quantidade): bool
    {
        $peca = $this->buscarPorId($id);
        return $peca ? (bool) $peca->increment('quantidade_estoque', $quantidade) : false;
    }
}
