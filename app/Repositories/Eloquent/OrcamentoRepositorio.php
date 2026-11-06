<?php

namespace App\Repositories\Eloquent;

use App\Models\Orcamento;
use App\Models\ItemOrcamento;
use App\Repositories\Contratos\OrcamentoRepositorioInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class OrcamentoRepositorio implements OrcamentoRepositorioInterface
{
    protected $modelo;

    public function __construct(Orcamento $modelo)
    {
        $this->modelo = $modelo;
    }

    public function listarTodos(): Collection
    {
        return $this->modelo->with(['cliente', 'veiculo', 'usuario'])->orderBy('created_at', 'desc')->get();
    }

    public function paginar(int $porPagina = 15): LengthAwarePaginator
    {
        return $this->modelo->with(['cliente', 'veiculo', 'usuario'])->orderBy('created_at', 'desc')->paginate($porPagina);
    }

    public function buscarPorId(int $id): ?Orcamento
    {
        return $this->modelo->with(['cliente', 'veiculo', 'usuario', 'itens.peca'])->find($id);
    }

    public function buscarPorNumero(string $numero): ?Orcamento
    {
        return $this->modelo->with(['cliente', 'veiculo', 'usuario', 'itens.peca'])->where('numero', $numero)->first();
    }

    public function criar(array $dados, array $itens): Orcamento
    {
        return DB::transaction(function () use ($dados, $itens) {
            $orcamento = $this->modelo->create($dados);

            foreach ($itens as $item) {
                $subtotal = $item['quantidade'] * $item['valor_unitario'];
                $orcamento->itens()->create([
                    'peca_id' => $item['peca_id'] ?? null,
                    'tipo' => $item['tipo'] ?? 'peca',
                    'descricao' => $item['descricao'],
                    'quantidade' => $item['quantidade'],
                    'valor_unitario' => $item['valor_unitario'],
                    'subtotal' => $subtotal,
                ]);
            }

            return $orcamento->fresh(['itens']);
        });
    }

    public function atualizarStatus(int $id, string $status): bool
    {
        $orcamento = $this->buscarPorId($id);
        return $orcamento ? $orcamento->update(['status' => $status]) : false;
    }

    public function excluir(int $id): bool
    {
        $orcamento = $this->buscarPorId($id);
        return $orcamento ? $orcamento->delete() : false;
    }
}
