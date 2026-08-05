<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('career_plans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->enum('status', ['nao_iniciado', 'em_andamento', 'concluido'])
                ->default('nao_iniciado');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('career_plans');
    }
};
