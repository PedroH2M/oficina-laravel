<?php

namespace App\Repositories\Eloquent;

use App\Models\OrdemServico;
use App\Models\ItemOrdemServico;
use App\Repositories\Contratos\OrdemServicoRepositorioInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class OrdemServicoRepositorio implements OrdemServicoRepositorioInterface
{
    protected $modelo;

    public function __construct(OrdemServico $modelo)
    {
        $this->modelo = $modelo;
    }

    public function listarTodas(): Collection
    {
        return $this->modelo->with(['cliente', 'veiculo', 'mecanicoResponsavel'])
            ->orderBy('data_abertura', 'desc')
            ->get();
    }

    public function paginar(int $porPagina = 15): LengthAwarePaginator
    {
        return $this->modelo->with(['cliente', 'veiculo', 'mecanicoResponsavel'])
            ->orderBy('data_abertura', 'desc')
            ->paginate($porPagina);
    }

    public function buscarPorId(int $id): ?OrdemServico
    {
        return $this->modelo->with([
            'cliente',
            'veiculo',
            'orcamento',
            'mecanicoResponsavel',
            'itens.peca',
            'historicos'
        ])->find($id);
    }

    public function buscarPorNumero(string $numero): ?OrdemServico
    {
        return $this->modelo->with(['cliente', 'veiculo', 'itens.peca'])->where('numero', $numero)->first();
    }

    public function listarPorStatus(string $status): Collection
    {
        return $this->modelo->with(['cliente', 'veiculo'])
            ->where('status', $status)
            ->orderBy('data_abertura', 'desc')
            ->get();
    }

    public function listarPorVeiculo(int $veiculoId): Collection
    {
        return $this->modelo->with(['itens.peca'])
            ->where('veiculo_id', $veiculoId)
            ->orderBy('data_abertura', 'desc')
            ->get();
    }

    public function criar(array $dados, array $itens): OrdemServico
    {
        return DB::transaction(function () use ($dados, $itens) {
            $os = $this->modelo->create($dados);

            foreach ($itens as $item) {
                $subtotal = $item['quantidade'] * $item['valor_unitario'];
                $os->itens()->create([
                    'peca_id' => $item['peca_id'] ?? null,
                    'tipo' => $item['tipo'] ?? 'peca',
                    'descricao' => $item['descricao'],
                    'quantidade' => $item['quantidade'],
                    'valor_unitario' => $item['valor_unitario'],
                    'subtotal' => $subtotal,
                ]);
            }

            return $os->fresh(['itens']);
        });
    }

    public function atualizar(int $id, array $dados): bool
    {
        $os = $this->buscarPorId($id);
        return $os ? $os->update($dados) : false;
    }

    public function atualizarStatus(int $id, string $status, ?string $solucao = null): bool
    {
        $os = $this->buscarPorId($id);
        if (!$os) {
            return false;
        }

        $dados = ['status' => $status];
        if ($solucao) {
            $dados['solucao_aplicada'] = $solucao;
        }
        if ($status === 'finalizada') {
            $dados['data_finalizacao'] = now();
        }

        return $os->update($dados);
    }

    public function excluir(int $id): bool
    {
        $os = $this->buscarPorId($id);
        return $os ? $os->delete() : false;
    }
}
