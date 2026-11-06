<?php

namespace App\Repositories\Contratos;

use App\Models\Usuario;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface UsuarioRepositorioInterface
{
    public function listarTodos(): Collection;
    public function paginar(int $porPagina = 15): LengthAwarePaginator;
    public function buscarPorId(int $id): ?Usuario;
    public function buscarPorEmail(string $email): ?Usuario;
    public function criar(array $dados): Usuario;
    public function atualizar(int $id, array $dados): bool;
    public function excluir(int $id): bool;
}
