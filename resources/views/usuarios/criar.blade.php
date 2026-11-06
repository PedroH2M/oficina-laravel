@extends('layouts.app')

@section('titulo', 'Cadastrar Funcionário')

@section('conteudo')
<div class="row justify-content-center">
    <div class="col-md-7">
        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <h5 class="mb-0 font-weight-bold text-dark">
                    <i class="fas fa-user-plus mr-1 text-warning"></i> Novo Funcionário / Usuário
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('usuarios.armazenar') }}" method="POST">
                    @csrf

                    <div class="form-group mb-3">
                        <label for="nome" class="font-weight-bold small">Nome Completo *</label>
                        <input type="text" name="nome" id="nome" class="form-control @error('nome') is-invalid @enderror" value="{{ old('nome') }}" placeholder="Ex: Roberto Oliveira" required autofocus>
                        @error('nome') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label for="email" class="font-weight-bold small">E-mail de Acesso *</label>
                        <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" placeholder="roberto@oficina.com.br" required>
                        @error('email') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-row mb-3">
                        <div class="form-group col-md-6">
                            <label for="senha" class="font-weight-bold small">Senha Inicial *</label>
                            <input type="password" name="senha" id="senha" class="form-control @error('senha') is-invalid @enderror" placeholder="Mínimo 6 caracteres" required>
                            @error('senha') <span class="invalid-feedback">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group col-md-6">
                            <label for="perfil" class="font-weight-bold small">Cargo / Perfil de Acesso *</label>
                            <select name="perfil" id="perfil" class="form-control @error('perfil') is-invalid @enderror" required>
                                <option value="">-- Selecione o Cargo --</option>
                                <option value="mecanico" {{ old('perfil') == 'mecanico' ? 'selected' : '' }}>Mecânico (Execução técnica & O.S.)</option>
                                <option value="atendente" {{ old('perfil') == 'atendente' ? 'selected' : '' }}>Atendente (Atendimento & Orçamentos)</option>
                                <option value="administrador" {{ old('perfil') == 'administrador' ? 'selected' : '' }}>Administrador (Acesso total)</option>
                            </select>
                            @error('perfil') <span class="invalid-feedback">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="text-right pt-2 border-top">
                        <a href="{{ route('usuarios.index') }}" class="btn btn-secondary mr-2">Cancelar</a>
                        <button type="submit" class="btn btn-warning font-weight-bold shadow-sm">
                            <i class="fas fa-save mr-1"></i> Salvar Funcionário
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
