<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Usuario;

class AutenticacaoController extends Controller
{
    public function mostrarLogin()
    {
        return view('autenticacao.login');
    }

    public function entrar(Request $request)
    {
        $credenciais = $request->validate([
            'email' => 'required|email',
            'senha' => 'required',
        ]);

        $usuario = Usuario::where('email', $credenciais['email'])->first();

        if ($usuario && Hash::check($credenciais['senha'], $usuario->senha)) {
            Auth::login($usuario, (bool) $request->filled('lembrar'));
            $request->session()->regenerate();
            return redirect()->intended('/painel');
        }

        return back()->withErrors([
            'email' => 'E-mail ou senha informados estão incorretos.',
        ])->withInput($request->only('email'));
    }

    public function sair(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
