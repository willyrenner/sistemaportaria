<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('registros_saidas', function (Blueprint $table) {
            $table->id();
            $table->enum('tipo', ['entrada', 'saida']); // Adiciona a coluna 'tipo'
            $table->timestamp('solicitacao')->nullable();
            $table->string('permissao')->nullable();
            $table->timestamp('saida')->nullable();
            $table->text('motivo')->nullable();
            $table->text('observacao_responsavel')->nullable();

            $table->unsignedBigInteger('aluno_id');
            $table->unsignedBigInteger('funcionario_id')->nullable();
            $table->unsignedBigInteger('porteiro_id')->nullable(); // Permitir valores nulos
            $table->timestamps();

            // Definir as chaves estrangeiras
            $table->foreign('aluno_id')->references('id')->on('alunos')->onDelete('cascade');
            $table->foreign('funcionario_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('porteiro_id')->references('id')->on('porteiros')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('registros_saidas');
    }
};
