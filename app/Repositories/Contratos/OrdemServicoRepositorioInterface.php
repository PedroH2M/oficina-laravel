<?php

namespace App\Repositories\Contratos;

use App\Models\OrdemServico;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface OrdemServicoRepositorioInterface
{
    public function listarTodas(): Collection;
    public function paginar(int $porPagina = 15): LengthAwarePaginator;
    public function buscarPorId(int $id): ?OrdemServico;
    public function buscarPorNumero(string $numero): ?OrdemServico;
    public function listarPorStatus(string $status): Collection;
    public function listarPorVeiculo(int $veiculoId): Collection;
    public function criar(array $dados, array $itens): OrdemServico;
    public function atualizar(int $id, array $dados): bool;
    public function atualizarStatus(int $id, string $status, ?string $solucao = null): bool;
    public function excluir(int $id): bool;
}
