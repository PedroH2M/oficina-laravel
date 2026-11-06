<?php

namespace App\Services;

use App\Models\Servico;
use App\Repositories\Contratos\ServicoRepositorioInterface;
use App\Services\Contratos\ServicoServicoInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class ServicoServico implements ServicoServicoInterface
{
    protected $servicoRepo;

    public function __construct(ServicoRepositorioInterface $servicoRepo)
    {
        $this->servicoRepo = $servicoRepo;
    }

    public function paginarServicos(int $porPagina = 15): LengthAwarePaginator
    {
        return $this->servicoRepo->paginar($porPagina);
    }

    public function listarTodos(): Collection
    {
        return $this->servicoRepo->listarTodos();
    }

    public function cadastrarServico(array $dados): Servico
    {
        return $this->servicoRepo->criar($dados);
    }

    public function atualizarServico(int $id, array $dados): bool
    {
        return $this->servicoRepo->atualizar($id, $dados);
    }

    public function excluirServico(int $id): bool
    {
        return $this->servicoRepo->excluir($id);
    }

    public function obterServico(int $id): ?Servico
    {
        return $this->servicoRepo->buscarPorId($id);
    }
}
