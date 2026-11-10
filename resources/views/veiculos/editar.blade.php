@extends('layouts.app')

@section('titulo', 'Editar Veículo')

@section('conteudo')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="fas fa-edit mr-1 text-info"></i> Editar Veículo: {{ $veiculo->placa ?? '' }}</h5>
            </div>
            <div class="card-body">
                <form action="{{ isset($veiculo) ? route('veiculos.atualizar', $veiculo->id ?? 0) : '#' }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <label for="cliente_id">Proprietário (Cliente) *</label>
                        <select name="cliente_id" id="cliente_id" class="form-control @error('cliente_id') is-invalid @enderror" required>
                            <option value="">-- Selecione o Cliente --</option>
                            @if(isset($clientes))
                                @foreach($clientes as $cli)
                                    <option value="{{ $cli->id }}" {{ old('cliente_id', $veiculo->cliente_id ?? '') == $cli->id ? 'selected' : '' }}>
                                        {{ $cli->nome }} (CPF/CNPJ: {{ $cli->cpf_cnpj }})
                                    </option>
                                @endforeach
                            @endif
                        </select>
                        @error('cliente_id') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label for="placa">Placa *</label>
                            <input type="text" name="placa" id="placa" class="form-control text-uppercase @error('placa') is-invalid @enderror" value="{{ old('placa', $veiculo->placa ?? '') }}" placeholder="Ex: ABC1D23" required>
                            @error('placa') <span class="invalid-feedback">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group col-md-4">
                            <label for="marca">Marca *</label>
                            <input type="text" name="marca" id="marca" class="form-control @error('marca') is-invalid @enderror" value="{{ old('marca', $veiculo->marca ?? '') }}" placeholder="Ex: Fiat, VW, Ford" required>
                            @error('marca') <span class="invalid-feedback">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group col-md-4">
                            <label for="modelo">Modelo *</label>
                            <input type="text" name="modelo" id="modelo" class="form-control @error('modelo') is-invalid @enderror" value="{{ old('modelo', $veiculo->modelo ?? '') }}" placeholder="Ex: Gol 1.6, Onix" required>
                            @error('modelo') <span class="invalid-feedback">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-3">
                            <label for="ano_fabricacao">Ano Fabricação *</label>
                            <input type="number" name="ano_fabricacao" id="ano_fabricacao" min="1950" class="form-control @error('ano_fabricacao') is-invalid @enderror" value="{{ old('ano_fabricacao', $veiculo->ano_fabricacao ?? date('Y')) }}" required>
                            @error('ano_fabricacao') <span class="invalid-feedback">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group col-md-3">
                            <label for="ano_modelo">Ano Modelo *</label>
                            <input type="number" name="ano_modelo" id="ano_modelo" min="1950" class="form-control @error('ano_modelo') is-invalid @enderror" value="{{ old('ano_modelo', $veiculo->ano_modelo ?? date('Y')) }}" required>
                            @error('ano_modelo') <span class="invalid-feedback">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group col-md-3">
                            <label for="cor">Cor *</label>
                            <input type="text" name="cor" id="cor" class="form-control @error('cor') is-invalid @enderror" value="{{ old('cor', $veiculo->cor ?? '') }}" placeholder="Ex: Prata, Preto" required>
                            @error('cor') <span class="invalid-feedback">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group col-md-3">
                            <label for="quilometragem_atual">KM Atual *</label>
                            <input type="number" name="quilometragem_atual" id="quilometragem_atual" min="0" class="form-control @error('quilometragem_atual') is-invalid @enderror" value="{{ old('quilometragem_atual', $veiculo->quilometragem_atual ?? 0) }}" required>
                            @error('quilometragem_atual') <span class="invalid-feedback">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="chassi">Número do Chassi</label>
                        <input type="text" name="chassi" id="chassi" class="form-control text-uppercase @error('chassi') is-invalid @enderror" value="{{ old('chassi', $veiculo->chassi ?? '') }}">
                        @error('chassi') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>

                    <div class="text-right">
                        <a href="{{ route('veiculos.index') }}" class="btn btn-secondary mr-2">Cancelar</a>
                        <button type="submit" class="btn btn-info"><i class="fas fa-save mr-1"></i> Atualizar Veículo</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
