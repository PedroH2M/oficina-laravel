<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrdemServico extends Model
{
    use SoftDeletes;

    protected $table = 'ordens_servico';

    protected $fillable = [
        'numero',
        'cliente_id',
        'veiculo_id',
        'orcamento_id',
        'usuario_mecanico_id',
        'status', // aberta, em_diagnostico, aguardando_pecas, em_execucao, finalizada, cancelada
        'data_abertura',
        'data_previsao',
        'data_finalizacao',
        'quilometragem_entrada',
        'diagnostico_defeito',
        'solucao_aplicada',
        'valor_pecas',
        'valor_servicos',
        'valor_desconto',
        'valor_total',
    ];

    protected $casts = [
        'data_abertura' => 'datetime',
        'data_previsao' => 'datetime',
        'data_finalizacao' => 'datetime',
        'quilometragem_entrada' => 'integer',
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

    public function orcamento()
    {
        return $this->belongsTo(Orcamento::class, 'orcamento_id');
    }

    public function mecanicoResponsavel()
    {
        return $this->belongsTo(Usuario::class, 'usuario_mecanico_id');
    }

    public function itens()
    {
        return $this->hasMany(ItemOrdemServico::class, 'ordem_servico_id');
    }

    public function historicos()
    {
        return $this->hasMany(HistoricoVeiculo::class, 'ordem_servico_id');
    }
}
