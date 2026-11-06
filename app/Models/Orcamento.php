<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Orcamento extends Model
{
    use SoftDeletes;

    protected $table = 'orcamentos';

    protected $fillable = [
        'numero',
        'cliente_id',
        'veiculo_id',
        'usuario_id',
        'status', // pendente, aprovado, rejeitado, convertido_os
        'data_validade',
        'valor_pecas',
        'valor_servicos',
        'valor_desconto',
        'valor_total',
        'observacoes',
    ];

    protected $casts = [
        'data_validade' => 'date',
        'valor_pecas' => 'decimal:2',
        'valor_servicos' => 'decimal:2',
        'valor_desconto' => 'decimal:2',
        'valor_total' => 'decimal:2',
    ];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'cliente_id');
    }

    public function veiculo()
    {
        return $this->belongsTo(Veiculo::class, 'veiculo_id');
    }

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }

    public function itens()
    {
        return $this->hasMany(ItemOrcamento::class, 'orcamento_id');
    }

    public function ordemServico()
    {
        return $this->hasOne(OrdemServico::class, 'orcamento_id');
    }
}
