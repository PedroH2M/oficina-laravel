@extends('layouts.app')

@section('titulo', 'Gestão de Funcionários e Usuários')

@section('conteudo')
<div class="row mb-3">
    <div class="col-md-6">
        <h4 class="font-weight-bold text-dark"><i class="fas fa-user-shield text-warning mr-2"></i>Funcionários / Usuários</h4>
        <p class="text-muted small mb-0">Gerencie a equipe da oficina, mecânicos, atendentes e administradores com acesso ao sistema.</p>
    </div>
    <div class="col-md-6 text-right">
        <a href="{{ route('usuarios.criar') }}" class="btn btn-warning font-weight-bold shadow-sm">
            <i class="fas fa-user-plus mr-1"></i> Novo Funcionário
        </a>
    </div>
</div>

<div class="card shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="thead-light">
                    <tr>
                        <th>Nome</th>
                        <th>E-mail</th>
                        <th>Cargo / Perfil</th>
                        <th>Data de Cadastro</th>
                        <th class="text-right">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($usuarios as $user)
                    <tr>
                        <td class="font-weight-bold">
                            <i class="fas fa-user-circle text-muted mr-1"></i> {{ $user->nome }}
                            @if(auth()->id() == $user->id)
                                <span class="badge badge-secondary ml-1">Você</span>
                            @endif
                        </td>
                        <td>{{ $user->email }}</td>
                        <td>
                            @if($user->perfil === 'administrador')
                                <span class="badge badge-danger p-1"><i class="fas fa-crown mr-1"></i> Administrador</span>
                            @elseif($user->perfil === 'mecanico')
                                <span class="badge badge-warning text-dark p-1"><i class="fas fa-wrench mr-1"></i> Mecânico</span>
                            @else
                                <span class="badge badge-info p-1"><i class="fas fa-headset mr-1"></i> Atendente</span>
                            @endif
                        </td>
                        <td>{{ $user->created_at ? $user->created_at->format('d/m/Y') : '-' }}</td>
                        <td class="text-right">
                            <a href="{{ route('usuarios.editar', $user->id) }}" class="btn btn-sm btn-outline-secondary" title="Editar">
                                <i class="fas fa-edit"></i>
                            </a>

                            @if(auth()->id() != $user->id)
                            <form action="{{ route('usuarios.excluir', $user->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Deseja realmente excluir este funcionário?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Excluir">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-4 text-muted">Nenhum funcionário cadastrado.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($usuarios->hasPages())
    <div class="card-footer bg-white">
        {{ $usuarios->links() }}
    </div>
    @endif
</div>
@endsection
