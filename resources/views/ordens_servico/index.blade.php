@extends('layouts.app')

@section('titulo', 'Ordens de Serviço')

@section('conteudo')
<div class="row mb-3">
    <div class="col-md-6">
        <h3 class="font-weight-bold"><i class="fas fa-clipboard-list text-primary mr-2"></i>Ordens de Serviço</h3>
    </div>
    <div class="col-md-6 text-right">
        <a href="{{ route('ordens_servico.criar') }}" class="btn btn-primary font-weight-bold">
            <i class="fas fa-plus mr-1"></i> Abrir Nova O.S.
        </a>
    </div>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="thead-light">
                    <tr>
                        <th>Número</th>
                        <th>Cliente</th>
                        <th>Veículo</th>
                        <th>Data Abertura</th>
                        <th>Mecânico</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th class="text-right">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($ordens as $os)
                    <tr>
                        <td class="font-weight-bold">{{ $os->numero }}</td>
                        <td>{{ $os->cliente->nome ?? '-' }}</td>
                        <td>
                            <span class="badge badge-dark">{{ $os->veiculo->placa ?? '-' }}</span> 
                            {{ $os->veiculo->modelo ?? '' }}
                        </td>
                        <td>{{ $os->data_abertura->format('d/m/Y H:i') }}</td>
                        <td>{{ $os->mecanicoResponsavel->nome ?? 'Não atribuído' }}</td>
                        <td class="font-weight-bold">R$ {{ number_format($os->valor_total, 2, ',', '.') }}</td>
                        <td>
                            @if($os->status === 'aberta')
                                <span class="badge badge-secondary p-1">Aberta</span>
                            @elseif($os->status === 'em_diagnostico')
                                <span class="badge badge-info p-1">Em Diagnóstico</span>
                            @elseif($os->status === 'aguardando_pecas')
                                <span class="badge badge-warning text-dark p-1">Aguardando Peças</span>
                            @elseif($os->status === 'em_execucao')
                                <span class="badge badge-primary p-1">Em Execução</span>
                            @elseif($os->status === 'finalizada')
                                <span class="badge badge-success p-1">Finalizada</span>
                            @else
                                <span class="badge badge-danger p-1">Cancelada</span>
                            @endif
                        </td>
                        <td class="text-right">
                            <a href="{{ route('ordens_servico.detalhes', $os->id) }}" class="btn btn-sm btn-outline-info" title="Gerenciar O.S.">
                                <i class="fas fa-eye mr-1"></i> Gerenciar
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-4 text-muted">Nenhuma ordem de serviço cadastrada.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($ordens->hasPages())
    <div class="card-footer bg-white">
        {{ $ordens->links() }}
    </div>
    @endif
</div>
@endsection
