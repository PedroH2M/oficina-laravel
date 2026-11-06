<?php

namespace App\Services\Contratos;

use App\Models\Usuario;
use Illuminate\Pagination\LengthAwarePaginator;

interface UsuarioServicoInterface
{
    public function paginarUsuarios(int $porPagina = 15): LengthAwarePaginator;
    public function cadastrarUsuario(array $dados): Usuario;
    public function atualizarUsuario(int $id, array $dados): bool;
    public function excluirUsuario(int $id): bool;
    public function obterUsuario(int $id): ?Usuario;
}
