<?php

namespace App\Services\Contratos;

use App\Models\OrdemServico;
use Illuminate\Pagination\LengthAwarePaginator;

interface OrdemServicoServicoInterface
{
    public function paginarOrdensServico(int $porPagina = 15): LengthAwarePaginator;
    public function abrirOrdemServico(array $dados, array $itens): OrdemServico;
    public function atualizarStatus(int $osId, string $novoStatus, ?string $solucao = null): bool;
    public function finalizarOrdemServico(int $osId, string $solucaoAplicada): bool;
    public function cancelarOrdemServico(int $osId, string $motivo): bool;
    public function obterOrdemServico(int $id): ?OrdemServico;
}
