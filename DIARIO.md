# Diário Técnico — Orbit RH

Registro de desenvolvimento por sprint: entregas, impedimentos, decisões técnicas e
questões em aberto.

Este documento cumpre três funções:

1. **Rastreabilidade** — histórico de decisões de projeto e sua justificativa.
2. **Evidência de processo** — registro do ciclo iterativo adotado, alinhado à
   metodologia declarada no relatório do projeto.
3. **Insumo para a documentação final** — as decisões relatadas aqui alimentam
   diretamente a seção de desenvolvimento do artefato e a de resultados.

**Regra:** uma entrada por dia trabalhado, mesmo curta. O campo "Questões em aberto" é
obrigatório — deixá-lo vazio por dois dias seguidos é sinal de que uma decisão não foi
suficientemente compreendida antes de ser aplicada.

---

## Modelo de entrada

```markdown
### DD/MM — Sprint N — Dia X

**Entregas do dia:**

**Impedimentos:**

**Decisão técnica tomada (e justificativa):**

**Questões em aberto:**
```

---

## Sprint 0 — Fundação e domínio (04–05/08)

**Objetivo:** repositório, ambiente e modelo de dados estabelecidos. Nenhuma
funcionalidade de negócio.

**Definição de pronto:** modelo entidade-relacionamento revisado e compreendido pelo
autor, sem necessidade de consulta.

### 04/08 — Sprint 0 — Dia 1

**Entregas do dia:**

**Impedimentos:**

**Decisão técnica tomada (e justificativa):**

**Questões em aberto:**

---

### 05/08 — Sprint 0 — Dia 2

**Entregas do dia:**

**Impedimentos:**

**Decisão técnica tomada (e justificativa):**

**Questões em aberto:**

---

## Sprint 1 — Núcleo de segurança (06–10/08)

**Objetivo:** autenticação, RBAC, Global Scope e Row Level Security.
**Sprint crítica** — concentra a contribuição técnica central do projeto.

**Definição de pronto:** o teste de defesa em profundidade passa — com o Global Scope
desabilitado, a RLS ainda barra o acesso entre organizações.

### 06/08 — Sprint 1 — Dia 1

**Entregas do dia:**

- Autenticação por token (Laravel Sanctum): `POST /api/v1/login`,
  `POST /api/v1/logout`, `GET /api/v1/me`.
- Middleware `EnsureUserHasRole`, registrado com o alias `role`, aplicável como
  `role:admin` ou `role:gestor,admin`.
- Banco de teste dedicado (`orbit_rh_test`) e arquivo `.env.testing`.
- Factories de `Organization`, `Branch` e estados de papel em `UserFactory`.
- Removidos os dois arquivos de teste de exemplo gerados pelo framework.
- **Endurecimento de segurança**, a partir de revisão do código escrito no mesmo dia:
  campos de privilégio (`role`, `organization_id`) retirados da atribuição em massa;
  limite de tentativas no login; expiração de token definida; `APP_DEBUG` documentado
  no `.env.example`.
- 15 testes automatizados, 31 asserções, todos passando. Inclui o primeiro dos sete
  cenários obrigatórios (colaborador recebe 403 em rota restrita a gestor) e seis
  testes de endurecimento que simulam tentativas concretas de ataque.

**Impedimentos:**

1. `User::createToken()` inexistente — o model não havia recebido o trait
   `HasApiTokens` do Sanctum, que normalmente é adicionado por `install:api`.
   Resolvido acrescentando o trait.
2. Teste de logout falhando por motivo não óbvio: dentro de um mesmo método de
   teste, o guard de autenticação permanece em cache entre requisições HTTP
   sucessivas, de modo que a segunda chamada não refletia a revogação do token.
   O comportamento é particularidade do ambiente de teste, não do sistema. O teste
   foi reescrito para verificar diretamente a ausência do registro na tabela
   `personal_access_tokens`, que é o efeito que realmente importa.

**Decisão técnica tomada (e justificativa):**

- **Sanctum em modo token, não cookie de sessão.** Front-end e API operam em
  origens distintas em desenvolvimento (portas 5173 e 8000); o modo cookie exigiria
  configuração adicional de CORS e CSRF sem benefício correspondente.
- **Middleware de papel próprio, sem pacote externo.** Pacotes de RBAC como o
  Spatie Permission resolvem papéis dinâmicos e permissões granulares — problema
  que este sistema não tem, pois os três papéis são fixos por requisito (RF11). Um
  middleware de poucas linhas cobre o caso e permanece integralmente explicável.
- **Remoção das conexões MySQL, MariaDB e SQLite de `config/database.php`.** O
  projeto é PostgreSQL-only por decisão de arquitetura (a RLS não existe nos
  demais). Manter conexões alternativas configuradas sugeriria uma portabilidade
  que o sistema não possui.
- **Campos de privilégio fora do `$fillable`.** `role` e `organization_id` passaram a
  exigir atribuição explícita (`assignRole`, `assignToOrganization`). Sem isso, um
  endpoint de edição de perfil que repassasse o corpo da requisição ao model
  permitiria escalação de privilégio e migração para outra organização — esta última
  contornando o isolamento multitenant por dentro, sem depender de falha do Global
  Scope ou da RLS. A proteção reside no model, não no controller, justamente para
  não depender da disciplina de quem escreve cada endpoint.

**Revisão de segurança realizada:**

Revisão conduzida sobre o código produzido no próprio dia, motivada pela expectativa
de teste de intrusão por terceiro. Quatro correções aplicadas e seis testes escritos,
cada um reproduzindo uma tentativa concreta de ataque:

| Vetor | Situação anterior | Correção |
|---|---|---|
| Escalação de privilégio por atribuição em massa | `role` e `organization_id` em `$fillable` | Removidos; atribuição explícita e validada |
| Migração não autorizada entre organizações | idem | idem |
| Força bruta no login | Tentativas ilimitadas | `throttle:5,1` — 5 por minuto, por IP |
| Token de acesso sem prazo | `'expiration' => null` | 480 minutos (8 horas) |
| Enumeração de contas pelo login | Já correto, sem garantia | Teste comparando as respostas |
| Vazamento de informação por rastreamento de pilha | `APP_DEBUG=true` sem alerta | Documentado no `.env.example` |

O teste de escalação de privilégio exercita o pior caso deliberadamente: registra um
endpoint que repassa o corpo inteiro da requisição ao model — a forma mais descuidada
de escrever a operação — e verifica que, ainda assim, o papel não é alterado.

**Questões em aberto:**

- Executar a suíte em PHP 8.5 gera avisos de código obsoleto originados no próprio
  Laravel 11 (`PDO::MYSQL_ATTR_SSL_CA`, em
  `vendor/laravel/framework/config/database.php`). Os testes passam, mas o PHPUnit
  marca cada um com `DEPR`, o que reduz a legibilidade da saída. Tentativas de
  filtrar via `restrictDeprecations` (recurso do PHPUnit 11, indisponível na versão
  10.5 em uso) e via `error_reporting` não surtiram efeito. Registrado como
  limitação conhecida de ambiente; a solução definitiva seria executar sob PHP 8.3
  ou 8.4, versões alvo do Laravel 11.

---

### 07/08 — Sprint 1 — Dia 2

**Entregas do dia:**

- `TenantContext`: objeto singleton que guarda organização, filial e papel da
  requisição em curso.
- `ResolveTenantContext`: middleware que o preenche a partir do usuário autenticado,
  registrado com o alias `tenant`.
- `OrganizationScope` e `BranchScope`: escopos globais do Eloquent que filtram toda
  consulta automaticamente.
- Trait `BelongsToOrganization`, que aplica ambos os escopos, preenche
  `organization_id` e `branch_id` na criação de registros, e expõe
  `semEscopoDeFilial()` para a exceção do RF07.
- Trait aplicado a `User` e `Branch`.
- 8 testes novos, cobrindo dois dos sete cenários obrigatórios: segregação por
  organização e segregação por filial. Suíte total: 23 testes, 45 asserções.

**Impedimentos:**

Nenhum. A camada foi implementada e testada em um dia, contra os dois previstos
no plano.

**Decisão técnica tomada (e justificativa):**

- **Contexto de tenant em objeto próprio, não consulta direta a `auth()`.** Três
  razões: funciona fora do ciclo HTTP (comandos, filas, testes); separa persistência
  de autenticação; e, decisiva, torna as duas camadas de isolamento independentes
  entre si. Global Scope e RLS lerão a mesma fonte, de modo que desativar um não
  afeta o outro — condição para que o teste de defesa em profundidade seja
  conclusivo.
- **Escopo de filial automático, e não filtro manual por endpoint.** O argumento que
  justifica filtrar organização automaticamente ("não é seguro depender de alguém
  lembrar") vale igualmente para filial. Com o filtro automático, o cenário
  obrigatório "segregação por filial" comprova uma propriedade estrutural do
  sistema; com filtro manual, comprovaria apenas que um endpoint específico foi
  escrito com cuidado.
- **Custo assumido:** o escopo de filial depende do papel de quem consulta (não se
  aplica ao administrador), o que o torna mais difícil de raciocinar. Mitigado
  isolando a condição em `TenantContext::isRestrictedToBranch()` e cobrindo os três
  papéis por teste.
- **Ausência de contexto não filtra.** Em seeder ou comando de terminal, o escopo
  não se aplica. É decisão consciente: nesses contextos o acesso amplo é legítimo.
  Também é exatamente a razão de a segunda camada existir e ser independente desta.
- **Preenchimento automático na criação.** O trait define `organization_id` e
  `branch_id` ao criar um registro. Sem isso, uma linha poderia nascer órfã de
  tenant — invisível para todos ou, pior, visível para quem não deveria.

**Questões em aberto:**

- A remoção explícita do escopo de filial (`semEscopoDeFilial()`) já está testada
  quanto a não atravessar organizações, mas ainda não há restrição de papel sobre
  quem pode invocá-la. O RF07 determina acesso restrito a Administrador e Gestor;
  essa verificação será aplicada quando o requisito for implementado, na Sprint 3.

---

### Retrospectiva da sprint

**O que funcionou:**

**O que seria feito diferente:**

**Registro para a documentação final:**

---

## Sprint 2 — Escuta contínua (11–15/08)

**Objetivo:** check-in diário (RF01), motor de XP (RF04), feedback anônimo (RF02).

**Definição de pronto:** o teste da virada de meia-noite passa e o teste de não
persistência do remetente passa.

### Retrospectiva da sprint

**O que funcionou:**

**O que seria feito diferente:**

**Registro para a documentação final:**

---

## Sprint 3 — Desenvolvimento individual (16–19/08)

**Objetivo:** PPC (RF05), portfólio de competências (RF06), busca por competência
(RF07).

**Definição de pronto:** a busca localiza colaborador de outra filial, e a exceção ao
isolamento está documentada e testada.

### Retrospectiva da sprint

**O que funcionou:**

**O que seria feito diferente:**

**Registro para a documentação final:**

---

## Sprint 4 — Painel e congelamento (20–22/08)

**Objetivo:** painel agregado (RF08), polimento, seed de demonstração, documentação de
apoio à avaliação pronta.

**Definição de pronto:** nenhum indicador expõe humor individual; grupos com menos de 5
respondentes não são exibidos.

> **Code freeze — 22/08 às 23h.** A partir daqui, somente correção de bug crítico.
> Alterar o sistema durante a coleta invalida a comparabilidade do teste de usabilidade.

### Retrospectiva da sprint

**O que funcionou:**

**O que seria feito diferente:**

**Registro para a documentação final:**

---

## Sprint 5 — Avaliação (23–25/08)

**Objetivo:** teste de usabilidade com 8 voluntários, tabulação e análise.

### Registro das sessões

| Participante | Data | Papel simulado | Tarefas concluídas | Escore SUS | Observação principal |
|---|---|---|---|---|---|
| 1 | | | | | |
| 2 | | | | | |
| 3 | | | | | |
| 4 | | | | | |
| 5 | | | | | |
| 6 | | | | | |
| 7 | | | | | |
| 8 | | | | | |

**Média SUS:** ______  **Desvio-padrão:** ______  **Mínimo:** ______  **Máximo:** ______

### Confronto com os critérios de aceitação

| Critério | Meta | Resultado | Atendido? |
|---|---|---|---|
| (a) Escore SUS médio | ≥ 68 | | |
| (b) Tarefas essenciais concluídas | 100% por ≥ 70% dos participantes | | |
| (c) Utilidade percebida | ≥ 70% dos participantes | | |

> Resultado abaixo do critério não invalida a avaliação. É tratado como evidência de
> revisão de requisitos ou de design, coerente com o caráter iterativo do método adotado.
> Um resultado de 61 analisado com honestidade sobre as causas é mais forte,
> metodologicamente, do que um resultado de 84 sem discussão.

### Padrões observados nas sessões

**Onde mais participantes travaram:**

**Comentários recorrentes:**

**Bugs encontrados (e não corrigidos, por estar em code freeze):**

---

## Fechamento (26–28/08)

- [ ] Seção de desenvolvimento do artefato
- [ ] Seção de resultados e discussão
- [ ] Considerações finais reescritas
- [ ] Texto convertido para o pretérito
- [ ] Bloqueadores de redação corrigidos (ver `docs/PLANO-MESTRE-30-DIAS.md`)
- [ ] Revisões do ranking aplicadas (ver `docs/REVISOES-ARTIGO-ranking.md`)
- [ ] Apêndices consolidados
- [ ] Diagramas exportados em PNG/SVG
- [ ] Material de apresentação
- [ ] Vídeo da demonstração gravado (plano de contingência)
- [ ] Ensaios cronometrados realizados, com perguntas de sabatina técnica
