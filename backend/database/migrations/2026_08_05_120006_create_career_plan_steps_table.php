<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('career_plan_steps', function (Blueprint $table) {
            $table->id();
            // Denormalizado a partir de career_plans.organization_id.
            // Necessário porque a política de RLS (Camada 2) filtra pela
            // coluna da própria tabela — não atravessa join até career_plans.
            $table->foreignId('organization_id')->constrained()->cascadeOnDelete();
            $table->foreignId('career_plan_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->unsignedSmallInteger('order');
            $table->unsignedInteger('xp_reward')->default(0);
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('career_plan_steps');
    }
};
