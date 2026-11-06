<?php

namespace App\Repositories\Eloquent;

use App\Models\Usuario;
use App\Repositories\Contratos\UsuarioRepositorioInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class UsuarioRepositorio implements UsuarioRepositorioInterface
{
    protected $modelo;

    public function __construct(Usuario $modelo)
    {
        $this->modelo = $modelo;
    }

    public function listarTodos(): Collection
    {
        return $this->modelo->orderBy('nome')->get();
    }

    public function paginar(int $porPagina = 15): LengthAwarePaginator
    {
        return $this->modelo->orderBy('nome')->paginate($porPagina);
    }

    public function buscarPorId(int $id): ?Usuario
    {
        return $this->modelo->find($id);
    }

    public function buscarPorEmail(string $email): ?Usuario
    {
        return $this->modelo->where('email', $email)->first();
    }

    public function criar(array $dados): Usuario
    {
        return $this->modelo->create($dados);
    }

    public function atualizar(int $id, array $dados): bool
    {
        $usuario = $this->buscarPorId($id);
        return $usuario ? $usuario->update($dados) : false;
    }

    public function excluir(int $id): bool
    {
        $usuario = $this->buscarPorId($id);
        return $usuario ? $usuario->delete() : false;
    }
}
