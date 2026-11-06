@extends('layouts.app')

@section('titulo', 'Controle de Peças e Estoque')

@section('conteudo')
<div class="row mb-3">
    <div class="col-md-6">
        <h3 class="font-weight-bold"><i class="fas fa-cogs text-warning mr-2"></i>Controle de Peças & Estoque</h3>
    </div>
    <div class="col-md-6 text-right">
        <a href="{{ route('pecas.criar') }}" class="btn btn-warning font-weight-bold">
            <i class="fas fa-plus mr-1"></i> Nova Peça
        </a>
    </div>
</div>

@if($alertas->count() > 0)
<div class="alert alert-danger shadow-sm mb-4">
    <h5 class="alert-heading"><i class="fas fa-exclamation-triangle mr-2"></i>Atenção: {{ $alertas->count() }} item(ns) com estoque no limite ou abaixo do mínimo!</h5>
    <ul class="mb-0 pl-3">
        @foreach($alertas as $alerta)
            <li><strong>{{ $alerta->nome }}</strong> (Cód: {{ $alerta->codigo }}): Restam apenas <u>{{ $alerta->quantidade_estoque }} {{ $alerta->unidade_medida }}</u> (Mínimo recomendado: {{ $alerta->estoque_minimo }}).</li>
        @endforeach
    </ul>
</div>
@endif

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="thead-light">
                    <tr>
                        <th>Código</th>
                        <th>Nome da Peça</th>
                        <th>Preço Custo</th>
                        <th>Preço Venda</th>
                        <th>Estoque Atual</th>
                        <th>Estoque Mínimo</th>
                        <th>Situação</th>
                        <th class="text-right">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pecas as $peca)
                    <tr>
                        <td class="font-weight-bold"><code>{{ $peca->codigo }}</code></td>
                        <td>{{ $peca->nome }}</td>
                        <td>R$ {{ number_format($peca->preco_custo, 2, ',', '.') }}</td>
                        <td class="font-weight-bold text-success">R$ {{ number_format($peca->preco_venda, 2, ',', '.') }}</td>
                        <td>{{ $peca->quantidade_estoque }} {{ $peca->unidade_medida }}</td>
                        <td>{{ $peca->estoque_minimo }} {{ $peca->unidade_medida }}</td>
                        <td>
                            @if($peca->quantidade_estoque <= 0)
                                <span class="badge badge-danger">Esgotado</span>
                            @elseif($peca->quantidade_estoque <= $peca->estoque_minimo)
                                <span class="badge badge-warning text-dark">Estoque Baixo</span>
                            @else
                                <span class="badge badge-success">Normal</span>
                            @endif
                        </td>
                        <td class="text-right">
                            <a href="{{ route('pecas.editar', $peca->id) }}" class="btn btn-sm btn-outline-secondary">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('pecas.excluir', $peca->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Excluir peça do catálogo?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-4 text-muted">Nenhuma peça cadastrada no estoque.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($pecas->hasPages())
    <div class="card-footer bg-white">
        {{ $pecas->links() }}
    </div>
    @endif
</div>
@endsection
