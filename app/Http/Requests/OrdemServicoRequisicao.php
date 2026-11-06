<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrdemServicoRequisicao extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'cliente_id' => 'required|exists:clientes,id',
            'veiculo_id' => 'required|exists:veiculos,id',
            'usuario_mecanico_id' => 'nullable|exists:usuarios,id',
            'quilometragem_entrada' => 'required|integer|min:0',
            'data_previsao' => 'nullable|date',
            'diagnostico_defeito' => 'required|string',
            'valor_desconto' => 'nullable|numeric|min:0',
            'itens' => 'required|array|min:1',
            'itens.*.tipo' => 'required|in:peca,servico',
            'itens.*.peca_id' => 'nullable|exists:pecas,id',
            'itens.*.descricao' => 'required|string|max:191',
            'itens.*.quantidade' => 'required|integer|min:1',
            'itens.*.valor_unitario' => 'required|numeric|min:0',
        ];
    }

    public function messages()
    {
        return [
            'cliente_id.required' => 'O cliente é obrigatório.',
            'veiculo_id.required' => 'O veículo é obrigatório.',
            'quilometragem_entrada.required' => 'A quilometragem de entrada é obrigatória.',
            'diagnostico_defeito.required' => 'O diagnóstico ou relato do defeito é obrigatório.',
            'itens.required' => 'Adicione pelo menos uma peça ou serviço na Ordem de Serviço.',
        ];
    }
}
