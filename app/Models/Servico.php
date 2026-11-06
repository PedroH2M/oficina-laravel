<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Servico extends Model
{
    use SoftDeletes;

    protected $table = 'servicos';

    protected $fillable = [
        'codigo',
        'nome',
        'descricao',
        'preco',
        'tempo_estimado_minutos',
    ];

    protected $casts = [
        'preco' => 'decimal:2',
        'tempo_estimado_minutos' => 'integer',
    ];
}
