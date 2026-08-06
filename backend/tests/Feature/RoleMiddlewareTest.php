<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

/**
 * RF11 / RNF01 — RBAC: o que cada papel pode acessar não depende só do
 * front-end esconder um botão, depende do middleware barrar a requisição.
 *
 * Registra uma rota de uso exclusivo do teste (existe só durante a
 * execução, não polui routes/api.php) para exercitar o middleware
 * `role` isoladamente, sem depender de nenhuma funcionalidade de negócio
 * já estar construída.
 */
class RoleMiddlewareTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Route::middleware(['auth:sanctum', 'role:gestor,admin'])
            ->get('/_teste/somente-gestor-ou-admin', fn () => response()->json(['ok' => true]));
    }

    public function test_colaborador_recebe_403_em_rota_de_gestor(): void
    {
        $user = User::factory()->colaborador()->create();

        $this->actingAs($user, 'sanctum')
            ->getJson('/_teste/somente-gestor-ou-admin')
            ->assertForbidden();
    }

    public function test_gestor_acessa_a_mesma_rota(): void
    {
        $user = User::factory()->gestor()->create();

        $this->actingAs($user, 'sanctum')
            ->getJson('/_teste/somente-gestor-ou-admin')
            ->assertOk();
    }

    public function test_admin_tambem_acessa(): void
    {
        $user = User::factory()->admin()->create();

        $this->actingAs($user, 'sanctum')
            ->getJson('/_teste/somente-gestor-ou-admin')
            ->assertOk();
    }

    public function test_requisicao_sem_autenticacao_e_barrada_antes_do_papel(): void
    {
        $this->getJson('/_teste/somente-gestor-ou-admin')->assertUnauthorized();
    }
}
