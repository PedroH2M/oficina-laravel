<?php

namespace App\Http\Controllers;

use App\Http\Requests\UsuarioRequisicao;
use App\Services\Contratos\UsuarioServicoInterface;
use Illuminate\Http\Request;

class UsuarioController extends Controller
{
    protected $usuarioServico;

    public function __construct(UsuarioServicoInterface $usuarioServico)
    {
        $this->usuarioServico = $usuarioServico;
    }

    public function index()
    {
        $usuarios = $this->usuarioServico->paginarUsuarios();
        return view('usuarios.index', compact('usuarios'));
    }

    public function criar()
    {
        return view('usuarios.criar');
    }

    public function armazenar(UsuarioRequisicao $request)
    {
        $this->usuarioServico->cadastrarUsuario($request->validated());
        return redirect()->route('usuarios.index')->with('sucesso', 'Funcionário/Usuário cadastrado com sucesso!');
    }

    public function editar($id)
    {
        $usuario = $this->usuarioServico->obterUsuario($id);
        if (!$usuario) {
            return redirect()->route('usuarios.index')->with('erro', 'Usuário não encontrado.');
        }
        return view('usuarios.editar', compact('usuario'));
    }

    public function atualizar(UsuarioRequisicao $request, $id)
    {
        $this->usuarioServico->atualizarUsuario($id, $request->validated());
        return redirect()->route('usuarios.index')->with('sucesso', 'Dados do funcionário atualizados com sucesso!');
    }

    public function excluir($id)
    {
        // Evitar que o usuário exclua a si mesmo
        if (auth()->id() == $id) {
            return redirect()->route('usuarios.index')->with('erro', 'Você não pode excluir sua própria conta conectada.');
        }

        $this->usuarioServico->excluirUsuario($id);
        return redirect()->route('usuarios.index')->with('sucesso', 'Funcionário removido com sucesso!');
    }
}
