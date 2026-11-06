<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

// Contratos de Repositório
use App\Repositories\Contratos\ClienteRepositorioInterface;
use App\Repositories\Contratos\VeiculoRepositorioInterface;
use App\Repositories\Contratos\PecaRepositorioInterface;
use App\Repositories\Contratos\ServicoRepositorioInterface;
use App\Repositories\Contratos\OrcamentoRepositorioInterface;
use App\Repositories\Contratos\OrdemServicoRepositorioInterface;
use App\Repositories\Contratos\HistoricoVeiculoRepositorioInterface;
use App\Repositories\Contratos\UsuarioRepositorioInterface;

// Implementações Eloquent
use App\Repositories\Eloquent\ClienteRepositorio;
use App\Repositories\Eloquent\VeiculoRepositorio;
use App\Repositories\Eloquent\PecaRepositorio;
use App\Repositories\Eloquent\ServicoRepositorio;
use App\Repositories\Eloquent\OrcamentoRepositorio;
use App\Repositories\Eloquent\OrdemServicoRepositorio;
use App\Repositories\Eloquent\HistoricoVeiculoRepositorio;
use App\Repositories\Eloquent\UsuarioRepositorio;

// Contratos de Serviços
use App\Services\Contratos\ClienteServicoInterface;
use App\Services\Contratos\VeiculoServicoInterface;
use App\Services\Contratos\PecaServicoInterface;
use App\Services\Contratos\ServicoServicoInterface;
use App\Services\Contratos\OrcamentoServicoInterface;
use App\Services\Contratos\OrdemServicoServicoInterface;
use App\Services\Contratos\UsuarioServicoInterface;

// Implementações de Serviços
use App\Services\ClienteServico;
use App\Services\VeiculoServico;
use App\Services\PecaServico;
use App\Services\ServicoServico;
use App\Services\OrcamentoServico;
use App\Services\OrdemServicoServico;
use App\Services\UsuarioServico;

class RepositorioServiceProvider extends ServiceProvider
{
    public function register()
    {
        // Bindings de Repositórios (Inversão de Dependência)
        $this->app->bind(ClienteRepositorioInterface::class, ClienteRepositorio::class);
        $this->app->bind(VeiculoRepositorioInterface::class, VeiculoRepositorio::class);
        $this->app->bind(PecaRepositorioInterface::class, PecaRepositorio::class);
        $this->app->bind(ServicoRepositorioInterface::class, ServicoRepositorio::class);
        $this->app->bind(OrcamentoRepositorioInterface::class, OrcamentoRepositorio::class);
        $this->app->bind(OrdemServicoRepositorioInterface::class, OrdemServicoRepositorio::class);
        $this->app->bind(HistoricoVeiculoRepositorioInterface::class, HistoricoVeiculoRepositorio::class);
        $this->app->bind(UsuarioRepositorioInterface::class, UsuarioRepositorio::class);

        // Bindings de Serviços de Domínio (SOLID - Camada de Negócio)
        $this->app->bind(ClienteServicoInterface::class, ClienteServico::class);
        $this->app->bind(VeiculoServicoInterface::class, VeiculoServico::class);
        $this->app->bind(PecaServicoInterface::class, PecaServico::class);
        $this->app->bind(ServicoServicoInterface::class, ServicoServico::class);
        $this->app->bind(OrcamentoServicoInterface::class, OrcamentoServico::class);
        $this->app->bind(OrdemServicoServicoInterface::class, OrdemServicoServico::class);
        $this->app->bind(UsuarioServicoInterface::class, UsuarioServico::class);
    }

    public function boot()
    {
        //
    }
}
