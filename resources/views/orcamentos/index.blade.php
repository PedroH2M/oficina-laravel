@extends('layouts.app')

@section('titulo', 'Orçamentos')

@section('conteudo')
<div class="row mb-3">
    <div class="col-md-6">
        <h3 class="font-weight-bold"><i class="fas fa-file-invoice-dollar text-success mr-2"></i>Orçamentos</h3>
    </div>
    <div class="col-md-6 text-right">
        <a href="{{ route('orcamentos.criar') }}" class="btn btn-success font-weight-bold">
            <i class="fas fa-plus mr-1"></i> Novo Orçamento
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
                        <th>Validade</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th class="text-right">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orcamentos as $orcamento)
                    <tr>
                        <td class="font-weight-bold">{{ $orcamento->numero }}</td>
                        <td>{{ $orcamento->cliente->nome ?? '-' }}</td>
                        <td>{{ $orcamento->veiculo->placa ?? '-' }} ({{ $orcamento->veiculo->modelo ?? '-' }})</td>
                        <td>{{ $orcamento->data_validade->format('d/m/Y') }}</td>
                        <td class="font-weight-bold text-success">R$ {{ number_format($orcamento->valor_total, 2, ',', '.') }}</td>
                        <td>
                            @if($orcamento->status === 'pendente')
                                <span class="badge badge-warning text-dark">Pendente</span>
                            @elseif($orcamento->status === 'aprovado')
                                <span class="badge badge-info">Aprovado pelo Cliente</span>
                            @elseif($orcamento->status === 'convertido_os')
                                <span class="badge badge-success">Convertido em O.S.</span>
                            @else
                                <span class="badge badge-secondary">Rejeitado</span>
                            @endif
                        </td>
                        <td class="text-right">
                            <a href="{{ route('orcamentos.detalhes', $orcamento->id) }}" class="btn btn-sm btn-outline-info" title="Ver Detalhes">
                                <i class="fas fa-eye"></i> Detalhes
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-4 text-muted">Nenhum orçamento cadastrado.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($orcamentos->hasPages())
    <div class="card-footer bg-white">
        {{ $orcamentos->links() }}
    </div>
    @endif
</div>
@endsection
