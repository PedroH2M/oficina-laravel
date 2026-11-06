<?php

namespace App\Services\Contratos;

use App\Models\Servico;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface ServicoServicoInterface
{
    public function paginarServicos(int $porPagina = 15): LengthAwarePaginator;
    public function listarTodos(): Collection;
    public function cadastrarServico(array $dados): Servico;
    public function atualizarServico(int $id, array $dados): bool;
    public function excluirServico(int $id): bool;
    public function obterServico(int $id): ?Servico;
}
