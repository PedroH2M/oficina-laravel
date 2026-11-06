@extends('layouts.app')

@section('titulo', 'Abrir Ordem de Serviço')

@section('conteudo')
<div class="row justify-content-center">
    <div class="col-md-11">
        <div class="card shadow-sm">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0 font-weight-bold text-dark">
                    <i class="fas fa-tools mr-1 text-primary"></i> Abertura Direta de Ordem de Serviço
                </h5>
                <a href="{{ route('servicos.criar') }}" target="_blank" class="btn btn-sm btn-outline-warning">
                    <i class="fas fa-plus mr-1"></i> Cadastrar Novo Serviço
                </a>
            </div>
            <div class="card-body">
                <form action="{{ route('ordens_servico.armazenar') }}" method="POST" id="formOS">
                    @csrf

                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label for="cliente_id" class="font-weight-bold small">Cliente *</label>
                            <select name="cliente_id" id="cliente_id" class="form-control" required>
                                <option value="">-- Selecione o Cliente --</option>
                                @foreach($clientes as $cli)
                                    <option value="{{ $cli->id }}" {{ old('cliente_id') == $cli->id ? 'selected' : '' }}>
                                        {{ $cli->nome }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="veiculo_id" class="font-weight-bold small">Veículo *</label>
                            <select name="veiculo_id" id="veiculo_id" class="form-control" required>
                                <option value="">-- Selecione o Veículo --</option>
                                @foreach($veiculos as $veic)
                                    <option value="{{ $veic->id }}" {{ old('veiculo_id') == $veic->id ? 'selected' : '' }}>
                                        {{ $veic->placa }} - {{ $veic->marca }} {{ $veic->modelo }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="usuario_mecanico_id" class="font-weight-bold small">Mecânico Responsável</label>
                            <select name="usuario_mecanico_id" id="usuario_mecanico_id" class="form-control">
                                <option value="">-- Selecione o Mecânico --</option>
                                @foreach($mecanicos as $mec)
                                    <option value="{{ $mec->id }}">{{ $mec->nome }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label for="quilometragem_entrada" class="font-weight-bold small">KM de Entrada no Pátio *</label>
                            <input type="number" name="quilometragem_entrada" id="quilometragem_entrada" class="form-control" value="{{ old('quilometragem_entrada', 0) }}" required>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="data_previsao" class="font-weight-bold small">Previsão de Conclusão</label>
                            <input type="datetime-local" name="data_previsao" id="data_previsao" class="form-control" value="{{ old('data_previsao', date('Y-m-d\TH:i', strtotime('+2 days'))) }}">
                        </div>
                        <div class="form-group col-md-4">
                            <label for="valor_desconto" class="font-weight-bold small">Desconto (R$)</label>
                            <input type="number" step="0.01" name="valor_desconto" id="valor_desconto" class="form-control" value="{{ old('valor_desconto', '0.00') }}">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="diagnostico_defeito" class="font-weight-bold small">Diagnóstico Inicial / Relato do Cliente / Defeito Reclamado *</label>
                        <textarea name="diagnostico_defeito" id="diagnostico_defeito" rows="3" class="form-control" placeholder="Descreva os problemas relatados pelo cliente e a checagem inicial..." required>{{ old('diagnostico_defeito') }}</textarea>
                    </div>

                    <!-- Tabela Dinâmica de Peças e Serviços -->
                    <div class="card bg-light border mb-4">
                        <div class="card-header bg-light d-flex justify-content-between align-items-center">
                            <h6 class="mb-0 font-weight-bold text-dark">
                                <i class="fas fa-wrench mr-1"></i> Peças Aplicadas & Serviços Prestados
                            </h6>
                            <button type="button" class="btn btn-sm btn-primary" id="btnAdicionarItemOS">
                                <i class="fas fa-plus mr-1"></i> Adicionar Item
                            </button>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-bordered mb-0">
                                    <thead class="thead-light">
                                        <tr>
                                            <th style="width: 14%;">Tipo</th>
                                            <th style="width: 28%;">Catálogo (Peça ou Serviço)</th>
                                            <th style="width: 30%;">Descrição / Procedimento *</th>
                                            <th style="width: 10%;">Qtd *</th>
                                            <th style="width: 13%;">Valor Unit. (R$) *</th>
                                            <th style="width: 5%;"></th>
                                        </tr>
                                    </thead>
                                    <tbody id="corpoItensOS">
                                        <tr class="linha-item">
                                            <td>
                                                <select name="itens[0][tipo]" class="form-control form-control-sm select-tipo">
                                                    <option value="peca" selected>Peça</option>
                                                    <option value="servico">Serviço/Mão de Obra</option>
                                                </select>
                                            </td>
                                            <td>
                                                <div class="bloco-peca">
                                                    <select name="itens[0][peca_id]" class="form-control form-control-sm select-peca">
                                                        <option value="">-- Selecione a Peça (Opcional) --</option>
                                                        @foreach($pecas as $p)
                                                            <option value="{{ $p->id }}" data-preco="{{ $p->preco_venda }}" data-nome="{{ $p->nome }}">
                                                                {{ $p->nome }} (Estoque: {{ $p->quantidade_estoque }})
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="bloco-servico d-none">
                                                    <select class="form-control form-control-sm select-servico">
                                                        <option value="">-- Selecione o Serviço (Opcional) --</option>
                                                        @foreach($servicos as $s)
                                                            <option value="{{ $s->id }}" data-preco="{{ $s->preco }}" data-nome="{{ $s->nome }}">
                                                                {{ $s->nome }} (R$ {{ number_format($s->preco, 2, ',', '.') }})
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </td>
                                            <td>
                                                <input type="text" name="itens[0][descricao]" class="form-control form-control-sm input-descricao" placeholder="Ex: Substituição de pastilhas" required>
                                            </td>
                                            <td>
                                                <input type="number" name="itens[0][quantidade]" class="form-control form-control-sm" value="1" min="1" required>
                                            </td>
                                            <td>
                                                <input type="number" step="0.01" name="itens[0][valor_unitario]" class="form-control form-control-sm input-preco" value="0.00" required>
                                            </td>
                                            <td class="text-center">
                                                <button type="button" class="btn btn-sm btn-outline-danger btn-remover" disabled><i class="fas fa-trash"></i></button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="text-right">
                        <a href="{{ route('ordens_servico.index') }}" class="btn btn-secondary mr-2">Cancelar</a>
                        <button type="submit" class="btn btn-primary font-weight-bold"><i class="fas fa-check-circle mr-1"></i> Abrir Ordem de Serviço</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    const pecasOptions = `@foreach($pecas as $p)<option value="{{ $p->id }}" data-preco="{{ $p->preco_venda }}" data-nome="{{ addslashes($p->nome) }}">{{ addslashes($p->nome) }} (Estoque: {{ $p->quantidade_estoque }})</option>@endforeach`;
    const servicosOptions = `@foreach($servicos as $s)<option value="{{ $s->id }}" data-preco="{{ $s->preco }}" data-nome="{{ addslashes($s->nome) }}">{{ addslashes($s->nome) }} (R$ {{ number_format($s->preco, 2, ',', '.') }})</option>@endforeach`;

    let indiceOS = 1;

    document.getElementById('btnAdicionarItemOS').addEventListener('click', function() {
        const corpo = document.getElementById('corpoItensOS');
        const novaLinha = document.createElement('tr');
        novaLinha.className = 'linha-item';
        novaLinha.innerHTML = `
            <td>
                <select name="itens[${indiceOS}][tipo]" class="form-control form-control-sm select-tipo">
                    <option value="peca" selected>Peça</option>
                    <option value="servico">Serviço/Mão de Obra</option>
                </select>
            </td>
            <td>
                <div class="bloco-peca">
                    <select name="itens[${indiceOS}][peca_id]" class="form-control form-control-sm select-peca">
                        <option value="">-- Selecione a Peça (Opcional) --</option>
                        ${pecasOptions}
                    </select>
                </div>
                <div class="bloco-servico d-none">
                    <select class="form-control form-control-sm select-servico">
                        <option value="">-- Selecione o Serviço (Opcional) --</option>
                        ${servicosOptions}
                    </select>
                </div>
            </td>
            <td>
                <input type="text" name="itens[${indiceOS}][descricao]" class="form-control form-control-sm input-descricao" placeholder="Descrição do item" required>
            </td>
            <td>
                <input type="number" name="itens[${indiceOS}][quantidade]" class="form-control form-control-sm" value="1" min="1" required>
            </td>
            <td>
                <input type="number" step="0.01" name="itens[${indiceOS}][valor_unitario]" class="form-control form-control-sm input-preco" value="0.00" required>
            </td>
            <td class="text-center">
                <button type="button" class="btn btn-sm btn-outline-danger btn-remover"><i class="fas fa-trash"></i></button>
            </td>
        `;
        corpo.appendChild(novaLinha);
        indiceOS++;
    });

    document.addEventListener('change', function(e) {
        // Alternância entre Peça e Serviço
        if (e.target && e.target.classList.contains('select-tipo')) {
            const tr = e.target.closest('tr');
            const tipo = e.target.value;
            const blocoPeca = tr.querySelector('.bloco-peca');
            const blocoServico = tr.querySelector('.bloco-servico');
            const selectPeca = tr.querySelector('.select-peca');
            const selectServico = tr.querySelector('.select-servico');

            if (tipo === 'peca') {
                blocoPeca.classList.remove('d-none');
                blocoServico.classList.add('d-none');
                selectServico.value = '';
                if (selectPeca) selectPeca.disabled = false;
            } else {
                blocoPeca.classList.add('d-none');
                blocoServico.classList.remove('d-none');
                if (selectPeca) {
                    selectPeca.value = '';
                    selectPeca.disabled = true; // Desabilita para não enviar peca_id vazio inválido se tipo servico
                }
            }
        }

        // Seleção de Peça do Catálogo
        if (e.target && e.target.classList.contains('select-peca')) {
            const opt = e.target.selectedOptions[0];
            const tr = e.target.closest('tr');
            if (opt && opt.dataset.preco) {
                tr.querySelector('.input-preco').value = opt.dataset.preco;
                tr.querySelector('.input-descricao').value = opt.dataset.nome;
            }
        }

        // Seleção de Serviço do Catálogo
        if (e.target && e.target.classList.contains('select-servico')) {
            const opt = e.target.selectedOptions[0];
            const tr = e.target.closest('tr');
            if (opt && opt.dataset.preco) {
                tr.querySelector('.input-preco').value = opt.dataset.preco;
                tr.querySelector('.input-descricao').value = opt.dataset.nome;
            }
        }
    });

    document.addEventListener('click', function(e) {
        if (e.target && (e.target.classList.contains('btn-remover') || e.target.closest('.btn-remover'))) {
            const btn = e.target.classList.contains('btn-remover') ? e.target : e.target.closest('.btn-remover');
            const tr = btn.closest('tr');
            if (document.querySelectorAll('#corpoItensOS tr').length > 1) {
                tr.remove();
            }
        }
    });
</script>
@endsection