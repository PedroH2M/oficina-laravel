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
            'quilometragem_entrada.required' => 'A quilometragem de entrada é obrigatória.',
            'quilometragem_entrada.min' => 'A quilometragem de entrada não pode ser negativa.',
            'valor_desconto.min' => 'O valor do desconto não pode ser negativo.',
            'diagnostico_defeito.required' => 'O diagnóstico ou relato do defeito é obrigatório.',
            'itens.required' => 'Adicione pelo menos uma peça ou serviço na Ordem de Serviço.',
            'itens.*.quantidade.min' => 'A quantidade de cada item deve ser de no mínimo 1.',
            'itens.*.valor_unitario.gt' => 'O valor unitário deve ser maior que zero.',
        ];
    }
}
