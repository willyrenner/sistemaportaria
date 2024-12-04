<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('registros_saidas', function (Blueprint $table) {
            $table->id();
            $table->timestamp('solicitacao')->nullable();
            $table->timestamp('permissao')->nullable();
            $table->timestamp('saida')->nullable();
            $table->unsignedBigInteger('aluno_id');
            $table->unsignedBigInteger('funcionario_id');
            $table->string('porteiro_id')->nullable();
            $table->timestamps();
            
            $table->foreign('aluno_id')->references('id')->on('alunos')->onDelete('cascade');
            $table->foreign('funcionario_id')->references('id')->on('funcionarios')->onDelete('cascade');
            $table->foreign('porteiro_id')->references('id')->on('porteiros')->onDelete('cascade');
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('registro_saidas');
    }
};
