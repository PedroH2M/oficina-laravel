<?php

namespace App\Repositories\Contratos;

use App\Models\Cliente;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface ClienteRepositorioInterface
{
    public function listarTodos(): Collection;
    public function paginar(int $porPagina = 15): LengthAwarePaginator;
    public function buscarPorId(int $id): ?Cliente;
    public function buscarPorCpfCnpj(string $cpfCnpj): ?Cliente;
    public function criar(array $dados): Cliente;
    public function atualizar(int $id, array $dados): bool;
    public function excluir(int $id): bool;
}
