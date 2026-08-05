<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_badges', function (Blueprint $table) {
            $table->id();
            // Denormalizado a partir de users.organization_id, pelo mesmo
            // motivo de career_plan_steps: a RLS precisa da coluna aqui.
            $table->foreignId('organization_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('badge_id')->constrained()->cascadeOnDelete();
            // Concessão é um evento imutável: só awarded_at, sem updated_at.
            $table->timestamp('awarded_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_badges');
    }
};
