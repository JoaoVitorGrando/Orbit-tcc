<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mood_checkins', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->constrained()->cascadeOnDelete();
            $table->foreignId('branch_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            // 1 = triste, 2 = neutro, 3 = feliz. Não é escala psicométrica
            // validada (não é a UWES) — é indicador de tendência, por desenho.
            $table->unsignedTinyInteger('mood');
            // Resolvida no timezone da organização, nunca no do servidor.
            $table->date('checkin_date');
            $table->timestamps();

            // RF01: um check-in por usuário por dia. Garantido pelo banco,
            // não só pela aplicação — sobrevive a requisições concorrentes.
            $table->unique(['user_id', 'checkin_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mood_checkins');
    }
};
