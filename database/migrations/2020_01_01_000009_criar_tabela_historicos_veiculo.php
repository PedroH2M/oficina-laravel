<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CriarTabelaHistoricosVeiculo extends Migration
{
    public function up()
    {
        Schema::create('historicos_veiculo', function (Blueprint $table) {
            $table->id();
            $table->foreignId('veiculo_id')->constrained('veiculos')->onDelete('cascade');
            $table->foreignId('ordem_servico_id')->nullable()->constrained('ordens_servico')->onDelete('cascade');
            $table->dateTime('data_evento');
            $table->integer('quilometragem_registrada');
            $table->string('tipo_evento', 50); // abertura_os, finalizacao_os, troca_peca, observacao
            $table->text('descricao');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('historicos_veiculo');
    }
}
