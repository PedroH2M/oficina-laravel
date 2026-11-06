<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CriarTabelaItensOrdemServico extends Migration
{
    public function up()
    {
        Schema::create('itens_ordem_servico', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ordem_servico_id')->constrained('ordens_servico')->onDelete('cascade');
            $table->foreignId('peca_id')->nullable()->constrained('pecas')->onDelete('set null');
            $table->enum('tipo', ['peca', 'servico'])->default('peca');
            $table->string('descricao');
            $table->integer('quantidade')->default(1);
            $table->decimal('valor_unitario', 10, 2)->default(0.00);
            $table->decimal('subtotal', 10, 2)->default(0.00);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('itens_ordem_servico');
    }
}
