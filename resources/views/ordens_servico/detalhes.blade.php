@extends('layouts.app')

@section('titulo', 'Ordem de Serviço ' . $ordemServico->numero)

@section('conteudo')
<div class="row mb-3">
    <div class="col-md-6">
        <h3 class="font-weight-bold">
            <i class="fas fa-clipboard-check text-primary mr-2"></i>O.S.: {{ $ordemServico->numero }}
        </h3>
    </div>
    <div class="col-md-6 text-right">
        <a href="{{ route('ordens_servico.index') }}" class="btn btn-outline-secondary mr-2">
            <i class="fas fa-arrow-left mr-1"></i> Voltar
        </a>
        <button onclick="window.print()" class="btn btn-outline-dark">
            <i class="fas fa-print mr-1"></i> Imprimir O.S.
        </button>
    </div>
</div>

<div class="card shadow-sm">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <div>
            <span class="text-muted small">Status da O.S.:</span>
            @if($ordemServico->status === 'aberta')
                <span class="badge badge-secondary p-2">Aberta</span>
            @elseif($ordemServico->status === 'em_diagnostico')
                <span class="badge badge-info p-2">Em Diagnóstico</span>
            @elseif($ordemServico->status === 'aguardando_pecas')
                <span class="badge badge-warning text-dark p-2">Aguardando Peças</span>
            @elseif($ordemServico->status === 'em_execucao')
                <span class="badge badge-primary p-2">Em Execução Mecânica</span>
            @elseif($ordemServico->status === 'finalizada')
                <span class="badge badge-success p-2">Finalizada e Entregue</span>
            @else
                <span class="badge badge-danger p-2">Cancelada</span>
            @endif
        </div>
        <div>
            <span class="text-muted small">Aberta em:</span>
            <strong>{{ $ordemServico->data_abertura->format('d/m/Y H:i') }}</strong>
        </div>
    </div>
    <div class="card-body">
        <!-- Informações Gerais -->
        <div class="row mb-4">
            <div class="col-md-4">
                <h6 class="font-weight-bold border-bottom pb-2 text-primary">Cliente</h6>
                <p class="mb-1"><strong>Nome:</strong> {{ $ordemServico->cliente->nome ?? '-' }}</p>
                <p class="mb-1"><strong>Telefone:</strong> {{ $ordemServico->cliente->telefone ?? '-' }}</p>
                <p class="mb-0"><strong>CPF/CNPJ:</strong> {{ $ordemServico->cliente->cpf_cnpj ?? '-' }}</p>
            </div>
            <div class="col-md-4">
                <h6 class="font-weight-bold border-bottom pb-2 text-info">Veículo</h6>
                <p class="mb-1"><strong>Placa:</strong> <span class="badge badge-dark">{{ $ordemServico->veiculo->placa ?? '-' }}</span></p>
                <p class="mb-1"><strong>Modelo:</strong> {{ $ordemServico->veiculo->marca ?? '-' }} {{ $ordemServico->veiculo->modelo ?? '-' }}</p>
                <p class="mb-0">
                    <a href="{{ route('veiculos.historico', $ordemServico->veiculo_id) }}" class="btn btn-sm btn-outline-info py-0">
                        <i class="fas fa-history mr-1"></i> Ver Histórico deste Veículo
                    </a>
                </p>
            </div>
            <div class="col-md-4">
                <h6 class="font-weight-bold border-bottom pb-2 text-dark">Execução</h6>
                <p class="mb-1"><strong>KM de Entrada:</strong> {{ number_format($ordemServico->quilometragem_entrada, 0, ',', '.') }} km</p>
                <p class="mb-1"><strong>Mecânico:</strong> {{ $ordemServico->mecanicoResponsavel->nome ?? 'Não atribuído' }}</p>
                @if($ordemServico->data_finalizacao)
                    <p class="mb-0 text-success"><strong>Finalizada em:</strong> {{ $ordemServico->data_finalizacao->format('d/m/Y H:i') }}</p>
                @endif
            </div>
        </div>

        <div class="alert alert-light border mb-4">
            <strong class="text-dark"><i class="fas fa-stethoscope mr-1"></i> Diagnóstico Inicial / Defeito Reclamado:</strong><br>
            {{ $ordemServico->diagnostico_defeito }}
        </div>

        @if($ordemServico->solucao_aplicada)
        <div class="alert alert-success border mb-4">
            <strong><i class="fas fa-check-circle mr-1"></i> Solução Aplicada e Procedimentos Realizados:</strong><br>
            {{ $ordemServico->solucao_aplicada }}
        </div>
        @endif

        <!-- Itens e Peças -->
        <h6 class="font-weight-bold border-bottom pb-2 mb-3">Peças e Mão de Obra da O.S.</h6>
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
                    @foreach($ordemServico->itens as $item)
                    <tr>
                        <td>
                            @if($item->tipo === 'peca')
                                <span class="badge badge-warning text-dark"><i class="fas fa-cog mr-1"></i> Peça</span>
                            @else
                                <span class="badge badge-info"><i class="fas fa-wrench mr-1"></i> Mão de Obra</span>
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
                        <td colspan="4" class="text-right">Total Peças:</td>
                        <td class="text-right font-weight-bold">R$ {{ number_format($ordemServico->valor_pecas, 2, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td colspan="4" class="text-right">Total Serviços:</td>
                        <td class="text-right font-weight-bold">R$ {{ number_format($ordemServico->valor_servicos, 2, ',', '.') }}</td>
                    </tr>
                    @if($ordemServico->valor_desconto > 0)
                    <tr class="text-danger">
                        <td colspan="4" class="text-right">Desconto:</td>
                        <td class="text-right font-weight-bold">- R$ {{ number_format($ordemServico->valor_desconto, 2, ',', '.') }}</td>
                    </tr>
                    @endif
                    <tr class="table-primary">
                        <td colspan="4" class="text-right font-weight-bold h5 mb-0">Total Final da O.S.:</td>
                        <td class="text-right font-weight-bold h5 mb-0">R$ {{ number_format($ordemServico->valor_total, 2, ',', '.') }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <!-- Painel de Mudança de Status e Finalização -->
        @if(!in_array($ordemServico->status, ['finalizada', 'cancelada']))
        <div class="card bg-light border p-3 mb-4">
            <h6 class="font-weight-bold text-dark mb-3"><i class="fas fa-tasks mr-1"></i> Fluxo de Trabalho da Ordem de Serviço</h6>
            <div class="row">
                <!-- Alterar Status Intermediário -->
                <div class="col-md-6 border-right">
                    <form action="{{ route('ordens_servico.status', $ordemServico->id) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <div class="form-group">
                            <label for="status">Atualizar Andamento:</label>
                            <select name="status" id="status" class="form-control">
                                <option value="aberta" {{ $ordemServico->status == 'aberta' ? 'selected' : '' }}>Aberta</option>
                                <option value="em_diagnostico" {{ $ordemServico->status == 'em_diagnostico' ? 'selected' : '' }}>Em Diagnóstico</option>
                                <option value="aguardando_pecas" {{ $ordemServico->status == 'aguardando_pecas' ? 'selected' : '' }}>Aguardando Peças</option>
                                <option value="em_execucao" {{ $ordemServico->status == 'em_execucao' ? 'selected' : '' }}>Em Execução</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary btn-sm">
                            <i class="fas fa-sync mr-1"></i> Atualizar Status
                        </button>
                    </form>
                </div>

                <!-- Finalizar com Laudo -->
                <div class="col-md-6">
                    <form action="{{ route('ordens_servico.finalizar', $ordemServico->id) }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="solucao_aplicada">Concluir Manutenção e Entregar Veículo:</label>
                            <textarea name="solucao_aplicada" id="solucao_aplicada" rows="2" class="form-control" placeholder="Descreva os serviços concluídos para registrar no Histórico do Veículo..." required></textarea>
                        </div>
                        <button type="submit" class="btn btn-success btn-sm font-weight-bold">
                            <i class="fas fa-check-double mr-1"></i> Concluir O.S. e Registrar no Histórico
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
