@extends('layouts.app')

@section('titulo', 'Catálogo de Serviços')

@section('conteudo')
<div class="row mb-3">
    <div class="col-md-6">
        <h4 class="font-weight-bold text-dark"><i class="fas fa-wrench text-warning mr-2"></i>Catálogo de Serviços & Mão de Obra</h4>
        <p class="text-muted small mb-0">Cadastre e gerencie os serviços e tabelas de mão de obra prestados pela oficina.</p>
    </div>
    <div class="col-md-6 text-right">
        <a href="{{ route('servicos.criar') }}" class="btn btn-warning font-weight-bold shadow-sm">
            <i class="fas fa-plus mr-1"></i> Novo Serviço
        </a>
    </div>
</div>

<div class="card shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="thead-light">
                    <tr>
                        <th>Código</th>
                        <th>Nome do Serviço</th>
                        <th>Descrição / Procedimento</th>
                        <th>Tempo Estimado</th>
                        <th>Preço Padrão (Mão de Obra)</th>
                        <th class="text-right">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($servicos as $servico)
                    <tr>
                        <td class="font-weight-bold"><code>{{ $servico->codigo }}</code></td>
                        <td>
                            <i class="fas fa-cog text-muted mr-1"></i> {{ $servico->nome }}
                        </td>
                        <td class="text-muted">{{ $servico->descricao ?? '-' }}</td>
                        <td>
                            <span class="badge badge-light border">
                                <i class="far fa-clock mr-1 text-muted"></i> {{ $servico->tempo_estimado_minutos ?? 60 }} min
                            </span>
                        </td>
                        <td class="font-weight-bold text-success">
                            R$ {{ number_format($servico->preco, 2, ',', '.') }}
                        </td>
                        <td class="text-right">
                            <a href="{{ route('servicos.editar', $servico->id) }}" class="btn btn-sm btn-outline-secondary" title="Editar">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('servicos.excluir', $servico->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Deseja realmente excluir este serviço?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Excluir">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-4 text-muted">Nenhum serviço cadastrado ainda.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($servicos->hasPages())
    <div class="card-footer bg-white">
        {{ $servicos->links() }}
    </div>
    @endif
</div>
@endsection
