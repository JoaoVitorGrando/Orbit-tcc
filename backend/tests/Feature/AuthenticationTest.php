<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_usuario_faz_login_com_credenciais_validas(): void
    {
        $user = User::factory()->create(['password' => bcrypt('12345678')]);

        $response = $this->postJson('/api/v1/login', [
            'email' => $user->email,
            'password' => '12345678',
        ]);

        $response->assertOk()
            ->assertJsonStructure(['user' => ['id', 'name', 'email', 'role'], 'token']);
    }

    public function test_login_falha_com_senha_incorreta(): void
    {
        $user = User::factory()->create(['password' => bcrypt('12345678')]);

        $response = $this->postJson('/api/v1/login', [
            'email' => $user->email,
            'password' => 'senha-errada',
        ]);

        $response->assertStatus(422);
    }

    public function test_token_valido_acessa_rota_protegida(): void
    {
        $user = User::factory()->create();
        $token = $user->createToken('teste')->plainTextToken;

        $response = $this->withHeader('Authorization', "Bearer {$token}")
            ->getJson('/api/v1/me');

        $response->assertOk()->assertJson(['id' => $user->id]);
    }

    public function test_rota_protegida_recusa_requisicao_sem_token(): void
    {
        $this->getJson('/api/v1/me')->assertUnauthorized();
    }

    public function test_logout_revoga_o_token_usado(): void
    {
        $user = User::factory()->create();
        $tokenResult = $user->createToken('teste');

        // Verifica o efeito direto no banco, não uma segunda requisição
        // autenticada: dentro de um único teste, o guard de autenticação
        // do Laravel fica em cache entre chamadas HTTP sucessivas, então
        // uma segunda chamada não refletiria a revogação de forma
        // confiável — isso é uma particularidade do ambiente de teste,
        // não do comportamento real em produção.
        $this->withHeader('Authorization', "Bearer {$tokenResult->plainTextToken}")
            ->postJson('/api/v1/logout')
            ->assertOk();

        $this->assertDatabaseMissing('personal_access_tokens', [
            'id' => $tokenResult->accessToken->id,
        ]);
    }
}
