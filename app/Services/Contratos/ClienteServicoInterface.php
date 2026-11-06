<?php

namespace App\Services\Contratos;

use App\Models\Cliente;
use Illuminate\Pagination\LengthAwarePaginator;

interface ClienteServicoInterface
{
    public function paginarClientes(int $porPagina = 15): LengthAwarePaginator;
    public function cadastrarCliente(array $dados): Cliente;
    public function atualizarCliente(int $id, array $dados): bool;
    public function excluirCliente(int $id): bool;
    public function obterCliente(int $id): ?Cliente;
}
