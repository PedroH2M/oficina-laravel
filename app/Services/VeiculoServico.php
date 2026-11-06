<?php

namespace App\Services;

use App\Models\Veiculo;
use App\Repositories\Contratos\VeiculoRepositorioInterface;
use App\Repositories\Contratos\HistoricoVeiculoRepositorioInterface;
use App\Services\Contratos\VeiculoServicoInterface;
use Illuminate\Pagination\LengthAwarePaginator;

class VeiculoServico implements VeiculoServicoInterface
{
    protected $veiculoRepo;
    protected $historicoRepo;

    public function __construct(
        VeiculoRepositorioInterface $veiculoRepo,
        HistoricoVeiculoRepositorioInterface $historicoRepo
    ) {
        $this->veiculoRepo = $veiculoRepo;
        $this->historicoRepo = $historicoRepo;
    }

    public function paginarVeiculos(int $porPagina = 15): LengthAwarePaginator
    {
        return $this->veiculoRepo->paginar($porPagina);
    }

    public function cadastrarVeiculo(array $dados): Veiculo
    {
        $veiculo = $this->veiculoRepo->criar($dados);

        // Registrar evento inicial no histórico
        $this->historicoRepo->registrarEvento([
            'veiculo_id' => $veiculo->id,
            'data_evento' => now(),
            'quilometragem_registrada' => $veiculo->quilometragem_atual,
            'tipo_evento' => 'cadastro_inicial',
            'descricao' => "Veículo cadastrado no sistema com {$veiculo->quilometragem_atual} km.",
        ]);

        return $veiculo;
    }

    public function atualizarVeiculo(int $id, array $dados): bool
    {
        return $this->veiculoRepo->atualizar($id, $dados);
    }

    public function obterHistoricoCompleto(int $veiculoId): array
    {
        $veiculo = $this->veiculoRepo->buscarPorId($veiculoId);
        $historicos = $this->historicoRepo->listarPorVeiculo($veiculoId);

        return [
            'veiculo' => $veiculo,
            'eventos' => $historicos,
        ];
    }

    public function obterVeiculo(int $id): ?Veiculo
    {
        return $this->veiculoRepo->buscarPorId($id);
    }
}
