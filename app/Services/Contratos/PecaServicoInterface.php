<?php

namespace App\Services\Contratos;

use App\Models\Peca;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface PecaServicoInterface
{
    public function paginarPecas(int $porPagina = 15): LengthAwarePaginator;
    public function cadastrarPeca(array $dados): Peca;
    public function atualizarPeca(int $id, array $dados): bool;
    public function excluirPeca(int $id): bool;
    public function obterPeca(int $id): ?Peca;
    public function listarAlertasEstoque(): Collection;
    public function validarDisponibilidade(int $pecaId, int $quantidadeDesejada): bool;
}
