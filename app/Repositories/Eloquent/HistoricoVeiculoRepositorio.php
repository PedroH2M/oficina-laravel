<?php

namespace App\Repositories\Eloquent;

use App\Models\HistoricoVeiculo;
use App\Repositories\Contratos\HistoricoVeiculoRepositorioInterface;
use Illuminate\Database\Eloquent\Collection;

class HistoricoVeiculoRepositorio implements HistoricoVeiculoRepositorioInterface
{
    protected $modelo;

    public function __construct(HistoricoVeiculo $modelo)
    {
        $this->modelo = $modelo;
    }

    public function listarPorVeiculo(int $veiculoId): Collection
    {
        return $this->modelo->with(['ordemServico'])
            ->where('veiculo_id', $veiculoId)
            ->orderBy('data_evento', 'desc')
            ->get();
    }

    public function registrarEvento(array $dados): HistoricoVeiculo
    {
        if (!isset($dados['data_evento'])) {
            $dados['data_evento'] = now();
        }
        return $this->modelo->create($dados);
    }
}
