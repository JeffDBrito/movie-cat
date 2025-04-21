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
        Schema::create('filme_genero', function (Blueprint $table) {
            $table->foreignId('filme_id')->constrained('filmes')->onDelete('cascade');
            $table->foreignId('genero_id')->constrained('generos')->onDelete('cascade');

            $table->primary(['filme_id', 'genero_id']);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tabela_filme_genero');
    }
};
