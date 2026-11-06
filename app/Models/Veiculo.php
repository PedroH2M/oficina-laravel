<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Veiculo extends Model
{
    use SoftDeletes;

    protected $table = 'veiculos';

    protected $fillable = [
        'cliente_id',
        'placa',
        'marca',
        'modelo',
        'ano_fabricacao',
        'ano_modelo',
        'cor',
        'chassi',
        'quilometragem_atual',
    ];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'cliente_id');
    }

    public function historicos()
    {
        return $this->hasMany(HistoricoVeiculo::class, 'veiculo_id')->orderBy('data_evento', 'desc');
    }

    public function ordensServico()
    {
        return $this->hasMany(OrdemServico::class, 'veiculo_id')->orderBy('data_abertura', 'desc');
    }

    public function orcamentos()
    {
        return $this->hasMany(Orcamento::class, 'veiculo_id');
    }

    public function getDescricaoCompletaAttribute()
    {
        return "{$this->marca} {$this->modelo} ({$this->placa})";
    }
}
