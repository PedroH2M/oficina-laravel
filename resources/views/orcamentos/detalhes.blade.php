@extends('layouts.app')

@section('titulo', 'Orçamento ' . $orcamento->numero)

@section('conteudo')
<div class="row mb-3">
    <div class="col-md-6">
        <h3 class="font-weight-bold">
            <i class="fas fa-file-invoice-dollar text-success mr-2"></i>Orçamento: {{ $orcamento->numero }}
        </h3>
    </div>
    <div class="col-md-6 text-right">
        <a href="{{ route('orcamentos.index') }}" class="btn btn-outline-secondary mr-2">
            <i class="fas fa-arrow-left mr-1"></i> Voltar
        </a>
        <button onclick="window.print()" class="btn btn-outline-dark">
            <i class="fas fa-print mr-1"></i> Imprimir Orçamento
        </button>
    </div>
</div>

<div class="card shadow-sm">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <div>
            <span class="text-muted small">Status Atual:</span>
            @if($orcamento->status === 'pendente')
                <span class="badge badge-warning text-dark p-2">Pendente de Aprovação</span>
            @elseif($orcamento->status === 'aprovado')
                <span class="badge badge-info p-2">Aprovado pelo Cliente</span>
            @elseif($orcamento->status === 'convertido_os')
                <span class="badge badge-success p-2">Convertido em O.S.</span>
            @else
                <span class="badge badge-secondary p-2">Rejeitado</span>
            @endif
        </div>
        <div>
            <span class="text-muted small">Válido até:</span>
            <strong>{{ $orcamento->data_validade->format('d/m/Y') }}</strong>
        </div>
    </div>
    <div class="card-body">
        <!-- Dados Cliente e Veículo -->
        <div class="row mb-4">
            <div class="col-md-6">
                <h6 class="font-weight-bold border-bottom pb-2 text-primary">Dados do Cliente</h6>
                <p class="mb-1"><strong>Nome:</strong> {{ $orcamento->cliente->nome ?? '-' }}</p>
                <p class="mb-1"><strong>CPF/CNPJ:</strong> {{ $orcamento->cliente->cpf_cnpj ?? '-' }}</p>
                <p class="mb-1"><strong>Telefone:</strong> {{ $orcamento->cliente->telefone ?? '-' }}</p>
                <p class="mb-0"><strong>E-mail:</strong> {{ $orcamento->cliente->email ?? '-' }}</p>
            </div>
            <div class="col-md-6">
                <h6 class="font-weight-bold border-bottom pb-2 text-info">Dados do Veículo</h6>
                <p class="mb-1"><strong>Placa:</strong> <span class="badge badge-dark">{{ $orcamento->veiculo->placa ?? '-' }}</span></p>
                <p class="mb-1"><strong>Modelo:</strong> {{ $orcamento->veiculo->marca ?? '-' }} {{ $orcamento->veiculo->modelo ?? '-' }}</p>
                <p class="mb-1"><strong>Ano:</strong> {{ $orcamento->veiculo->ano_fabricacao ?? '-' }}/{{ $orcamento->veiculo->ano_modelo ?? '-' }}</p>
                <p class="mb-0"><strong>KM Atual:</strong> {{ number_format($orcamento->veiculo->quilometragem_atual ?? 0, 0, ',', '.') }} km</p>
            </div>
        </div>

        @if($orcamento->observacoes)
        <div class="alert alert-light border mb-4">
            <strong>Relato do Cliente / Observações:</strong><br>
            {{ $orcamento->observacoes }}
        </div>
        @endif

        <!-- Itens do Orçamento -->
        <h6 class="font-weight-bold border-bottom pb-2 mb-3">Detalhamento de Peças e Serviços</h6>
        <div class="table-responsive mb-4">
            <table class="table table-bordered">
                <thead class="thead-light">
                    <tr>
                        <th>Tipo</th>
                        <th>Descrição</th>
                        <th class="text-center">Quantidade</th>
                        <th class="text-right">Valor Unitário</th>
                        <th class="text-right">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orcamento->itens as $item)
                    <tr>
                        <td>
                            @if($item->tipo === 'peca')
                                <span class="badge badge-warning text-dark"><i class="fas fa-cog mr-1"></i> Peça</span>
                            @else
                                <span class="badge badge-info"><i class="fas fa-wrench mr-1"></i> Serviço</span>
                            @endif
                        </td>
                        <td>{{ $item->descricao }}</td>
                        <td class="text-center">{{ $item->quantidade }}</td>
                        <td class="text-right">R$ {{ number_format($item->valor_unitario, 2, ',', '.') }}</td>
                        <td class="text-right font-weight-bold">R$ {{ number_format($item->subtotal, 2, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="4" class="text-right">Total em Peças:</td>
                        <td class="text-right font-weight-bold">R$ {{ number_format($orcamento->valor_pecas, 2, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td colspan="4" class="text-right">Total em Mão de Obra / Serviços:</td>
                        <td class="text-right font-weight-bold">R$ {{ number_format($orcamento->valor_servicos, 2, ',', '.') }}</td>
                    </tr>
                    @if($orcamento->valor_desconto > 0)
                    <tr class="text-danger">
                        <td colspan="4" class="text-right">Desconto Aplicado:</td>
                        <td class="text-right font-weight-bold">- R$ {{ number_format($orcamento->valor_desconto, 2, ',', '.') }}</td>
                    </tr>
                    @endif
                    <tr class="table-success">
                        <td colspan="4" class="text-right font-weight-bold h5 mb-0">Valor Total do Orçamento:</td>
                        <td class="text-right font-weight-bold h5 mb-0">R$ {{ number_format($orcamento->valor_total, 2, ',', '.') }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <!-- Ações do Orçamento -->
        @if($orcamento->status === 'pendente')
        <div class="card bg-light p-3">
            <h6 class="font-weight-bold mb-2">Ações de Aprovação</h6>
            <div class="d-flex">
                <form action="{{ route('orcamentos.aprovar', $orcamento->id) }}" method="POST" class="mr-2">
                    @csrf
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-thumbs-up mr-1"></i> Aprovar Orçamento
                    </button>
                </form>

                <form action="{{ route('orcamentos.rejeitar', $orcamento->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-outline-danger">
                        <i class="fas fa-times mr-1"></i> Rejeitar Orçamento
                    </button>
                </form>
            </div>
        </div>
        @endif

        @if($orcamento->status === 'aprovado')
        <div class="card bg-success text-white p-3">
            <h5 class="mb-2"><i class="fas fa-check-circle mr-2"></i> Orçamento Aprovado!</h5>
            <p class="mb-3">Você pode converter este orçamento diretamente em uma Ordem de Serviço em execução.</p>
            <form action="{{ route('orcamentos.converter', $orcamento->id) }}" method="POST" class="form-inline">
                @csrf
                <div class="form-group mr-2">
                    <label for="km" class="mr-2 text-white font-weight-bold">KM de Entrada:</label>
                    <input type="number" name="quilometragem_entrada" id="km" class="form-control" value="{{ $orcamento->veiculo->quilometragem_atual }}" required>
                </div>
                <button type="submit" class="btn btn-warning font-weight-bold">
                    <i class="fas fa-wrench mr-1"></i> Gerar Ordem de Serviço Agora
                </button>
            </form>
        </div>
        @endif

        @if($orcamento->status === 'convertido_os' && $orcamento->ordemServico)
        <div class="alert alert-info">
            <i class="fas fa-info-circle mr-1"></i> Este orçamento foi convertido na <strong>Ordem de Serviço {{ $orcamento->ordemServico->numero }}</strong>.
            <a href="{{ route('ordens_servico.detalhes', $orcamento->ordemServico->id) }}" class="btn btn-sm btn-outline-primary ml-2">Acessar O.S.</a>
        </div>
        @endif
    </div>
</div>
@endsection
