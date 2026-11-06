<?php

namespace App\Services;

use App\Models\OrdemServico;
use App\Repositories\Contratos\OrdemServicoRepositorioInterface;
use App\Repositories\Contratos\VeiculoRepositorioInterface;
use App\Repositories\Contratos\PecaRepositorioInterface;
use App\Repositories\Contratos\HistoricoVeiculoRepositorioInterface;
use App\Services\Contratos\OrdemServicoServicoInterface;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;
use InvalidArgumentException;

class OrdemServicoServico implements OrdemServicoServicoInterface
{
    protected $osRepo;
    protected $veiculoRepo;
    protected $pecaRepo;
    protected $historicoRepo;

    public function __construct(
        OrdemServicoRepositorioInterface $osRepo,
        VeiculoRepositorioInterface $veiculoRepo,
        PecaRepositorioInterface $pecaRepo,
        HistoricoVeiculoRepositorioInterface $historicoRepo
    ) {
        $this->osRepo = $osRepo;
        $this->veiculoRepo = $veiculoRepo;
        $this->pecaRepo = $pecaRepo;
        $this->historicoRepo = $historicoRepo;
    }

    public function paginarOrdensServico(int $porPagina = 15): LengthAwarePaginator
    {
        return $this->osRepo->paginar($porPagina);
    }

    public function abrirOrdemServico(array $dados, array $itens): OrdemServico
    {
        if (empty($itens)) {
            throw new InvalidArgumentException('A Ordem de Serviço precisa conter pelo menos um item ou serviço.');
        }

        $dados['numero'] = 'OS-' . date('Y') . '-' . strtoupper(Str::random(6));
        $dados['status'] = 'aberta';
        $dados['data_abertura'] = now();

        // Calcular totais
        $totalPecas = 0.00;
        $totalServicos = 0.00;

        foreach ($itens as $item) {
            $subtotal = $item['quantidade'] * $item['valor_unitario'];
            if (($item['tipo'] ?? 'peca') === 'peca') {
                $totalPecas += $subtotal;

                // Baixar estoque se a peça for informada
                if (!empty($item['peca_id'])) {
                    $peca = $this->pecaRepo->buscarPorId($item['peca_id']);
                    if ($peca && $peca->quantidade_estoque < $item['quantidade']) {
                        throw new InvalidArgumentException("Estoque insuficiente para a peça '{$peca->nome}' (Disponível em estoque: {$peca->quantidade_estoque}, Solicitado: {$item['quantidade']}).");
                    }
                    $this->pecaRepo->debitarEstoque($item['peca_id'], $item['quantidade']);
                }
            } else {
                $totalServicos += $subtotal;
            }
        }

        $desconto = $dados['valor_desconto'] ?? 0.00;
        $dados['valor_pecas'] = $totalPecas;
        $dados['valor_servicos'] = $totalServicos;
        $dados['valor_total'] = max(0, ($totalPecas + $totalServicos) - $desconto);

        // Criar OS
        $ordemServico = $this->osRepo->criar($dados, $itens);

        // Atualizar quilometragem do veículo
        if (!empty($dados['quilometragem_entrada'])) {
            $this->veiculoRepo->atualizarQuilometragem($dados['veiculo_id'], $dados['quilometragem_entrada']);
        }

        // Registrar no Histórico do Veículo
        $this->historicoRepo->registrarEvento([
            'veiculo_id' => $dados['veiculo_id'],
            'ordem_servico_id' => $ordemServico->id,
            'data_evento' => now(),
            'quilometragem_registrada' => $dados['quilometragem_entrada'],
            'tipo_evento' => 'abertura_os',
            'descricao' => "Abertura de O.S. {$ordemServico->numero}. Diagnóstico inicial: {$dados['diagnostico_defeito']}",
        ]);

        return $ordemServico;
    }

    public function atualizarStatus(int $osId, string $novoStatus, ?string $solucao = null): bool
    {
        $os = $this->osRepo->buscarPorId($osId);
        if (!$os) {
            return false;
        }

        $atualizado = $this->osRepo->atualizarStatus($osId, $novoStatus, $solucao);

        if ($atualizado) {
            $this->historicoRepo->registrarEvento([
                'veiculo_id' => $os->veiculo_id,
                'ordem_servico_id' => $os->id,
                'data_evento' => now(),
                'quilometragem_registrada' => $os->quilometragem_entrada,
                'tipo_evento' => 'mudanca_status_os',
                'descricao' => "Status da O.S. {$os->numero} alterado de '{$os->status}' para '{$novoStatus}'.",
            ]);
        }

        return $atualizado;
    }

    public function finalizarOrdemServico(int $osId, string $solucaoAplicada): bool
    {
        $os = $this->osRepo->buscarPorId($osId);
        if (!$os) {
            return false;
        }

        $sucesso = $this->osRepo->atualizarStatus($osId, 'finalizada', $solucaoAplicada);

        if ($sucesso) {
            $this->historicoRepo->registrarEvento([
                'veiculo_id' => $os->veiculo_id,
                'ordem_servico_id' => $os->id,
                'data_evento' => now(),
                'quilometragem_registrada' => $os->quilometragem_entrada,
                'tipo_evento' => 'conclusao_os',
                'descricao' => "O.S. {$os->numero} concluída e entregue. Solução aplicada: {$solucaoAplicada}",
            ]);
        }

        return $sucesso;
    }

    public function cancelarOrdemServico(int $osId, string $motivo): bool
    {
        $os = $this->osRepo->buscarPorId($osId);
        if (!$os) {
            return false;
        }

        // Estornar itens de estoque
        foreach ($os->itens as $item) {
            if ($item->tipo === 'peca' && $item->peca_id) {
                $this->pecaRepo->creditarEstoque($item->peca_id, $item->quantidade);
            }
        }

        $sucesso = $this->osRepo->atualizarStatus($osId, 'cancelada', 'Cancelamento: ' . $motivo);

        if ($sucesso) {
            $this->historicoRepo->registrarEvento([
                'veiculo_id' => $os->veiculo_id,
                'ordem_servico_id' => $os->id,
                'data_evento' => now(),
                'quilometragem_registrada' => $os->quilometragem_entrada,
                'tipo_evento' => 'cancelamento_os',
                'descricao' => "O.S. {$os->numero} cancelada. Motivo: {$motivo}",
            ]);
        }

        return $sucesso;
    }

    public function obterOrdemServico(int $id): ?OrdemServico
    {
        return $this->osRepo->buscarPorId($id);
    }
}
