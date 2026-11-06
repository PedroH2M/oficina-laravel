@extends('layouts.app')

@section('titulo', 'Gestão de Veículos')

@section('conteudo')
<div class="row mb-3">
    <div class="col-md-6">
        <h3 class="font-weight-bold"><i class="fas fa-car text-info mr-2"></i>Veículos</h3>
    </div>
    <div class="col-md-6 text-right">
        <a href="{{ route('veiculos.criar') }}" class="btn btn-info">
            <i class="fas fa-plus mr-1"></i> Novo Veículo
        </a>
    </div>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="thead-light">
                    <tr>
                        <th>Placa</th>
                        <th>Marca / Modelo</th>
                        <th>Ano</th>
                        <th>Cor</th>
                        <th>Quilometragem</th>
                        <th>Proprietário</th>
                        <th class="text-right">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($veiculos as $veiculo)
                    <tr>
                        <td class="font-weight-bold"><span class="badge badge-dark p-2">{{ $veiculo->placa }}</span></td>
                        <td>{{ $veiculo->marca }} {{ $veiculo->modelo }}</td>
                        <td>{{ $veiculo->ano_fabricacao }}/{{ $veiculo->ano_modelo }}</td>
                        <td>{{ $veiculo->cor }}</td>
                        <td>{{ number_format($veiculo->quilometragem_atual, 0, ',', '.') }} km</td>
                        <td>{{ $veiculo->cliente->nome ?? '-' }}</td>
                        <td class="text-right">
                            <a href="{{ route('veiculos.historico', $veiculo->id) }}" class="btn btn-sm btn-outline-info" title="Ver Histórico Completo">
                                <i class="fas fa-history mr-1"></i> Histórico
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-4 text-muted">Nenhum veículo cadastrado.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($veiculos->hasPages())
    <div class="card-footer bg-white">
        {{ $veiculos->links() }}
    </div>
    @endif
</div>
@endsection
