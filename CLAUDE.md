# Orbit RH — Diretrizes de Engenharia

## Visão geral do projeto

Plataforma web multitenant para gestão estratégica de pessoas em pequenas e médias
empresas brasileiras, desenvolvida como artefato de pesquisa aplicada sob o método
Design Science Research. Entrega prevista: **28/08/2026**.

Três frentes funcionais: escuta contínua (check-in de humor + feedback anônimo),
desenvolvimento individual (gamificação + Plano de Progressão de Carreira) e apoio à
decisão da liderança (painel de indicadores agregados).

Conformidade com **NR-1** e **LGPD** é requisito arquitetural, não funcionalidade
opcional.

---

## Processo de desenvolvimento

Este projeto segue disciplina de revisão técnica, aplicada de forma consistente a
qualquer mudança no repositório:

1. **Mudanças não triviais exigem proposta antes da implementação** — arquivos afetados,
   camada envolvida, alternativas razoáveis e trade-offs.
2. **Incrementos pequenos.** Uma mudança deve caber em uma única leitura de revisão.
3. **Comentários documentam decisão, não mecânica.** `// incrementa contador` é ruído.
   `// evento em vez de contador, para permitir auditoria do XP` é informação.
4. **Toda mudança precisa ser explicável por quem a propôs**, em poucas frases, sem
   consultar anotação.
5. **Idioma:** português do Brasil na documentação e na interface; inglês em código
   (models, tabelas, rotas).

---

## Stack

- **Back-end:** Laravel 11, PHP 8.2+, API REST
- **Auth:** Laravel Sanctum (tokens), HTTPS obrigatório
- **Banco:** PostgreSQL (suporte nativo a Row Level Security)
- **Front-end:** React + Vite + Tailwind CSS
- **Testes:** PHPUnit
- **Versionamento:** Git, commits vinculados a requisitos (`RF01: ...`)

---

## Regras invioláveis de arquitetura

Estas regras derivam de requisitos funcionais e não funcionais declarados na
especificação do produto.

### Isolamento multitenant (RNF02, RNF03)
- **Toda** tabela de dado de negócio tem `organization_id`. Quando fizer sentido,
  também `branch_id`.
- **Duas camadas independentes de isolamento**, sempre:
  1. Global Scope no Eloquent, aplicado automaticamente a toda query.
  2. Políticas de Row Level Security no PostgreSQL.
- Nenhuma camada é removida sob a justificativa de que a outra já resolve. A
  redundância é deliberada (defesa em profundidade).
- Exceção documentada: a **busca por competência (RF07)** atravessa filiais de
  propósito, para apoiar mobilidade interna. É explícita, restrita a
  Administrador/Gestor, e nunca vaza entre organizações.

### Anonimato técnico (RNF04, LGPD)
- Feedback anônimo **não persiste o remetente**. O `user_id` não é gravado —
  não é nulo-mas-preenchido, não é criptografado, não é hash. É ausente.
- Existe teste automatizado que prova isso.

### Agregação no painel (RNF04, LGPD)
- O painel da liderança exibe **apenas indicadores agregados**.
- Nunca exibe humor individual identificável.
- Não exibe indicador de grupo com menos de 5 respondentes (proteção contra
  reidentificação).

### Controle de acesso (RF11, RNF01)
- RBAC com exatamente 3 papéis: `admin`, `gestor`, `colaborador`.
- Aplicado por middleware no back-end **e** por proteção de rota no front-end.
- O front-end nunca é a única barreira.

### Arquivos
- Armazenamento privado, acesso via URLs assinadas. Nada em disco público.

---

## Requisitos funcionais

**Essenciais — escopo principal do produto:**

| Código | Descrição |
|---|---|
| RF01 | Check-in diário de humor, 3 níveis, uma vez por dia |
| RF02 | Canal de feedback bidirecional com opção de envio anônimo |
| RF04 | Acúmulo de XP: check-in, ofensivas de dias consecutivos, metas, avanço no PPC |
| RF05 | Plano de Progressão de Carreira com níveis e emblemas automáticos |
| RF06 | Portfólio de competências, habilidades e cursos no perfil |
| RF07 | Busca de colaboradores por competência, inclusive entre filiais |
| RF08 | Painel de indicadores agregados de clima e engajamento por unidade |
| RF11 | Controle de acesso por 3 papéis |

**Complementares — fora do escopo até que os essenciais estejam concluídos e
testados:**

| Código | Descrição |
|---|---|
| RF03 | Notificação por e-mail ao Administrador em feedback urgente |
| RF09 | Destaque mensal de maior participação |
| RF10 | Mural de comunicados |
| RF12 | Ranking interno de participação, restrito ao Administrador |

Solicitações de implementação de RF03, RF09, RF10 ou RF12 antes da conclusão e teste
dos itens essenciais devem ser sinalizadas antes de iniciar o trabalho.

### Fora de escopo — não implementar

- **Recompensas e resgate de pontos.** Não constam dos requisitos e não serão
  implementados. Se solicitado, o pedido deve ser questionado antes de prosseguir.

### Regras específicas do ranking (RF12)

Decisão registrada em `docs/REVISOES-ARTIGO-ranking.md`:

- Ordena **exclusivamente por XP acumulado** (check-ins, ofensivas, metas, avanço no
  PPC).
- **Nunca ordena, exibe ou deriva humor por colaborador.** Ranquear por estado
  emocional contraria o princípio de projeto "escutar continuamente sem vigiar
  individualmente".
- Visível **apenas ao papel `admin`**. Gestor e colaborador recebem 403, com teste
  automatizado provando isso.
- O ranking **não é mecânica de gamificação** — é indicador gerencial. O colaborador
  nunca vê sua posição relativa. A única exposição pública entre pares é o destaque
  mensal (RF09).

---

## Testes obrigatórios

Cenários que devem existir e passar antes de qualquer entrega:

- [ ] Unicidade do check-in diário, incluindo transição de data (23h59 → 00h01)
- [ ] Cálculo de XP e de ofensivas de dias consecutivos
- [ ] Integridade do RBAC — colaborador não acessa endpoint de gestor
- [ ] Segregação por tenant via Global Scope
- [ ] Segregação por filial
- [ ] **Defesa em profundidade:** com o Global Scope desabilitado, a RLS ainda barra
- [ ] Feedback anônimo não persiste o remetente

O teste de defesa em profundidade é a evidência central da contribuição técnica do
projeto e tem prioridade sobre os demais.

---

## Convenções

- Migrations: `snake_case`, plural (`mood_checkins`)
- Models: `PascalCase`, singular (`MoodCheckin`)
- Rotas API: `/api/v1/...`, kebab-case
- Commits: `RF01: adiciona validação de unicidade do check-in diário`
- Timezone: definido por organização. Nunca assumir o do servidor.

---

## Cronograma

| Sprint | Período | Entrega |
|---|---|---|
| 0 — Fundação | 04–05/08 | Repositório, PostgreSQL, migrations, seeders |
| 1 — Segurança | 06–10/08 | Auth, RBAC, Global Scope, RLS |
| 2 — Escuta contínua | 11–15/08 | Check-in, XP, feedback anônimo |
| 3 — Desenvolvimento individual | 16–19/08 | PPC, portfólio, busca por competência |
| 4 — Painel e congelamento | 20–22/08 | Painel, polimento, **code freeze 22/08** |
| 5 — Avaliação | 23–25/08 | Teste de usabilidade (SUS, 8 voluntários) |
| Fechamento | 26–28/08 | Relatório final, apresentação |

Qualquer item que ameace estourar a sprint deve ser sinalizado antes de prosseguir.
Cortar escopo é decisão consciente, registrada; não é acidente de cronograma.
