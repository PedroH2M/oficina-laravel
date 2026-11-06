<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Peca extends Model
{
    use SoftDeletes;

    protected $table = 'pecas';

    protected $fillable = [
        'codigo',
        'nome',
        'descricao',
        'preco_custo',
        'preco_venda',
        'quantidade_estoque',
        'estoque_minimo',
        'unidade_medida',
    ];

    protected $casts = [
        'preco_custo' => 'decimal:2',
        'preco_venda' => 'decimal:2',
        'quantidade_estoque' => 'integer',
        'estoque_minimo' => 'integer',
    ];

    public function precisaReposicao()
    {
        return $this->quantidade_estoque <= $this->estoque_minimo;
    }

    public function itensOrdemServico()
    {
        return $this->hasMany(ItemOrdemServico::class, 'peca_id');
    }

    public function itensOrcamento()
    {
        return $this->hasMany(ItemOrcamento::class, 'peca_id');
    }
}
