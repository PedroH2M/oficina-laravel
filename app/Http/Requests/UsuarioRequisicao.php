<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UsuarioRequisicao extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $id = $this->route('usuario') ?? $this->route('id');

        $regras = [
            'nome' => 'required|string|max:191',
            'email' => 'required|email|max:191|unique:usuarios,email,' . $id,
            'perfil' => 'required|in:administrador,mecanico,atendente',
        ];

        if ($this->isMethod('POST')) {
            $regras['senha'] = 'required|string|min:6';
        } else {
            $regras['senha'] = 'nullable|string|min:6';
        }

        return $regras;
    }

    public function messages()
    {
        return [
            'nome.required' => 'O nome do funcionário é obrigatório.',
            'email.required' => 'O e-mail de acesso é obrigatório.',
            'email.unique' => 'Este e-mail já está em uso por outro usuário.',
            'senha.required' => 'A senha de acesso é obrigatória.',
            'senha.min' => 'A senha deve conter no mínimo 6 caracteres.',
            'perfil.required' => 'Selecione o cargo/perfil do funcionário.',
        ];
    }
}
