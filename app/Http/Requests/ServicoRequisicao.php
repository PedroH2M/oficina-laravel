<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ServicoRequisicao extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $id = $this->route('servico') ?? $this->route('id');

        return [
            'codigo' => 'required|string|max:50|unique:servicos,codigo,' . $id,
            'nome' => 'required|string|max:150',
            'descricao' => 'nullable|string',
            'preco' => 'required|numeric|min:0',
            'tempo_estimado_minutos' => 'nullable|integer|min:0',
        ];
    }

    public function messages()
    {
        return [
            'codigo.required' => 'O código do serviço é obrigatório.',
            'codigo.unique' => 'Já existe um serviço cadastrado com este código.',
            'nome.required' => 'O nome do serviço é obrigatório.',
            'preco.required' => 'O preço padrão da mão de obra/serviço é obrigatório.',
        ];
    }
}
