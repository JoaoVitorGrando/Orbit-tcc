<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_competencies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->enum('kind', ['habilidade', 'curso', 'certificacao']);
            // Distingue o que o colaborador declarou por conta própria do
            // que veio automaticamente da conclusão de uma etapa do PPC.
            $table->enum('origin', ['autodeclarada', 'ppc']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_competencies');
    }
};
