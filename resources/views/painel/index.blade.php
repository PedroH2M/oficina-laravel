@extends('layouts.app')

@section('titulo', 'Painel de Controle')

@section('conteudo')
<div class="row">
    <div class="col-12 mb-3">
        <h4 class="font-weight-bold text-dark"><i class="fas fa-tachometer-alt mr-2 text-warning"></i>Painel da Oficina</h4>
        <p class="text-muted small">Visão geral em tempo real da oficina mecânica, ordens de serviço e alertas de estoque.</p>
    </div>
</div>

<div class="row">
    <!-- Card Clientes -->
    <div class="col-md-3">
        <div class="card bg-primary text-white">
            <div class="card-body d-flex align-items-center justify-content-between py-3">
                <div>
                    <h6 class="text-uppercase small mb-1 font-weight-bold">Clientes Ativos</h6>
                    <h3 class="mb-0 font-weight-bold">{{ $totalClientes }}</h3>
                </div>
                <i class="fas fa-users fa-2x opacity-75"></i>
            </div>
            <a href="{{ route('clientes.index') }}" class="card-footer text-white small d-flex justify-content-between align-items-center py-2" style="background: rgba(0,0,0,0.1);">
                <span>Ver Clientes</span>
                <i class="fas fa-chevron-right"></i>
            </a>
        </div>
    </div>

    <!-- Card Veículos -->
    <div class="col-md-3">
        <div class="card bg-info text-white">
            <div class="card-body d-flex align-items-center justify-content-between py-3">
                <div>
                    <h6 class="text-uppercase small mb-1 font-weight-bold">Veículos</h6>
                    <h3 class="mb-0 font-weight-bold">{{ $totalVeiculos }}</h3>
                </div>
                <i class="fas fa-car fa-2x opacity-75"></i>
            </div>
            <a href="{{ route('veiculos.index') }}" class="card-footer text-white small d-flex justify-content-between align-items-center py-2" style="background: rgba(0,0,0,0.1);">
                <span>Ver Veículos</span>
                <i class="fas fa-chevron-right"></i>
            </a>
        </div>
    </div>

    <!-- Card O.S. Abertas -->
    <div class="col-md-3">
        <div class="card bg-warning text-dark">
            <div class="card-body d-flex align-items-center justify-content-between py-3">
                <div>
                    <h6 class="text-uppercase small mb-1 font-weight-bold">O.S. em Andamento</h6>
                    <h3 class="mb-0 font-weight-bold">{{ $osAbertas }}</h3>
                </div>
                <i class="fas fa-tools fa-2x opacity-75"></i>
            </div>
            <a href="{{ route('ordens_servico.index') }}" class="card-footer text-dark small d-flex justify-content-between align-items-center py-2" style="background: rgba(0,0,0,0.08);">
                <span>Ver Ordens de Serviço</span>
                <i class="fas fa-chevron-right"></i>
            </a>
        </div>
    </div>

    <!-- Card Orçamentos Pendentes -->
    <div class="col-md-3">
        <div class="card bg-success text-white">
            <div class="card-body d-flex align-items-center justify-content-between py-3">
                <div>
                    <h6 class="text-uppercase small mb-1 font-weight-bold">Orçamentos</h6>
                    <h3 class="mb-0 font-weight-bold">{{ $orcamentosPendentes }}</h3>
                </div>
                <i class="fas fa-file-invoice-dollar fa-2x opacity-75"></i>
            </div>
            <a href="{{ route('orcamentos.index') }}" class="card-footer text-white small d-flex justify-content-between align-items-center py-2" style="background: rgba(0,0,0,0.1);">
                <span>Ver Orçamentos</span>
                <i class="fas fa-chevron-right"></i>
            </a>
        </div>
    </div>
</div>

<div class="row mt-2">
    <!-- Últimas Ordens de Serviço -->
    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <span class="font-weight-bold"><i class="fas fa-clipboard-list mr-1 text-primary"></i> Últimas Ordens de Serviço</span>
                <a href="{{ route('ordens_servico.criar') }}" class="btn btn-sm btn-primary">
                    <i class="fas fa-plus mr-1"></i> Nova O.S.
                </a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="thead-light">
                            <tr>
                                <th>Número</th>
                                <th>Cliente</th>
                                <th>Veículo</th>
                                <th>Status</th>
                                <th>Total</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($ultimasOS as $os)
                            <tr>
                                <td class="font-weight-bold">{{ $os->numero }}</td>
                                <td>{{ $os->cliente->nome ?? '-' }}</td>
                                <td>{{ $os->veiculo->placa ?? '-' }} ({{ $os->veiculo->modelo ?? '-' }})</td>
                                <td>
                                    <span class="badge badge-pill badge-secondary text-capitalize">{{ str_replace('_', ' ', $os->status) }}</span>
                                </td>
                                <td>R$ {{ number_format($os->valor_total, 2, ',', '.') }}</td>
                                <td>
                                    <a href="{{ route('ordens_servico.detalhes', $os->id) }}" class="btn btn-sm btn-outline-info">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">Nenhuma ordem de serviço aberta no momento.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Alertas de Reposição de Estoque -->
    <div class="col-md-4">
        <div class="card shadow-sm border-danger">
            <div class="card-header bg-danger text-white d-flex justify-content-between align-items-center">
                <span><i class="fas fa-exclamation-triangle mr-1"></i> Alertas de Estoque Baixo</span>
                <span class="badge badge-light">{{ $pecasEstoqueBaixo->count() }}</span>
            </div>
            <div class="card-body p-0">
                <ul class="list-group list-group-flush">
                    @forelse($pecasEstoqueBaixo as $peca)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <strong>{{ $peca->nome }}</strong><br>
                            <small class="text-muted">Cód: {{ $peca->codigo }} | Mín: {{ $peca->estoque_minimo }} {{ $peca->unidade_medida }}</small>
                        </div>
                        <span class="badge badge-danger badge-pill">{{ $peca->quantidade_estoque }} em estoque</span>
                    </li>
                    @empty
                    <li class="list-group-item text-center text-success py-4">
                        <i class="fas fa-check-circle fa-2x mb-2"></i><br>
                        Estoque sob controle!
                    </li>
                    @endforelse
                </ul>
            </div>
            <div class="card-footer bg-white text-center">
                <a href="{{ route('pecas.index') }}" class="btn btn-sm btn-outline-danger">Gerenciar Almoxarifado</a>
            </div>
        </div>
    </div>
</div>
@endsection
