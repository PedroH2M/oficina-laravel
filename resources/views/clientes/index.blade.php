@extends('layouts.app')

@section('titulo', 'Gestão de Clientes')

@section('conteudo')
<div class="row mb-3">
    <div class="col-md-6">
        <h3 class="font-weight-bold"><i class="fas fa-users text-primary mr-2"></i>Clientes</h3>
    </div>
    <div class="col-md-6 text-right">
        <a href="{{ route('clientes.criar') }}" class="btn btn-primary">
            <i class="fas fa-plus mr-1"></i> Novo Cliente
        </a>
    </div>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="thead-light">
                    <tr>
                        <th>Nome</th>
                        <th>CPF/CNPJ</th>
                        <th>Telefone</th>
                        <th>E-mail</th>
                        <th>Cidade/UF</th>
                        <th>Veículos</th>
                        <th class="text-right">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($clientes as $cliente)
                    <tr>
                        <td class="font-weight-bold">{{ $cliente->nome }}</td>
                        <td>{{ $cliente->cpf_cnpj }}</td>
                        <td>{{ $cliente->telefone }}</td>
                        <td>{{ $cliente->email ?? '-' }}</td>
                        <td>{{ $cliente->cidade ?? '-' }}/{{ $cliente->estado ?? '-' }}</td>
                        <td>
                            <span class="badge badge-info">{{ $cliente->veiculos->count() }} veículo(s)</span>
                        </td>
                        <td class="text-right">
                            <a href="{{ route('clientes.editar', $cliente->id) }}" class="btn btn-sm btn-outline-secondary">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('clientes.excluir', $cliente->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Tem certeza que deseja excluir este cliente?')">
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
                        <td colspan="7" class="text-center py-4 text-muted">Nenhum cliente cadastrado.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($clientes->hasPages())
    <div class="card-footer bg-white">
        {{ $clientes->links() }}
    </div>
    @endif
</div>
@endsection
