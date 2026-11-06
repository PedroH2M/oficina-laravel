<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CriarTabelaOrdensServico extends Migration
{
    public function up()
    {
        Schema::create('ordens_servico', function (Blueprint $table) {
            $table->id();
            $table->string('numero', 30)->unique();
            $table->foreignId('cliente_id')->constrained('clientes')->onDelete('cascade');
            $table->foreignId('veiculo_id')->constrained('veiculos')->onDelete('cascade');
            $table->foreignId('orcamento_id')->nullable()->constrained('orcamentos')->onDelete('set null');
            $table->foreignId('usuario_mecanico_id')->nullable()->constrained('usuarios')->onDelete('set null');
            $table->enum('status', [
                'aberta',
                'em_diagnostico',
                'aguardando_pecas',
                'em_execucao',
                'finalizada',
                'cancelada'
            ])->default('aberta');
            $table->dateTime('data_abertura');
            $table->dateTime('data_previsao')->nullable();
            $table->dateTime('data_finalizacao')->nullable();
            $table->integer('quilometragem_entrada');
            $table->text('diagnostico_defeito');
            $table->text('solucao_aplicada')->nullable();
            $table->decimal('valor_pecas', 10, 2)->default(0.00);
            $table->decimal('valor_servicos', 10, 2)->default(0.00);
            $table->decimal('valor_desconto', 10, 2)->default(0.00);
            $table->decimal('valor_total', 10, 2)->default(0.00);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('ordens_servico');
    }
}
