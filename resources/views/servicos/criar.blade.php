@extends('layouts.app')

@section('titulo', 'Cadastrar Serviço')

@section('conteudo')
<div class="row justify-content-center">
    <div class="col-md-7">
        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <h5 class="mb-0 font-weight-bold text-dark">
                    <i class="fas fa-plus-circle mr-1 text-warning"></i> Novo Serviço / Mão de Obra
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('servicos.armazenar') }}" method="POST">
                    @csrf

                    <div class="form-row mb-3">
                        <div class="form-group col-md-4">
                            <label for="codigo" class="font-weight-bold small">Código do Serviço *</label>
                            <input type="text" name="codigo" id="codigo" class="form-control text-uppercase @error('codigo') is-invalid @enderror" value="{{ old('codigo') }}" placeholder="Ex: SRV-001" required autofocus>
                            @error('codigo') <span class="invalid-feedback">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group col-md-8">
                            <label for="nome" class="font-weight-bold small">Nome do Serviço / Procedimento *</label>
                            <input type="text" name="nome" id="nome" class="form-control @error('nome') is-invalid @enderror" value="{{ old('nome') }}" placeholder="Ex: Alinhamento 3D e Balanceamento" required>
                            @error('nome') <span class="invalid-feedback">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="form-group mb-3">
                        <label for="descricao" class="font-weight-bold small">Descrição Detalhada do Procedimento</label>
                        <textarea name="descricao" id="descricao" rows="2" class="form-control" placeholder="Descreva o escopo do serviço realizado...">{{ old('descricao') }}</textarea>
                    </div>

                    <div class="form-row mb-3">
                        <div class="form-group col-md-6">
                            <label for="preco" class="font-weight-bold small">Preço Padrão de Mão de Obra (R$) *</label>
                            <input type="number" step="0.01" name="preco" id="preco" class="form-control @error('preco') is-invalid @enderror" value="{{ old('preco', '0.00') }}" required>
                            @error('preco') <span class="invalid-feedback">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group col-md-6">
                            <label for="tempo_estimado_minutos" class="font-weight-bold small">Tempo Estimado (Minutos)</label>
                            <input type="number" name="tempo_estimado_minutos" id="tempo_estimado_minutos" class="form-control" value="{{ old('tempo_estimado_minutos', 60) }}" placeholder="Ex: 60">
                        </div>
                    </div>

                    <div class="text-right pt-2 border-top">
                        <a href="{{ route('servicos.index') }}" class="btn btn-secondary mr-2">Cancelar</a>
                        <button type="submit" class="btn btn-warning font-weight-bold shadow-sm">
                            <i class="fas fa-save mr-1"></i> Salvar Serviço
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
