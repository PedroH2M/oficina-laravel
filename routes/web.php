<?php

use Illuminate\Support\Facades\Route;

// Rotas Públicas e Autenticação
Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', 'AutenticacaoController@mostrarLogin')->name('login')->middleware('guest');
Route::post('/login', 'AutenticacaoController@entrar')->name('login.entrar')->middleware('guest');
Route::post('/logout', 'AutenticacaoController@sair')->name('logout')->middleware('auth');

// Rotas Protegidas (Requer Autenticação)
Route::middleware(['auth'])->group(function () {
    // Painel Principal / Dashboard
    Route::get('/painel', 'PainelController@index')->name('painel');

    // Gestão de Funcionários / Usuários
    Route::prefix('usuarios')->name('usuarios.')->group(function () {
        Route::get('/', 'UsuarioController@index')->name('index');
        Route::get('/criar', 'UsuarioController@criar')->name('criar');
        Route::post('/', 'UsuarioController@armazenar')->name('armazenar');
        Route::get('/{id}/editar', 'UsuarioController@editar')->name('editar');
        Route::put('/{id}', 'UsuarioController@atualizar')->name('atualizar');
        Route::delete('/{id}', 'UsuarioController@excluir')->name('excluir');
    });

    // Gestão de Clientes
    Route::prefix('clientes')->name('clientes.')->group(function () {
        Route::get('/', 'ClienteController@index')->name('index');
        Route::get('/criar', 'ClienteController@criar')->name('criar');
        Route::post('/', 'ClienteController@armazenar')->name('armazenar');
        Route::get('/{id}/editar', 'ClienteController@editar')->name('editar');
        Route::put('/{id}', 'ClienteController@atualizar')->name('atualizar');
        Route::delete('/{id}', 'ClienteController@excluir')->name('excluir');
    });

    // Gestão de Veículos e Histórico
    Route::prefix('veiculos')->name('veiculos.')->group(function () {
        Route::get('/', 'VeiculoController@index')->name('index');
        Route::get('/criar', 'VeiculoController@criar')->name('criar');
        Route::post('/', 'VeiculoController@armazenar')->name('armazenar');
        Route::get('/{id}/historico', 'VeiculoController@historico')->name('historico');
    });

    // Catálogo de Serviços & Mão de Obra
    Route::prefix('servicos')->name('servicos.')->group(function () {
        Route::get('/', 'ServicoController@index')->name('index');
        Route::get('/criar', 'ServicoController@criar')->name('criar');
        Route::post('/', 'ServicoController@armazenar')->name('armazenar');
        Route::get('/{id}/editar', 'ServicoController@editar')->name('editar');
        Route::put('/{id}', 'ServicoController@atualizar')->name('atualizar');
        Route::delete('/{id}', 'ServicoController@excluir')->name('excluir');
    });

    // Controle de Peças e Estoque
    Route::prefix('pecas')->name('pecas.')->group(function () {
        Route::get('/', 'PecaController@index')->name('index');
        Route::get('/criar', 'PecaController@criar')->name('criar');
        Route::post('/', 'PecaController@armazenar')->name('armazenar');
        Route::get('/{id}/editar', 'PecaController@editar')->name('editar');
        Route::put('/{id}', 'PecaController@atualizar')->name('atualizar');
        Route::delete('/{id}', 'PecaController@excluir')->name('excluir');
    });

    // Orçamentos
    Route::prefix('orcamentos')->name('orcamentos.')->group(function () {
        Route::get('/', 'OrcamentoController@index')->name('index');
        Route::get('/criar', 'OrcamentoController@criar')->name('criar');
        Route::post('/', 'OrcamentoController@armazenar')->name('armazenar');
        Route::get('/{id}', 'OrcamentoController@detalhes')->name('detalhes');
        Route::post('/{id}/aprovar', 'OrcamentoController@aprovar')->name('aprovar');
        Route::post('/{id}/rejeitar', 'OrcamentoController@rejeitar')->name('rejeitar');
        Route::post('/{id}/converter', 'OrcamentoController@converterEmOS')->name('converter');
    });

    // Ordens de Serviço (OS)
    Route::prefix('ordens-servico')->name('ordens_servico.')->group(function () {
        Route::get('/', 'OrdemServicoController@index')->name('index');
        Route::get('/criar', 'OrdemServicoController@criar')->name('criar');
        Route::post('/', 'OrdemServicoController@armazenar')->name('armazenar');
        Route::get('/{id}', 'OrdemServicoController@detalhes')->name('detalhes');
        Route::patch('/{id}/status', 'OrdemServicoController@atualizarStatus')->name('status');
        Route::post('/{id}/finalizar', 'OrdemServicoController@finalizar')->name('finalizar');
        Route::post('/{id}/cancelar', 'OrdemServicoController@cancelar')->name('cancelar');
    });
});
