<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('xp_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            // Log de eventos, não contador em users.xp — cada ponto é
            // auditável e reconstruível a partir daqui (RF04).
            $table->enum('source', ['checkin', 'streak', 'goal', 'ppc_step']);
            $table->integer('points');
            // Referência informativa (id do check-in, da meta, da etapa do
            // PPC...), não uma foreign key real: a origem varia de tabela
            // conforme `source`, e não há necessidade de navegar essa
            // relação de volta — só de auditar de onde o ponto veio.
            $table->unsignedBigInteger('reference_id')->nullable();
            // Somente created_at: evento é imutável, não existe "atualizar
            // XP" — existe "lançar um novo evento".
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('xp_events');
    }
};
