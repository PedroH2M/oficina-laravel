<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ClienteRequisicao extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    protected function prepareForValidation()
    {
        if ($this->has('cpf_cnpj')) {
            $this->merge([
                'cpf_cnpj' => preg_replace('/[^0-9]/', '', $this->cpf_cnpj)
            ]);
        }
    }

    public function rules()
    {
        $id = $this->route('cliente') ?? $this->route('id');

        return [
            'nome' => 'required|string|max:191',
            'cpf_cnpj' => 'required|string|max:20|unique:clientes,cpf_cnpj,' . $id,
            'telefone' => 'required|string|max:20',
            'email' => 'nullable|email|max:191',
            'endereco' => 'nullable|string|max:191',
            'cidade' => 'nullable|string|max:100',
            'estado' => 'nullable|string|size:2',
            'cep' => 'nullable|string|max:10',
            'observacoes' => 'nullable|string',
        ];
    }

    public function messages()
    {
        return [
            'nome.required' => 'O nome do cliente é obrigatório.',
            'cpf_cnpj.required' => 'O CPF ou CNPJ é obrigatório.',
            'cpf_cnpj.unique' => 'Este CPF/CNPJ já está cadastrado para outro cliente.',
            'telefone.required' => 'O telefone de contato é obrigatório.',
            'email.email' => 'Informe um endereço de e-mail válido.',
        ];
    }
}
