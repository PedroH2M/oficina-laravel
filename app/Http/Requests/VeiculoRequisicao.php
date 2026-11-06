<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VeiculoRequisicao extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    protected function prepareForValidation()
    {
        if ($this->has('placa')) {
            $this->merge([
                'placa' => strtoupper(trim(str_replace(['-', ' '], '', $this->placa)))
            ]);
        }
    }

    public function rules()
    {
        $id = $this->route('veiculo') ?? $this->route('id');

        return [
            'cliente_id' => 'required|exists:clientes,id',
            'placa' => 'required|string|max:10|unique:veiculos,placa,' . $id,
            'marca' => 'required|string|max:50',
            'modelo' => 'required|string|max:50',
            'ano_fabricacao' => 'required|integer|min:1950|max:' . (date('Y') + 1),
            'ano_modelo' => 'required|integer|min:1950|max:' . (date('Y') + 2),
            'cor' => 'required|string|max:30',
            'chassi' => 'nullable|string|max:30',
            'quilometragem_atual' => 'required|integer|min:0',
        ];
    }

    public function messages()
    {
        return [
            'cliente_id.required' => 'Selecione o cliente proprietário do veículo.',
            'cliente_id.exists' => 'O cliente selecionado não existe.',
            'placa.required' => 'A placa do veículo é obrigatória.',
            'placa.unique' => 'Esta placa já está cadastrada para outro veículo.',
            'marca.required' => 'A marca do veículo é obrigatória.',
            'modelo.required' => 'O modelo do veículo é obrigatório.',
            'quilometragem_atual.required' => 'A quilometragem atual é obrigatória.',
        ];
    }
}
