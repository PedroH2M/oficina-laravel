<?php

namespace App\Services\Contratos;

use App\Models\Veiculo;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface VeiculoServicoInterface
{
    public function paginarVeiculos(int $porPagina = 15): LengthAwarePaginator;
    public function cadastrarVeiculo(array $dados): Veiculo;
    public function atualizarVeiculo(int $id, array $dados): bool;
    public function obterHistoricoCompleto(int $veiculoId): array;
    public function obterVeiculo(int $id): ?Veiculo;
}
