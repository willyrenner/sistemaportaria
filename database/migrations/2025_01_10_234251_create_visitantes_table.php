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
        Schema::create('visitantes', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->string('cpf', 11);
            $table->enum('tipo', ['entrada', 'saida']);
            $table->timestamp('saida')->nullable();
            $table->string('motivo')->nullable();
            $table->timestamps();

            $table->unsignedBigInteger('funcionario_id')->nullable();
            $table->unsignedBigInteger('porteiro_id')->nullable(); // Permitir valores nulos

            $table->foreign('funcionario_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('porteiro_id')->references('id')->on('porteiros')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('visitantes');
    }
};
