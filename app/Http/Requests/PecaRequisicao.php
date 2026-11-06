<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PecaRequisicao extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $id = $this->route('peca') ?? $this->route('id');

        return [
            'codigo' => 'required|string|max:50|unique:pecas,codigo,' . $id,
            'nome' => 'required|string|max:150',
            'descricao' => 'nullable|string',
            'preco_custo' => 'required|numeric|min:0',
            'preco_venda' => 'required|numeric|min:0',
            'quantidade_estoque' => 'required|integer|min:0',
            'estoque_minimo' => 'required|integer|min:0',
            'unidade_medida' => 'required|string|max:10',
        ];
    }

    public function messages()
    {
        return [
            'codigo.required' => 'O código da peça é obrigatório.',
            'codigo.unique' => 'Já existe uma peça cadastrada com este código.',
            'nome.required' => 'O nome da peça é obrigatório.',
            'preco_venda.required' => 'O preço de venda é obrigatório.',
            'quantidade_estoque.required' => 'A quantidade em estoque é obrigatória.',
        ];
    }
}
