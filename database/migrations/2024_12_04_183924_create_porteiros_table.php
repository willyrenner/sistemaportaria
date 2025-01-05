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
        Schema::create('porteiros', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100); // Nome com limite de caracteres
            $table->string('email', 100)->unique(); // email com limite de caracteres
            $table->string('cpf', 20)->unique(); // cpf com limite de caracteres
            $table->string('matricula', 100)->unique(); // matricula com limite de caracteres
            $table->string('role'); // permissão do porteiro
            $table->string('turno'); // turno
            $table->string('password'); // Senha segura
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('porteiros');
    }
};
