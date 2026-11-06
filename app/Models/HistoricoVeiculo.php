<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HistoricoVeiculo extends Model
{
    protected $table = 'historicos_veiculo';

    protected $fillable = [
        'veiculo_id',
        'ordem_servico_id',
        'data_evento',
        'quilometragem_registrada',
        'tipo_evento',
        'descricao',
    ];

    protected $casts = [
        'data_evento' => 'datetime',
    ];

    public function veiculo()
    {
        return $this->belongsTo(Veiculo::class, 'veiculo_id');
    }

    public function ordemServico()
    {
        return $this->belongsTo(OrdemServico::class, 'ordem_servico_id');
    }
}
