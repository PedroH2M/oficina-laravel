<?php

namespace App\Repositories\Contratos;

use App\Models\Peca;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface PecaRepositorioInterface
{
    public function listarTodas(): Collection;
    public function paginar(int $porPagina = 15): LengthAwarePaginator;
    public function buscarPorId(int $id): ?Peca;
    public function buscarPorCodigo(string $codigo): ?Peca;
    public function listarEstoqueBaixo(): Collection;
    public function criar(array $dados): Peca;
    public function atualizar(int $id, array $dados): bool;
    public function excluir(int $id): bool;
    public function debitarEstoque(int $id, int $quantidade): bool;
    public function creditarEstoque(int $id, int $quantidade): bool;
}
