<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Usuario extends Authenticatable
{
    use Notifiable;

    protected $table = 'usuarios';

    protected $fillable = [
        'nome',
        'email',
        'senha',
        'perfil', // administrador, mecanico, atendente
    ];

    protected $hidden = [
        'senha',
        'remember_token',
    ];

    protected $casts = [
        'email_verificado_em' => 'datetime',
    ];

    // O Laravel Auth espera getAuthPassword
    public function getAuthPassword()
    {
        return $this->senha;
    }

    public function orcamentos()
    {
        return $this->hasMany(Orcamento::class, 'usuario_id');
    }

    public function ordensServicoResponsavel()
    {
        return $this->hasMany(OrdemServico::class, 'usuario_mecanico_id');
    }

    public function ehAdministrador()
    {
        return $this->perfil === 'administrador';
    }

    public function ehMecanico()
    {
        return $this->perfil === 'mecanico';
    }
}
