<?php

namespace App\Services;

use App\Models\Peca;
use App\Repositories\Contratos\PecaRepositorioInterface;
use App\Services\Contratos\PecaServicoInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class PecaServico implements PecaServicoInterface
{
    protected $pecaRepo;

    public function __construct(PecaRepositorioInterface $pecaRepo)
    {
        $this->pecaRepo = $pecaRepo;
    }

    public function paginarPecas(int $porPagina = 15): LengthAwarePaginator
    {
        return $this->pecaRepo->paginar($porPagina);
    }

    public function cadastrarPeca(array $dados): Peca
    {
        return $this->pecaRepo->criar($dados);
    }

    public function atualizarPeca(int $id, array $dados): bool
    {
        return $this->pecaRepo->atualizar($id, $dados);
    }

    public function excluirPeca(int $id): bool
    {
        return $this->pecaRepo->excluir($id);
    }

    public function obterPeca(int $id): ?Peca
    {
        return $this->pecaRepo->buscarPorId($id);
    }

    public function listarAlertasEstoque(): Collection
    {
        return $this->pecaRepo->listarEstoqueBaixo();
    }

    public function validarDisponibilidade(int $pecaId, int $quantidadeDesejada): bool
    {
        $peca = $this->pecaRepo->buscarPorId($pecaId);
        return $peca && $peca->quantidade_estoque >= $quantidadeDesejada;
    }
}
