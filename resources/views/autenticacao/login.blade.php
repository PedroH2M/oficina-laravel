@extends('layouts.app')

@section('titulo', 'Acesso ao Sistema')

@section('conteudo')
<div class="row justify-content-center mt-5">
    <div class="col-md-5 col-lg-4">
        <div class="card shadow border-0">
            <div class="card-header bg-dark text-white text-center py-4" style="border-top-left-radius: 12px; border-top-right-radius: 12px;">
                <div class="mb-2">
                    <i class="fas fa-tools text-warning fa-2x"></i>
                </div>
                <h4 class="font-weight-bold mb-1">Oficina Laravel</h4>
                <p class="text-muted small mb-0">Sistema de Gestão Automotiva</p>
            </div>

            <div class="card-body p-4 bg-white" style="border-bottom-left-radius: 12px; border-bottom-right-radius: 12px;">
                @if($errors->any())
                    <div class="alert alert-danger py-2 px-3 small">
                        <i class="fas fa-exclamation-circle mr-1"></i> {{ $errors->first() }}
                    </div>
                @endif

                <form action="{{ route('login.entrar') }}" method="POST">
                    @csrf

                    <div class="form-group mb-3">
                        <label for="email" class="font-weight-bold text-dark small">E-mail</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text bg-light text-muted border-right-0"><i class="fas fa-envelope"></i></span>
                            </div>
                            <input type="email" name="email" id="email" class="form-control border-left-0 @error('email') is-invalid @enderror" value="{{ old('email') }}" placeholder="seu.email@exemplo.com" required autofocus>
                        </div>
                    </div>

                    <div class="form-group mb-3">
                        <label for="senha" class="font-weight-bold text-dark small">Senha</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text bg-light text-muted border-right-0"><i class="fas fa-lock"></i></span>
                            </div>
                            <input type="password" name="senha" id="senha" class="form-control border-left-0 @error('senha') is-invalid @enderror" placeholder="••••••••" required>
                        </div>
                    </div>

                    <div class="form-group form-check mb-4">
                        <input type="checkbox" name="lembrar" class="form-check-input" id="lembrar" checked>
                        <label class="form-check-label text-muted small" for="lembrar">Lembrar minha sessão</label>
                    </div>

                    <button type="submit" class="btn btn-warning btn-block font-weight-bold py-2 shadow-sm text-dark">
                        <i class="fas fa-sign-in-alt mr-1"></i> Acessar Oficina
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
