<?php

namespace App\Repositories\Eloquent;

use App\Models\Veiculo;
use App\Repositories\Contratos\VeiculoRepositorioInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class VeiculoRepositorio implements VeiculoRepositorioInterface
{
    protected $modelo;

    public function __construct(Veiculo $modelo)
    {
        $this->modelo = $modelo;
    }

    public function listarTodos(): Collection
    {
        return $this->modelo->with('cliente')->orderBy('marca')->get();
    }

    public function paginar(int $porPagina = 15): LengthAwarePaginator
    {
        return $this->modelo->with('cliente')->orderBy('marca')->paginate($porPagina);
    }

    public function buscarPorId(int $id): ?Veiculo
    {
        return $this->modelo->with(['cliente', 'historicos', 'ordensServico'])->find($id);
    }

    public function buscarPorPlaca(string $placa): ?Veiculo
    {
        return $this->modelo->where('placa', strtoupper(trim($placa)))->first();
    }

    public function listarPorCliente(int $clienteId): Collection
    {
        return $this->modelo->where('cliente_id', $clienteId)->get();
    }

    public function criar(array $dados): Veiculo
    {
        $dados['placa'] = strtoupper(trim($dados['placa']));
        return $this->modelo->create($dados);
    }

    public function atualizar(int $id, array $dados): bool
    {
        $veiculo = $this->buscarPorId($id);
        if (!$veiculo) {
            return false;
        }
        if (isset($dados['placa'])) {
            $dados['placa'] = strtoupper(trim($dados['placa']));
        }
        return $veiculo->update($dados);
    }

    public function excluir(int $id): bool
    {
        $veiculo = $this->buscarPorId($id);
        return $veiculo ? $veiculo->delete() : false;
    }

    public function atualizarQuilometragem(int $id, int $km): bool
    {
        $veiculo = $this->buscarPorId($id);
        if ($veiculo && $km > $veiculo->quilometragem_atual) {
            return $veiculo->update(['quilometragem_atual' => $km]);
        }
        return false;
    }
}
