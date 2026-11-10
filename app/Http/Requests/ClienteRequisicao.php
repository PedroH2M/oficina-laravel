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
            'cpf_cnpj' => [
                'required',
                'string',
                'max:20',
                'unique:clientes,cpf_cnpj,' . $id,
                function ($attribute, $value, $fail) {
                    $tamanho = strlen($value);
                    if ($tamanho === 11) {
                        if (!$this->validarCpf($value)) {
                            $fail('O CPF informado é inválido.');
                        }
                    } elseif ($tamanho === 14) {
                        if (!$this->validarCnpj($value)) {
                            $fail('O CNPJ informado é inválido.');
                        }
                    } else {
                        $fail('O campo CPF/CNPJ deve conter exatamente 11 dígitos numéricos para CPF ou 14 dígitos para CNPJ.');
                    }
                },
            ],
            'telefone' => 'required|string|max:20',
            'email' => 'nullable|email|max:191',
            'endereco' => 'nullable|string|max:191',
            'cidade' => 'nullable|string|max:100',
            'estado' => 'nullable|string|size:2',
            'cep' => 'nullable|string|max:10',
            'observacoes' => 'nullable|string',
        ];
    }

    private function validarCpf(string $cpf): bool
    {
        if (strlen($cpf) !== 11) {
            return false;
        }

        // Bloqueia dígitos repetidos conhecidos (ex: 11111111111)
        if (preg_match('/^(\d)\1{10}$/', $cpf)) {
            return false;
        }

        // Validação dos dois dígitos verificadores
        for ($t = 9; $t < 11; $t++) {
            $soma = 0;
            for ($c = 0; $c < $t; $c++) {
                $soma += (int) $cpf[$c] * (($t + 1) - $c);
            }
            $digito = ((10 * $soma) % 11) % 10;
            if ((int) $cpf[$t] !== $digito) {
                return false;
            }
        }

        return true;
    }

    private function validarCnpj(string $cnpj): bool
    {
        if (strlen($cnpj) !== 14) {
            return false;
        }

        // Bloqueia dígitos repetidos conhecidos
        if (preg_match('/^(\d)\1{13}$/', $cnpj)) {
            return false;
        }

        // Primeiro dígito verificador
        $pesos1 = [5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2];
        $soma1 = 0;
        for ($i = 0; $i < 12; $i++) {
            $soma1 += (int) $cnpj[$i] * $pesos1[$i];
        }
        $resto1 = $soma1 % 11;
        $dv1 = ($resto1 < 2) ? 0 : 11 - $resto1;
        if ((int) $cnpj[12] !== $dv1) {
            return false;
        }

        // Segundo dígito verificador
        $pesos2 = [6, 5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2];
        $soma2 = 0;
        for ($i = 0; $i < 13; $i++) {
            $soma2 += (int) $cnpj[$i] * $pesos2[$i];
        }
        $resto2 = $soma2 % 11;
        $dv2 = ($resto2 < 2) ? 0 : 11 - $resto2;
        if ((int) $cnpj[13] !== $dv2) {
            return false;
        }

        return true;
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
