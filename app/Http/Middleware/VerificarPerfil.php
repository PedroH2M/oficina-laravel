<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class VerificarPerfil
{
    /**
     * Manipula a requisição verificando se o usuário possui os perfis autorizados.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string[]  ...$perfis
     * @return mixed
     */
    public function handle($request, Closure $next, ...$perfis)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $usuario = Auth::user();

        // Se o usuário possui um dos perfis exigidos
        if (in_array($usuario->perfil, $perfis)) {
            return $next($request);
        }

        return redirect()->route('painel')
            ->with('erro', 'Acesso Restrito: Seu perfil de usuário (' . ucfirst($usuario->perfil) . ') não tem permissão para acessar esta área.');
    }
}
