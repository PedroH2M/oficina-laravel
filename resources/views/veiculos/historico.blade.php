@extends('layouts.app')

@section('titulo', 'Histórico do Veículo ' . $veiculo->placa)

@section('conteudo')
<div class="row mb-3">
    <div class="col-md-8">
        <h3 class="font-weight-bold">
            <i class="fas fa-history text-info mr-2"></i>Histórico do Veículo: 
            <span class="badge badge-dark">{{ $veiculo->placa }}</span>
        </h3>
        <p class="text-muted">{{ $veiculo->marca }} {{ $veiculo->modelo }} ({{ $veiculo->ano_fabricacao }}/{{ $veiculo->ano_modelo }}) | Cor: {{ $veiculo->cor }}</p>
    </div>
    <div class="col-md-4 text-right">
        <a href="{{ route('ordens_servico.criar') }}" class="btn btn-primary mr-2">
            <i class="fas fa-plus mr-1"></i> Abrir O.S.
        </a>
        <a href="{{ route('veiculos.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left mr-1"></i> Voltar
        </a>
    </div>
</div>

<div class="row">
    <!-- Ficha Técnica do Veículo -->
    <div class="col-md-4">
        <div class="card shadow-sm">
            <div class="card-header bg-dark text-white">
                <i class="fas fa-info-circle mr-1 text-warning"></i> Dados do Veículo
            </div>
            <div class="card-body p-0">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item d-flex justify-content-between">
                        <strong>Proprietário:</strong>
                        <span>{{ $veiculo->cliente->nome ?? '-' }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between">
                        <strong>Telefone:</strong>
                        <span>{{ $veiculo->cliente->telefone ?? '-' }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between">
                        <strong>KM Atual:</strong>
                        <span class="badge badge-primary font-weight-bold p-1">{{ number_format($veiculo->quilometragem_atual, 0, ',', '.') }} km</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between">
                        <strong>Chassi:</strong>
                        <span>{{ $veiculo->chassi ?? 'Não informado' }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between">
                        <strong>Ordens de Serviço:</strong>
                        <span class="badge badge-secondary">{{ $veiculo->ordensServico->count() }} passagem(ns)</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Linha do Tempo de Manutenções e O.S. -->
    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-header bg-white font-weight-bold">
                <i class="fas fa-stream mr-1 text-primary"></i> Linha do Tempo de Eventos e Manutenções
            </div>
            <div class="card-body">
                @forelse($eventos as $evento)
                <div class="border-left border-info pl-3 mb-4 position-relative">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <h6 class="mb-0 font-weight-bold text-dark text-capitalize">
                            @if($evento->tipo_evento === 'abertura_os')
                                <i class="fas fa-wrench text-warning mr-1"></i> Abertura de Ordem de Serviço
                            @elseif($evento->tipo_evento === 'conclusao_os')
                                <i class="fas fa-check-circle text-success mr-1"></i> Manutenção Concluída
                            @elseif($evento->tipo_evento === 'manutencao_preventiva')
                                <i class="fas fa-shield-alt text-info mr-1"></i> Manutenção Preventiva
                            @else
                                <i class="fas fa-tag text-secondary mr-1"></i> {{ str_replace('_', ' ', $evento->tipo_evento) }}
                            @endif
                        </h6>
                        <small class="text-muted"><i class="far fa-clock mr-1"></i>{{ $evento->data_evento->format('d/m/Y H:i') }}</small>
                    </div>

                    <p class="mb-1 text-muted">{{ $evento->descricao }}</p>

                    <div class="small">
                        <span class="badge badge-light border">KM registrado: {{ number_format($evento->quilometragem_registrada, 0, ',', '.') }} km</span>
                        @if($evento->ordem_servico_id)
                            <a href="{{ route('ordens_servico.detalhes', $evento->ordem_servico_id) }}" class="btn btn-link btn-sm py-0">
                                Ver O.S. vinculada <i class="fas fa-external-link-alt"></i>
                            </a>
                        @endif
                    </div>
                </div>
                @empty
                <div class="text-center py-5 text-muted">
                    <i class="fas fa-car-crash fa-3x mb-3 text-secondary"></i>
                    <p>Nenhum registro de manutenção ou histórico encontrado para este veículo.</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
