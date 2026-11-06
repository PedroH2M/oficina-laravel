<?php

namespace App\Repositories\Contratos;

use App\Models\Orcamento;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface OrcamentoRepositorioInterface
{
    public function listarTodos(): Collection;
    public function paginar(int $porPagina = 15): LengthAwarePaginator;
    public function buscarPorId(int $id): ?Orcamento;
    public function buscarPorNumero(string $numero): ?Orcamento;
    public function criar(array $dados, array $itens): Orcamento;
    public function atualizarStatus(int $id, string $status): bool;
    public function excluir(int $id): bool;
}
