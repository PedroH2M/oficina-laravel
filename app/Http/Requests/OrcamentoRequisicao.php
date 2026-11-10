<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrcamentoRequisicao extends FormRequest
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
            'data_validade' => 'required|date|after_or_equal:today',
            'valor_desconto' => 'nullable|numeric|min:0',
            'observacoes' => 'nullable|string',
            'itens' => 'required|array|min:1',
            'itens.*.tipo' => 'required|in:peca,servico',
            'itens.*.peca_id' => 'nullable|exists:pecas,id',
            'itens.*.descricao' => 'required|string|max:191',
            'itens.*.quantidade' => 'required|integer|min:1',
            'itens.*.valor_unitario' => 'required|numeric|gt:0',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $itens = $this->input('itens', []);
            $desconto = (float) $this->input('valor_desconto', 0);

            if ($desconto > 0 && is_array($itens)) {
                $totalItens = 0.0;
                foreach ($itens as $item) {
                    $qtd = isset($item['quantidade']) ? (float) $item['quantidade'] : 0.0;
                    $preco = isset($item['valor_unitario']) ? (float) $item['valor_unitario'] : 0.0;
                    if ($qtd > 0 && $preco > 0) {
                        $totalItens += $qtd * $preco;
                    }
                }

                if ($desconto > $totalItens) {
                    $validator->errors()->add('valor_desconto', 'O valor do desconto não pode ser superior ao valor total dos itens.');
                }
            }
        });
    }

    public function messages()
    {
        return [
            'cliente_id.required' => 'O cliente é obrigatório.',
            'veiculo_id.required' => 'O veículo é obrigatório.',
            'data_validade.required' => 'A data de validade do orçamento é obrigatória.',
            'valor_desconto.min' => 'O valor do desconto não pode ser negativo.',
            'itens.required' => 'Adicione pelo menos um item ou serviço ao orçamento.',
            'itens.*.descricao.required' => 'A descrição do item é obrigatória.',
            'itens.*.quantidade.min' => 'A quantidade deve ser de no mínimo 1.',
            'itens.*.valor_unitario.gt' => 'O valor unitário deve ser maior que zero.',
        ];
    }
}
