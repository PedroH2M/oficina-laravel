<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ItemOrdemServico extends Model
{
    protected $table = 'itens_ordem_servico';

    protected $fillable = [
        'ordem_servico_id',
        'peca_id',
        'tipo', // peca, servico
        'descricao',
        'quantidade',
        'valor_unitario',
        'subtotal',
    ];

    protected $casts = [
        'quantidade' => 'integer',
        'valor_unitario' => 'decimal:2',
        'subtotal' => 'decimal:2',
    ];

    public function ordemServico()
    {
        return $this->belongsTo(OrdemServico::class, 'ordem_servico_id');
    }

    public function peca()
    {
        return $this->belongsTo(Peca::class, 'peca_id');
    }
}
