<?php

namespace App\Repositories\Contratos;

use App\Models\Servico;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface ServicoRepositorioInterface
{
    public function listarTodos(): Collection;
    public function paginar(int $porPagina = 15): LengthAwarePaginator;
    public function buscarPorId(int $id): ?Servico;
    public function buscarPorCodigo(string $codigo): ?Servico;
    public function criar(array $dados): Servico;
    public function atualizar(int $id, array $dados): bool;
    public function excluir(int $id): bool;
}
