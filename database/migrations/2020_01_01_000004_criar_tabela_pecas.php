<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CriarTabelaPecas extends Migration
{
    public function up()
    {
        Schema::create('pecas', function (Blueprint $table) {
            $table->id();
            $table->string('codigo', 50)->unique();
            $table->string('nome', 150);
            $table->text('descricao')->nullable();
            $table->decimal('preco_custo', 10, 2)->default(0.00);
            $table->decimal('preco_venda', 10, 2)->default(0.00);
            $table->integer('quantidade_estoque')->default(0);
            $table->integer('estoque_minimo')->default(5);
            $table->string('unidade_medida', 10)->default('UN');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('pecas');
    }
}
