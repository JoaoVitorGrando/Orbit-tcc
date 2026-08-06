<?php

namespace Tests\Feature;

use App\Models\Organization;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

/**
 * Testes de endurecimento de segurança.
 *
 * Cada teste aqui corresponde a uma tentativa concreta de ataque. Não
 * basta o código estar correto: a proteção precisa estar demonstrada,
 * porque proteção sem teste é afirmação, não evidência.
 */
class SecurityHardeningTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Endpoint deliberadamente ingênuo, que preenche o model com tudo
        // que veio na requisição. Existe só neste teste, para provar que a
        // proteção do model resiste mesmo quando o controller é descuidado.
        Route::middleware('auth:sanctum')->put('/_teste/perfil', function () {
            $user = request()->user();
            $user->update(request()->all());

            return response()->json($user->only(['name', 'role', 'organization_id']));
        });
    }

    public function test_colaborador_nao_consegue_se_promover_a_admin(): void
    {
        $user = User::factory()->colaborador()->create();

        $this->actingAs($user, 'sanctum')
            ->putJson('/_teste/perfil', [
                'name' => 'Nome Novo',
                'role' => 'admin',
            ])
            ->assertOk();

        $user->refresh();

        $this->assertSame('Nome Novo', $user->name, 'campo comum deve ser gravado');
        $this->assertSame('colaborador', $user->role, 'papel não pode ser alterado pelo payload');
    }

    public function test_usuario_nao_consegue_migrar_para_outra_organizacao(): void
    {
        $organizacaoOriginal = Organization::factory()->create();
        $organizacaoAlvo = Organization::factory()->create();

        $user = User::factory()->for($organizacaoOriginal)->colaborador()->create();

        $this->actingAs($user, 'sanctum')
            ->putJson('/_teste/perfil', [
                'name' => 'Nome Novo',
                'organization_id' => $organizacaoAlvo->id,
            ])
            ->assertOk();

        $user->refresh();

        // Se isto falhar, o isolamento multitenant é contornável por
        // dentro: bastaria trocar a própria organização para enxergar
        // os dados de outra empresa.
        $this->assertSame(
            $organizacaoOriginal->id,
            $user->organization_id,
            'organização não pode ser alterada pelo payload'
        );
    }

    public function test_papel_invalido_e_recusado_na_atribuicao_explicita(): void
    {
        $user = User::factory()->create();

        $this->expectException(\InvalidArgumentException::class);

        $user->assignRole('superadmin');
    }

    public function test_login_e_limitado_apos_tentativas_sucessivas(): void
    {
        $user = User::factory()->create(['password' => bcrypt('12345678')]);

        // 5 tentativas são permitidas por minuto; a 6ª deve ser barrada.
        for ($i = 0; $i < 5; $i++) {
            $this->postJson('/api/v1/login', [
                'email' => $user->email,
                'password' => 'senha-errada',
            ])->assertStatus(422);
        }

        $this->postJson('/api/v1/login', [
            'email' => $user->email,
            'password' => 'senha-errada',
        ])->assertStatus(429); // Too Many Requests
    }

    public function test_token_e_emitido_com_prazo_de_validade(): void
    {
        $this->assertNotNull(
            config('sanctum.expiration'),
            'tokens sem expiração permanecem válidos indefinidamente se vazarem'
        );
    }

    public function test_login_nao_revela_se_o_email_existe(): void
    {
        $existente = User::factory()->create(['password' => bcrypt('12345678')]);

        $respostaEmailReal = $this->postJson('/api/v1/login', [
            'email' => $existente->email,
            'password' => 'senha-errada',
        ]);

        $respostaEmailInexistente = $this->postJson('/api/v1/login', [
            'email' => 'ninguem@exemplo.local',
            'password' => 'senha-errada',
        ]);

        // Respostas idênticas: não dá para descobrir quais e-mails estão
        // cadastrados testando o formulário de login (enumeração de contas).
        $this->assertSame(
            $respostaEmailReal->json('errors.email'),
            $respostaEmailInexistente->json('errors.email')
        );
    }
}
