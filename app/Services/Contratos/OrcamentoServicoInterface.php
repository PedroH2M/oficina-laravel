<?php

namespace App\Services\Contratos;

use App\Models\Orcamento;
use App\Models\OrdemServico;
use Illuminate\Pagination\LengthAwarePaginator;

interface OrcamentoServicoInterface
{
    public function paginarOrcamentos(int $porPagina = 15): LengthAwarePaginator;
    public function criarOrcamento(array $dados, array $itens): Orcamento;
    public function aprovarOrcamento(int $orcamentoId): bool;
    public function rejeitarOrcamento(int $orcamentoId): bool;
    public function converterEmOrdemServico(int $orcamentoId, int $quilometragemEntrada, ?int $mecanicoId = null): OrdemServico;
    public function obterOrcamento(int $id): ?Orcamento;
}
