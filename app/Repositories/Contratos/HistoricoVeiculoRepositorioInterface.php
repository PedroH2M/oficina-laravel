<?php

namespace App\Repositories\Contratos;

use App\Models\HistoricoVeiculo;
use Illuminate\Database\Eloquent\Collection;

interface HistoricoVeiculoRepositorioInterface
{
    public function listarPorVeiculo(int $veiculoId): Collection;
    public function registrarEvento(array $dados): HistoricoVeiculo;
}
