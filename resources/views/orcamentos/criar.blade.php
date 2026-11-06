@extends('layouts.app')

@section('titulo', 'Novo Orçamento')

@section('conteudo')
<div class="row justify-content-center">
    <div class="col-md-11">
        <div class="card shadow-sm">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0 font-weight-bold text-dark">
                    <i class="fas fa-file-invoice mr-1 text-success"></i> Elaborar Novo Orçamento
                </h5>
                <a href="{{ route('servicos.criar') }}" target="_blank" class="btn btn-sm btn-outline-warning">
                    <i class="fas fa-plus mr-1"></i> Cadastrar Novo Serviço
                </a>
            </div>
            <div class="card-body">
                <form action="{{ route('orcamentos.armazenar') }}" method="POST" id="formOrcamento">
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
                        <div class="form-group col-md-2">
                            <label for="data_validade" class="font-weight-bold small">Validade *</label>
                            <input type="date" name="data_validade" id="data_validade" class="form-control" value="{{ old('data_validade', date('Y-m-d', strtotime('+7 days'))) }}" required>
                        </div>
                        <div class="form-group col-md-2">
                            <label for="valor_desconto" class="font-weight-bold small">Desconto (R$)</label>
                            <input type="number" step="0.01" name="valor_desconto" id="valor_desconto" class="form-control" value="{{ old('valor_desconto', '0.00') }}">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="observacoes" class="font-weight-bold small">Observações / Relato do Cliente</label>
                        <textarea name="observacoes" id="observacoes" rows="2" class="form-control" placeholder="Descreva os sintomas relatados pelo cliente...">{{ old('observacoes') }}</textarea>
                    </div>

                    <!-- Tabela Dinâmica de Itens (Peças e Serviços) -->
                    <div class="card bg-light border mb-4">
                        <div class="card-header bg-light d-flex justify-content-between align-items-center">
                            <h6 class="mb-0 font-weight-bold text-dark">
                                <i class="fas fa-list mr-1"></i> Itens do Orçamento (Peças & Mão de Obra)
                            </h6>
                            <button type="button" class="btn btn-sm btn-success" id="btnAdicionarItem">
                                <i class="fas fa-plus mr-1"></i> Adicionar Item
                            </button>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-bordered mb-0" id="tabelaItens">
                                    <thead class="thead-light">
                                        <tr>
                                            <th style="width: 14%;">Tipo</th>
                                            <th style="width: 28%;">Catálogo (Peça ou Serviço)</th>
                                            <th style="width: 30%;">Descrição / Detalhe *</th>
                                            <th style="width: 10%;">Qtd *</th>
                                            <th style="width: 13%;">Valor Unit. (R$) *</th>
                                            <th style="width: 5%;"></th>
                                        </tr>
                                    </thead>
                                    <tbody id="corpoItens">
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
                                                                {{ $p->nome }} (R$ {{ number_format($p->preco_venda, 2, ',', '.') }})
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
                                                <input type="text" name="itens[0][descricao]" class="form-control form-control-sm input-descricao" placeholder="Ex: Pastilha de freio dianteira" required>
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
                        <a href="{{ route('orcamentos.index') }}" class="btn btn-secondary mr-2">Cancelar</a>
                        <button type="submit" class="btn btn-success font-weight-bold"><i class="fas fa-check mr-1"></i> Salvar e Gerar Orçamento</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    const pecasOptions = `@foreach($pecas as $p)<option value="{{ $p->id }}" data-preco="{{ $p->preco_venda }}" data-nome="{{ addslashes($p->nome) }}">{{ addslashes($p->nome) }} (R$ {{ number_format($p->preco_venda, 2, ',', '.') }})</option>@endforeach`;
    const servicosOptions = `@foreach($servicos as $s)<option value="{{ $s->id }}" data-preco="{{ $s->preco }}" data-nome="{{ addslashes($s->nome) }}">{{ addslashes($s->nome) }} (R$ {{ number_format($s->preco, 2, ',', '.') }})</option>@endforeach`;

    let indice = 1;

    document.getElementById('btnAdicionarItem').addEventListener('click', function() {
        const corpo = document.getElementById('corpoItens');
        const novaLinha = document.createElement('tr');
        novaLinha.className = 'linha-item';
        novaLinha.innerHTML = `
            <td>
                <select name="itens[${indice}][tipo]" class="form-control form-control-sm select-tipo">
                    <option value="peca" selected>Peça</option>
                    <option value="servico">Serviço/Mão de Obra</option>
                </select>
            </td>
            <td>
                <div class="bloco-peca">
                    <select name="itens[${indice}][peca_id]" class="form-control form-control-sm select-peca">
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
                <input type="text" name="itens[${indice}][descricao]" class="form-control form-control-sm input-descricao" placeholder="Descrição do item" required>
            </td>
            <td>
                <input type="number" name="itens[${indice}][quantidade]" class="form-control form-control-sm" value="1" min="1" required>
            </td>
            <td>
                <input type="number" step="0.01" name="itens[${indice}][valor_unitario]" class="form-control form-control-sm input-preco" value="0.00" required>
            </td>
            <td class="text-center">
                <button type="button" class="btn btn-sm btn-outline-danger btn-remover"><i class="fas fa-trash"></i></button>
            </td>
        `;
        corpo.appendChild(novaLinha);
        indice++;
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
            if (document.querySelectorAll('#corpoItens tr').length > 1) {
                tr.remove();
            }
        }
    });
</script>
@endsection