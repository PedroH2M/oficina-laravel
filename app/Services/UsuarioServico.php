<?php

namespace App\Services;

use App\Models\Usuario;
use App\Repositories\Contratos\UsuarioRepositorioInterface;
use App\Services\Contratos\UsuarioServicoInterface;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Hash;

class UsuarioServico implements UsuarioServicoInterface
{
    protected $usuarioRepo;

    public function __construct(UsuarioRepositorioInterface $usuarioRepo)
    {
        $this->usuarioRepo = $usuarioRepo;
    }

    public function paginarUsuarios(int $porPagina = 15): LengthAwarePaginator
    {
        return $this->usuarioRepo->paginar($porPagina);
    }

    public function cadastrarUsuario(array $dados): Usuario
    {
        if (!empty($dados['senha'])) {
            $dados['senha'] = Hash::make($dados['senha']);
        }
        return $this->usuarioRepo->criar($dados);
    }

    public function atualizarUsuario(int $id, array $dados): bool
    {
        if (!empty($dados['senha'])) {
            $dados['senha'] = Hash::make($dados['senha']);
        } else {
            unset($dados['senha']);
        }
        return $this->usuarioRepo->atualizar($id, $dados);
    }

    public function excluirUsuario(int $id): bool
    {
        return $this->usuarioRepo->excluir($id);
    }

    public function obterUsuario(int $id): ?Usuario
    {
        return $this->usuarioRepo->buscarPorId($id);
    }
}
