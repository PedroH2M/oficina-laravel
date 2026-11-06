@extends('layouts.app')

@section('titulo', 'Cadastrar Cliente')

@section('conteudo')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="fas fa-user-plus mr-1 text-primary"></i> Novo Cliente</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('clientes.armazenar') }}" method="POST">
                    @csrf

                    <div class="form-row">
                        <div class="form-group col-md-8">
                            <label for="nome">Nome Completo *</label>
                            <input type="text" name="nome" id="nome" class="form-control @error('nome') is-invalid @enderror" value="{{ old('nome') }}" required>
                            @error('nome') <span class="invalid-feedback">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group col-md-4">
                            <label for="cpf_cnpj">CPF ou CNPJ *</label>
                            <input type="text" name="cpf_cnpj" id="cpf_cnpj" class="form-control @error('cpf_cnpj') is-invalid @enderror" value="{{ old('cpf_cnpj') }}" placeholder="Somente números" required>
                            @error('cpf_cnpj') <span class="invalid-feedback">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="telefone">Telefone / WhatsApp *</label>
                            <input type="text" name="telefone" id="telefone" class="form-control @error('telefone') is-invalid @enderror" value="{{ old('telefone') }}" required>
                            @error('telefone') <span class="invalid-feedback">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group col-md-6">
                            <label for="email">E-mail</label>
                            <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}">
                            @error('email') <span class="invalid-feedback">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="endereco">Endereço</label>
                            <input type="text" name="endereco" id="endereco" class="form-control" value="{{ old('endereco') }}">
                        </div>
                        <div class="form-group col-md-3">
                            <label for="cidade">Cidade</label>
                            <input type="text" name="cidade" id="cidade" class="form-control" value="{{ old('cidade') }}">
                        </div>
                        <div class="form-group col-md-1">
                            <label for="estado">UF</label>
                            <input type="text" name="estado" id="estado" maxlength="2" class="form-control text-uppercase" value="{{ old('estado') }}">
                        </div>
                        <div class="form-group col-md-2">
                            <label for="cep">CEP</label>
                            <input type="text" name="cep" id="cep" class="form-control" value="{{ old('cep') }}">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="observacoes">Observações</label>
                        <textarea name="observacoes" id="observacoes" rows="2" class="form-control">{{ old('observacoes') }}</textarea>
                    </div>

                    <div class="text-right">
                        <a href="{{ route('clientes.index') }}" class="btn btn-secondary mr-2">Cancelar</a>
                        <button type="submit" class="btn btn-primary"><i class="fas fa-save mr-1"></i> Salvar Cliente</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
