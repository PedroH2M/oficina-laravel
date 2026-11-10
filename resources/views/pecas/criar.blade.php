@extends('layouts.app')

@section('titulo', 'Cadastrar Peça')

@section('conteudo')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="fas fa-plus-circle mr-1 text-warning"></i> Nova Peça no Estoque</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('pecas.armazenar') }}" method="POST">
                    @csrf

                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label for="codigo">Código Interno / Fabricante *</label>
                            <input type="text" name="codigo" id="codigo" class="form-control text-uppercase @error('codigo') is-invalid @enderror" value="{{ old('codigo') }}" placeholder="Ex: PEC-001" required>
                            @error('codigo') <span class="invalid-feedback">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group col-md-8">
                            <label for="nome">Nome da Peça *</label>
                            <input type="text" name="nome" id="nome" class="form-control @error('nome') is-invalid @enderror" value="{{ old('nome') }}" placeholder="Ex: Pastilha de Freio Cerâmica" required>
                            @error('nome') <span class="invalid-feedback">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="descricao">Descrição / Aplicação / Compatibilidade</label>
                        <textarea name="descricao" id="descricao" rows="2" class="form-control @error('descricao') is-invalid @enderror">{{ old('descricao') }}</textarea>
                        @error('descricao') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-3">
                            <label for="preco_custo">Preço de Custo (R$) *</label>
                            <input type="number" min="0" step="0.01" name="preco_custo" id="preco_custo" class="form-control @error('preco_custo') is-invalid @enderror" value="{{ old('preco_custo', '0.00') }}" required>
                            @error('preco_custo') <span class="invalid-feedback">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group col-md-3">
                            <label for="preco_venda">Preço de Venda (R$) *</label>
                            <input type="number" min="0" step="0.01" name="preco_venda" id="preco_venda" class="form-control @error('preco_venda') is-invalid @enderror" value="{{ old('preco_venda', '0.00') }}" required>
                            @error('preco_venda') <span class="invalid-feedback">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group col-md-2">
                            <label for="quantidade_estoque">Estoque Inicial *</label>
                            <input type="number" min="0" name="quantidade_estoque" id="quantidade_estoque" class="form-control @error('quantidade_estoque') is-invalid @enderror" value="{{ old('quantidade_estoque', 10) }}" required>
                            @error('quantidade_estoque') <span class="invalid-feedback">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group col-md-2">
                            <label for="estoque_minimo">Estoque Mínimo *</label>
                            <input type="number" min="0" name="estoque_minimo" id="estoque_minimo" class="form-control @error('estoque_minimo') is-invalid @enderror" value="{{ old('estoque_minimo', 5) }}" required>
                            @error('estoque_minimo') <span class="invalid-feedback">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group col-md-2">
                            <label for="unidade_medida">Unidade *</label>
                            <input type="text" name="unidade_medida" id="unidade_medida" class="form-control text-uppercase @error('unidade_medida') is-invalid @enderror" value="{{ old('unidade_medida', 'UN') }}" placeholder="UN, L, KG" required>
                            @error('unidade_medida') <span class="invalid-feedback">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="text-right">
                        <a href="{{ route('pecas.index') }}" class="btn btn-secondary mr-2">Cancelar</a>
                        <button type="submit" class="btn btn-warning font-weight-bold"><i class="fas fa-save mr-1"></i> Salvar Peça</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
