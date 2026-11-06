<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ItemOrcamento extends Model
{
    protected $table = 'itens_orcamento';

    protected $fillable = [
        'orcamento_id',
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

    public function orcamento()
    {
        return $this->belongsTo(Orcamento::class, 'orcamento_id');
    }

    public function peca()
    {
        return $this->belongsTo(Peca::class, 'peca_id');
    }
}
