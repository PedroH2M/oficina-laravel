<?php

namespace App\Services;

use App\Models\Orcamento;
use App\Models\OrdemServico;
use App\Repositories\Contratos\OrcamentoRepositorioInterface;
use App\Repositories\Contratos\OrdemServicoRepositorioInterface;
use App\Repositories\Contratos\HistoricoVeiculoRepositorioInterface;
use App\Repositories\Contratos\PecaRepositorioInterface;
use App\Repositories\Contratos\VeiculoRepositorioInterface;
use App\Services\Contratos\OrcamentoServicoInterface;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use InvalidArgumentException;

class OrcamentoServico implements OrcamentoServicoInterface
{
    protected $orcamentoRepo;
    protected $osRepo;
    protected $historicoRepo;
    protected $pecaRepo;
    protected $veiculoRepo;

    public function __construct(
        OrcamentoRepositorioInterface $orcamentoRepo,
        OrdemServicoRepositorioInterface $osRepo,
        HistoricoVeiculoRepositorioInterface $historicoRepo,
        PecaRepositorioInterface $pecaRepo,
        VeiculoRepositorioInterface $veiculoRepo = null
    ) {
        $this->orcamentoRepo = $orcamentoRepo;
        $this->osRepo = $osRepo;
        $this->historicoRepo = $historicoRepo;
        $this->pecaRepo = $pecaRepo;
        $this->veiculoRepo = $veiculoRepo;
    }

    public function paginarOrcamentos(int $porPagina = 15): LengthAwarePaginator
    {
        return $this->orcamentoRepo->paginar($porPagina);
    }

    public function criarOrcamento(array $dados, array $itens): Orcamento
    {
        if (empty($itens)) {
            throw new InvalidArgumentException('O orçamento precisa conter pelo menos um item ou serviço.');
        }

        // Gerar número de orçamento amigável e único (ex: ORC-2026-XXXX)
        $dados['numero'] = 'ORC-' . date('Y') . '-' . strtoupper(Str::random(6));
        $dados['status'] = 'pendente';

        // Calcular totais
        $totalPecas = 0.00;
        $totalServicos = 0.00;

        foreach ($itens as $item) {
            $subtotal = $item['quantidade'] * $item['valor_unitario'];
            if (($item['tipo'] ?? 'peca') === 'peca') {
                $totalPecas += $subtotal;
            } else {
                $totalServicos += $subtotal;
            }
        }

        $desconto = $dados['valor_desconto'] ?? 0.00;
        $dados['valor_pecas'] = $totalPecas;
        $dados['valor_servicos'] = $totalServicos;
        $dados['valor_total'] = max(0, ($totalPecas + $totalServicos) - $desconto);

        return $this->orcamentoRepo->criar($dados, $itens);
    }

    public function aprovarOrcamento(int $orcamentoId): bool
    {
        return $this->orcamentoRepo->atualizarStatus($orcamentoId, 'aprovado');
    }

    public function rejeitarOrcamento(int $orcamentoId): bool
    {
        return $this->orcamentoRepo->atualizarStatus($orcamentoId, 'rejeitado');
    }

    public function converterEmOrdemServico(int $orcamentoId, int $quilometragemEntrada, ?int $mecanicoId = null): OrdemServico
    {
        return DB::transaction(function () use ($orcamentoId, $quilometragemEntrada, $mecanicoId) {
            $orcamento = $this->orcamentoRepo->buscarPorId($orcamentoId);
            if (!$orcamento) {
                throw new InvalidArgumentException('Orçamento não encontrado.');
            }

            // Criar dados da OS a partir do orçamento
            $dadosOS = [
                'numero' => 'OS-' . date('Y') . '-' . strtoupper(Str::random(6)),
                'cliente_id' => $orcamento->cliente_id,
                'veiculo_id' => $orcamento->veiculo_id,
                'orcamento_id' => $orcamento->id,
                'usuario_mecanico_id' => $mecanicoId,
                'status' => 'aberta',
                'data_abertura' => now(),
                'data_previsao' => now()->addDays(3),
                'quilometragem_entrada' => $quilometragemEntrada,
                'diagnostico_defeito' => 'Originado do Orçamento: ' . $orcamento->numero . '. ' . $orcamento->observacoes,
                'valor_pecas' => $orcamento->valor_pecas,
                'valor_servicos' => $orcamento->valor_servicos,
                'valor_desconto' => $orcamento->valor_desconto,
                'valor_total' => $orcamento->valor_total,
            ];

            // 1. Verificar disponibilidade de estoque antes de debitar
            foreach ($orcamento->itens as $item) {
                if ($item->tipo === 'peca' && !empty($item->peca_id)) {
                    $peca = $this->pecaRepo->buscarPorId($item->peca_id);
                    if ($peca && $peca->quantidade_estoque < $item->quantidade) {
                        throw new InvalidArgumentException("Estoque insuficiente para a peça '{$peca->nome}' (Disponível em estoque: {$peca->quantidade_estoque}, Solicitado: {$item->quantidade}).");
                    }
                }
            }

            // 2. Debitar estoque das peças
            foreach ($orcamento->itens as $item) {
                if ($item->tipo === 'peca' && !empty($item->peca_id)) {
                    $this->pecaRepo->debitarEstoque($item->peca_id, $item->quantidade);
                }
            }

            $itensOS = [];
            foreach ($orcamento->itens as $item) {
                $itensOS[] = [
                    'peca_id' => $item->peca_id,
                    'tipo' => $item->tipo,
                    'descricao' => $item->descricao,
                    'quantidade' => $item->quantidade,
                    'valor_unitario' => $item->valor_unitario,
                ];
            }

            $ordemServico = $this->osRepo->criar($dadosOS, $itensOS);

            // Atualizar quilometragem do veículo se disponível
            if ($this->veiculoRepo && !empty($quilometragemEntrada)) {
                $this->veiculoRepo->atualizarQuilometragem($orcamento->veiculo_id, $quilometragemEntrada);
            }

            // Atualizar status do orçamento para convertido
            $this->orcamentoRepo->atualizarStatus($orcamentoId, 'convertido_os');

            // Registrar no Histórico do Veículo
            $this->historicoRepo->registrarEvento([
                'veiculo_id' => $orcamento->veiculo_id,
                'ordem_servico_id' => $ordemServico->id,
                'data_evento' => now(),
                'quilometragem_registrada' => $quilometragemEntrada,
                'tipo_evento' => 'abertura_os',
                'descricao' => "Abertura de Ordem de Serviço {$ordemServico->numero} baseada no orçamento {$orcamento->numero}.",
            ]);

            return $ordemServico;
        });
    }

    public function obterOrcamento(int $id): ?Orcamento
    {
        return $this->orcamentoRepo->buscarPorId($id);
    }
}
