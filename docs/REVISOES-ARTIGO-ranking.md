# Revisões no artigo — decisão sobre o ranking

Decisões de 28/07/2026: ranking ordenado por **XP acumulado**, visível **apenas ao
Administrador**; módulo de recompensas/resgates **removido do escopo**.

Abaixo, os trechos exatos para substituir. Quatro pontos do artigo.

---

## 1 · Seção 2.3 — parágrafo final (o mais importante)

Este é o trecho que hoje contradiz o artefato. A correção não é apenas cosmética: ela
converte uma inconsistência em uma decisão de projeto fundamentada.

### ❌ Texto atual

> Essas ressalvas orientaram diretamente a seleção de mecânicas do Orbit RH. Adotam-se
> pontuação de experiência (XP), níveis, ofensivas de dias consecutivos e emblemas
> vinculados à conclusão de Planos de Progressão de Carreira, elementos centrados no
> desenvolvimento individual, com exposição pública restrita ao destaque mensal de maior
> participação. **Evitam-se rankings detalhados que a literatura associa a competição
> disfuncional.** A personalização das mecânicas por perfil motivacional, na linha de
> Tondello et al. (2016), é registrada como evolução futura.

### ✅ Texto proposto

> Essas ressalvas orientaram diretamente a seleção de mecânicas do Orbit RH. Adotam-se
> pontuação de experiência (XP), níveis, ofensivas de dias consecutivos e emblemas
> vinculados à conclusão de Planos de Progressão de Carreira, elementos centrados no
> desenvolvimento individual, com exposição pública restrita ao destaque mensal de maior
> participação.
>
> Cabe distinguir, nesse ponto, mecânica de gamificação e indicador gerencial. A
> advertência da literatura quanto a rankings dirige-se à comparação entre pares: os
> efeitos nulos ou negativos reportados por Hamari, Koivisto e Sarsa (2014) associam-se
> a situações em que as mecânicas são percebidas como controle ou geram competição
> indesejada, o que pressupõe a visibilidade da posição relativa pelo próprio
> colaborador. Por essa razão, o Orbit RH não expõe classificação relativa aos
> colaboradores nem aos gestores. Mantém-se, contudo, uma ordenação por participação
> acessível exclusivamente ao Administrador, cuja finalidade é analítica: identificar
> unidades ou perfis com baixa adesão às práticas de desenvolvimento, de modo a orientar
> a ação da liderança. Não constitui, assim, elemento de design de jogo percebido pelo
> usuário, e sim instrumento de acompanhamento gerencial, com acesso segregado por papel.
>
> Essa ordenação apoia-se unicamente em indicadores de participação — check-ins
> realizados, ofensivas mantidas, metas cumpridas e avanço no Plano de Progressão de
> Carreira. Em nenhuma hipótese são ordenados ou comparados os registros de humor, cuja
> proximidade com a esfera de saúde mental do titular exige o tratamento agregado
> descrito em 2.6. A personalização das mecânicas por perfil motivacional, na linha de
> Tondello et al. (2016), é registrada como evolução futura.

**Por que isso funciona na banca:** você não recuou da posição original — você a
refinou. A literatura fala de competição *entre pares*, e sem visibilidade entre pares
não há competição. Você está lendo Hamari com precisão, não contornando-o. E ao proibir
explicitamente o ranqueamento por humor, você antecipa a objeção mais grave antes que
ela seja feita.

---

## 2 · Seção 2.6 — parágrafo dos "dois regimes de dados"

Você já criou, nesta seção, a distinção que resolve o problema. Basta estendê-la.

### ❌ Trecho atual

> Distinguem-se, ainda, dois regimes de dados. Enquanto os registros de humor e os
> feedbacks são anônimos ou agregados, o portfólio de competências é dado pessoal
> identificado, com finalidade declarada e legítima (desenvolvimento e mobilidade
> interna) e acesso restrito por papel.

### ✅ Trecho proposto

> Distinguem-se, ainda, dois regimes de dados. Enquanto os registros de humor e os
> feedbacks são anônimos ou agregados, o portfólio de competências e os indicadores
> individuais de participação são dados pessoais identificados, com finalidade declarada
> e legítima (desenvolvimento, mobilidade interna e acompanhamento da adesão às práticas
> de desenvolvimento) e acesso restrito por papel. O segundo regime é deliberadamente
> mais restritivo quanto à audiência: a ordenação nominal por participação é acessível
> apenas ao Administrador, ao passo que o portfólio é consultável também pela gestão.
> A separação entre os dois regimes é estrutural, e não configurável: os registros de
> humor não são acessíveis em nível individual por nenhum papel, em nenhuma tela.

---

## 3 · Quadro 4 — requisitos funcionais

**Remover:** nada (recompensas nunca constaram do quadro — apareceram só no código).

**Acrescentar ao final:**

| Código | Descrição | Prioridade |
|---|---|---|
| RF12 | Disponibilizar ranking interno de participação, ordenado por XP acumulado, com acesso exclusivo ao Administrador | Complementar |

Classifique como **Complementar**, não Essencial. Dois motivos: mantém o requisito fora
do caminho crítico das suas 30 sprints-dia, e é coerente — o sistema cumpre sua proposta
sem ele.

---

## 4 · Seção 4.4 — parágrafo sobre funcionalidades

Acrescente uma frase ao parágrafo que descreve o XP e o destaque mensal (o que começa
com *"O XP é acumulado a partir do check-in diário de humor..."*), logo após a menção ao
destaque mensal:

> A mesma base de eventos de pontuação alimenta uma ordenação por participação disponível
> ao Administrador, protegida por verificação de papel no middleware e coberta por teste
> automatizado que assegura a negação de acesso aos demais papéis.

Isso amarra o requisito ao controle de acesso e ao teste — que é o padrão de rigor que
você adota no resto da seção.

---

## 5 · O que precisa existir no código para que o texto seja verdadeiro

O artigo passa a afirmar três coisas. Cada uma precisa de contrapartida verificável:

| Afirmação no texto | Evidência no código |
|---|---|
| "acessível exclusivamente ao Administrador" | Middleware `role:admin` na rota; teste assertando **403 para gestor e 403 para colaborador** |
| "apoia-se unicamente em indicadores de participação" | A query do ranking lê apenas `xp_events`. Não faz `join` com `mood_checkins` para obter valor de humor. |
| "em nenhuma hipótese são ordenados ou comparados os registros de humor" | Teste que garante que nenhum endpoint retorna humor por colaborador identificado |

O teste do 403 é rápido de escrever e é exatamente o tipo de coisa que a banca pede para
ver rodando:

```php
public function test_ranking_e_exclusivo_do_administrador(): void
{
    $org = Organization::factory()->create();

    $this->actingAs(User::factory()->for($org)->colaborador()->create())
         ->getJson('/api/v1/admin/ranking')->assertForbidden();

    $this->actingAs(User::factory()->for($org)->gestor()->create())
         ->getJson('/api/v1/admin/ranking')->assertForbidden();

    $this->actingAs(User::factory()->for($org)->admin()->create())
         ->getJson('/api/v1/admin/ranking')->assertOk();
}
```

---

## Resumo das decisões

| Item | Decisão |
|---|---|
| Recompensas / resgates | **Removidos.** Fora do escopo, não reimplementar. |
| Ranking | **Mantido**, como RF12 complementar. |
| Ordenado por | XP acumulado (participação). Nunca por humor. |
| Visível para | Apenas `admin`. Gestor e colaborador recebem 403. |
| Natureza | Indicador gerencial, não mecânica de gamificação. |
| Seções do artigo a alterar | 2.3, 2.6, Quadro 4, 4.4 |
