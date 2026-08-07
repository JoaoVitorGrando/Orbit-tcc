<?php

namespace Tests\Feature;

use App\Models\Branch;
use App\Models\Organization;
use App\Models\User;
use App\Support\TenantContext;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * RNF02 — Camada 1 do isolamento multitenant (Global Scope).
 *
 * Dois dos sete cenários obrigatórios da metodologia do projeto:
 * segregação por organização e segregação por filial.
 *
 * A camada 2 (Row Level Security no PostgreSQL) é verificada
 * separadamente, e o teste de defesa em profundidade — que desativa esta
 * camada e verifica que a outra ainda barra — encerra a sprint.
 */
class TenantIsolationTest extends TestCase
{
    use RefreshDatabase;

    private Organization $padaria;

    private Organization $vetta;

    private Branch $matriz;

    private Branch $zonaSul;

    private Branch $matrizVetta;

    protected function setUp(): void
    {
        parent::setUp();

        $this->padaria = Organization::factory()->create(['name' => 'Padaria']);
        $this->vetta = Organization::factory()->create(['name' => 'Vetta']);

        $this->matriz = Branch::factory()->for($this->padaria)->create(['name' => 'Matriz']);
        $this->zonaSul = Branch::factory()->for($this->padaria)->create(['name' => 'Zona Sul']);
        $this->matrizVetta = Branch::factory()->for($this->vetta)->create(['name' => 'Matriz Vetta']);
    }

    private function contexto(User $user): void
    {
        app(TenantContext::class)->fromUser($user);
    }

    // ---------------------------------------------------------------
    // Segregação por organização
    // ---------------------------------------------------------------

    public function test_consulta_nao_retorna_registros_de_outra_organizacao(): void
    {
        $daPadaria = User::factory()->for($this->padaria)->colaborador()->create();
        $daVetta = User::factory()->for($this->vetta)->colaborador()->create();

        $this->contexto($daPadaria);

        $encontrados = User::all()->pluck('id');

        $this->assertTrue($encontrados->contains($daPadaria->id));
        $this->assertFalse(
            $encontrados->contains($daVetta->id),
            'usuário de outra organização não pode aparecer na consulta'
        );
    }

    public function test_busca_direta_por_id_de_outra_organizacao_nao_encontra(): void
    {
        $daPadaria = User::factory()->for($this->padaria)->admin()->create();
        $daVetta = User::factory()->for($this->vetta)->colaborador()->create();

        $this->contexto($daPadaria);

        // Mesmo conhecendo o identificador exato — cenário de quem tenta
        // adivinhar ou incrementar IDs em uma URL — o registro não é
        // alcançável.
        $this->assertNull(User::find($daVetta->id));
    }

    public function test_contagem_reflete_apenas_a_propria_organizacao(): void
    {
        $admin = User::factory()->for($this->padaria)->admin()->create();
        User::factory()->count(4)->for($this->padaria)->colaborador()->create();
        User::factory()->count(7)->for($this->vetta)->colaborador()->create();

        $this->contexto($admin);

        $this->assertSame(5, User::count(), 'o admin e mais 4 colaboradores da Padaria');
    }

    // ---------------------------------------------------------------
    // Segregação por filial
    // ---------------------------------------------------------------

    public function test_gestor_nao_enxerga_colaboradores_de_outra_filial(): void
    {
        $gestorMatriz = User::factory()->for($this->padaria)->for($this->matriz)->gestor()->create();

        $daMatriz = User::factory()->for($this->padaria)->for($this->matriz)->colaborador()->create();
        $daZonaSul = User::factory()->for($this->padaria)->for($this->zonaSul)->colaborador()->create();

        $this->contexto($gestorMatriz);

        $encontrados = User::all()->pluck('id');

        $this->assertTrue($encontrados->contains($daMatriz->id));
        $this->assertFalse(
            $encontrados->contains($daZonaSul->id),
            'gestor não pode enxergar colaborador de outra filial'
        );
    }

    public function test_administrador_enxerga_todas_as_filiais_da_sua_organizacao(): void
    {
        $admin = User::factory()->for($this->padaria)->admin()->create();

        $daMatriz = User::factory()->for($this->padaria)->for($this->matriz)->colaborador()->create();
        $daZonaSul = User::factory()->for($this->padaria)->for($this->zonaSul)->colaborador()->create();
        $daVetta = User::factory()->for($this->vetta)->for($this->matrizVetta)->colaborador()->create();

        $this->contexto($admin);

        $encontrados = User::all()->pluck('id');

        $this->assertTrue($encontrados->contains($daMatriz->id));
        $this->assertTrue($encontrados->contains($daZonaSul->id));

        // O escopo de filial não se aplica ao administrador, mas o de
        // organização continua valendo — sem exceção de papel.
        $this->assertFalse($encontrados->contains($daVetta->id));
    }

    public function test_remocao_explicita_do_escopo_de_filial_nao_atravessa_organizacoes(): void
    {
        $gestorMatriz = User::factory()->for($this->padaria)->for($this->matriz)->gestor()->create();

        $daZonaSul = User::factory()->for($this->padaria)->for($this->zonaSul)->colaborador()->create();
        $daVetta = User::factory()->for($this->vetta)->for($this->matrizVetta)->colaborador()->create();

        $this->contexto($gestorMatriz);

        // Cenário do RF07: busca por competência entre filiais.
        $encontrados = User::semEscopoDeFilial()->get()->pluck('id');

        $this->assertTrue(
            $encontrados->contains($daZonaSul->id),
            'a exceção deve permitir atravessar filiais'
        );
        $this->assertFalse(
            $encontrados->contains($daVetta->id),
            'a exceção jamais pode atravessar organizações'
        );
    }

    // ---------------------------------------------------------------
    // Preenchimento automático na criação
    // ---------------------------------------------------------------

    public function test_registro_criado_herda_a_organizacao_do_contexto(): void
    {
        $gestor = User::factory()->for($this->padaria)->for($this->matriz)->gestor()->create();

        $this->contexto($gestor);

        $novo = new User([
            'name' => 'Novo Colaborador',
            'email' => 'novo@demo.local',
            'password' => bcrypt('12345678'),
        ]);
        $novo->assignRole('colaborador');
        $novo->save();

        // Sem informar organização nem filial: ambas vêm do contexto.
        // Um registro criado sem tenant ficaria órfão — invisível para
        // todos ou, pior, visível para quem não deveria.
        $this->assertSame($this->padaria->id, $novo->organization_id);
        $this->assertSame($this->matriz->id, $novo->branch_id);
    }

    // ---------------------------------------------------------------
    // Comportamento sem contexto
    // ---------------------------------------------------------------

    public function test_sem_contexto_definido_o_escopo_nao_filtra(): void
    {
        User::factory()->for($this->padaria)->colaborador()->create();
        User::factory()->for($this->vetta)->colaborador()->create();

        app(TenantContext::class)->clear();

        // Contexto administrativo (seeder, comando de terminal) enxerga
        // tudo. É decisão consciente, e é exatamente a razão de existir
        // uma segunda camada de isolamento no banco, independente desta.
        $this->assertSame(2, User::count());
    }
}
