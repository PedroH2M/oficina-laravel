<?php

namespace App\Repositories\Contratos;

use App\Models\Veiculo;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface VeiculoRepositorioInterface
{
    public function listarTodos(): Collection;
    public function paginar(int $porPagina = 15): LengthAwarePaginator;
    public function buscarPorId(int $id): ?Veiculo;
    public function buscarPorPlaca(string $placa): ?Veiculo;
    public function listarPorCliente(int $clienteId): Collection;
    public function criar(array $dados): Veiculo;
    public function atualizar(int $id, array $dados): bool;
    public function excluir(int $id): bool;
    public function atualizarQuilometragem(int $id, int $km): bool;
}
