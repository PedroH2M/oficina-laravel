<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cliente extends Model
{
    use SoftDeletes;

    protected $table = 'clientes';

    protected $fillable = [
        'nome',
        'cpf_cnpj',
        'telefone',
        'email',
        'endereco',
        'cidade',
        'estado',
        'cep',
        'observacoes',
    ];

    public function veiculos()
    {
        return $this->hasMany(Veiculo::class, 'cliente_id');
    }

    public function orcamentos()
    {
        return $this->hasMany(Orcamento::class, 'cliente_id');
    }

    public function ordensServico()
    {
        return $this->hasMany(OrdemServico::class, 'cliente_id');
    }
}
