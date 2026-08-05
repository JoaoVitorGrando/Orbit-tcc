# Orbit RH — Auditoria Técnica do Protótipo e Plano de Diagramas

Auditoria do código legado, conduzida antes do início da reconstrução. Registra as
divergências entre a especificação do produto e a implementação anterior, e a decisão
tomada para cada uma.

---

## Parte 1 — Migração do design system

O sistema visual não depende do back-end: vive em arquivos com acoplamento zero ao
restante da aplicação, o que permitiu reaproveitá-lo sem risco.

### Arquivos que compõem o sistema visual

| Arquivo | Linhas | Conteúdo |
|---|---|---|
| `src/index.css` | 1.156 | Tokens de design (`@theme`), gradientes, ~60 classes de componente (`.panel-card`, `.data-table`, `.modal-panel`, `.profile-*`, `.btn-*`) e a CSS do fundo animado (estrelas, órbita, planeta `#earth`) |
| `src/components/ui/parallax-cosmic-background.jsx` | 234 | Componente `<CosmicParallaxBg>` — estrelas via `box-shadow`, detritos em órbita, texto animado |
| `src/theme/orbitPalette.js` | 29 | Paleta em JS para os gráficos Recharts |

O planeta da tela de login é o `#earth` em `index.css`: um elemento de 580px com
`radial-gradient` e `box-shadow` interno. CSS puro, sem imagem nem biblioteca 3D.

**Status: concluído.** Os três arquivos, mais a fonte Poppins e os componentes de apoio
sem lógica de domínio (`button.jsx`, `card.jsx`, `badge.jsx`, `ModalBase.jsx`,
`PageContainer.jsx`, `EmptyState.jsx`, `ErrorBanner.jsx`, `LoadingScreen.jsx`,
`Skeleton.jsx`, `AppLayout.jsx`), foram portados para o projeto novo e validados por
teste de fumaça.

**O que não foi portado:** as páginas (`pages/**`) do protótipo. Misturam layout com
chamadas de API e regra de negócio — são reescritas requisito a requisito, usando as
telas antigas como referência visual, não como fonte.

---

## Parte 2 — Divergências identificadas entre a especificação e o protótipo

Cinco pontos em que o código legado contradizia os requisitos declarados. Duas eram
graves o suficiente para comprometer a arquitetura central do projeto.

### D1 — Banco de dados SQLite, sem suporte a Row Level Security

```
backend/.env → DB_CONNECTION=sqlite
backend/database/database.sqlite
```

A especificação do produto determina PostgreSQL como banco de dados, escolha
justificada pelo suporte nativo a Row Level Security — mecanismo que compõe a segunda
camada da defesa em profundidade exigida pelos requisitos não funcionais de isolamento.

**SQLite não implementa Row Level Security.** Sem PostgreSQL, essa camada de isolamento
simplesmente não existe no artefato, independentemente do que a documentação declare.

**Status: resolvido.** A Sprint 0 estabeleceu PostgreSQL com RLS desde a fundação do
projeto novo.

### D2 — Página de ranking com visibilidade não especificada

`pages/admin/AdminRankingPage.jsx` + rota `/app/admin/ranking` + item de menu.

A especificação original previa evitar rankings associados a competição disfuncional
entre pares, mas o protótipo implementava um ranking sem essa restrição de visibilidade
documentada.

**Status: resolvido.** Decisão registrada em `docs/REVISOES-ARTIGO-ranking.md`: ranking
mantido como RF12, ordenado exclusivamente por participação (XP), visível apenas ao
papel Administrador. Sem visibilidade entre pares, a comparação que caracteriza
competição disfuncional não ocorre.

### D3 — Módulo de recompensas fora dos requisitos

Models `Reward`, `RewardRedemption`, `Recompensa`, `Resgate`; páginas
`AdminRewardsPage.jsx`, `AdminRedemptionsPage.jsx`.

Nenhum requisito funcional documentado menciona recompensas ou resgate de pontos.

**Status: resolvido.** Fora de escopo, não reimplementado. Registrado como decisão
explícita em `CLAUDE.md` e no quadro de sprints, para que a ausência seja reconhecida
como intencional.

### D4 — Vocabulário duplicado em português e inglês

| Conceito | Model A | Model B |
|---|---|---|
| Comunicado | `Announcement.php` | `Comunicado.php` |
| Meta | `Goal.php` | `Meta.php` |
| Recompensa | `Reward.php` | `Recompensa.php` |
| Resgate | `RewardRedemption.php` | `Resgate.php` |

Duas entidades nomeando o mesmo conceito indicam ausência de convenção de nomenclatura
compartilhada ao longo do desenvolvimento do protótipo.

**Status: resolvido.** Convenção fixada em `CLAUDE.md`: inglês para código (models,
tabelas, rotas), português para a interface.

### D5 — Estrutura multitenant adicionada após o schema inicial

Ordem das migrations do protótipo:

```
2026_04_17_*  → users, announcements, check_ins, feedback, goals, rewards...
2026_05_27_100000_create_tenants_table.php          ← tenant chega 40 dias depois
2026_05_27_100200_add_tenant_id_to_business_tables.php
2026_05_27_100300_extend_ppcs_for_orbit.php
2026_05_27_100600_extend_feedbacks_for_orbit.php
```

A especificação afirma que a estrutura multitenant está contemplada no modelo de dados
desde o início. As migrations do protótipo mostram o contrário: o identificador de
tenant foi adicionado a um schema que já existia.

**Status: resolvido.** No projeto novo, `organization_id` entra na primeira migration
de cada tabela de negócio — sem exceção.

### D6 — Evidência de processo iterativo insuficiente

O protótipo registrava 6 commits no total e 4 arquivos de teste, dois deles gerados
automaticamente pelo framework. A metodologia declarada no projeto prevê histórico de
commits vinculado a requisitos e testes automatizados escritos junto a cada incremento.

**Status: em andamento.** Endereçado pelo processo descrito em
`FLUXO-DE-TRABALHO.md` — commit por requisito, teste junto ao incremento.

---

## Parte 3 — Diagramas: escopo e ferramenta

Cada diagrama deve responder a uma pergunta específica sobre a arquitetura. Diagramas
sem essa função são descartados.

### Diagramas produzidos

| # | Diagrama | Pergunta que responde |
|---|---|---|
| 1 | Entidade-Relacionamento (DER) | Como o isolamento de dados por organização é modelado? |
| 2 | Casos de uso | O que cada papel pode fazer? |
| 3 | Arquitetura em camadas | Como as três camadas do sistema se comunicam? |
| 4 | Sequência — check-in + XP | Como a unicidade diária e o cálculo de ofensiva são garantidos? |
| 5 | Sequência — feedback anônimo | Como se prova que o remetente não é persistido? |
| 6 | Fluxo de seleção do mapeamento sistemático | Como o corpus da revisão de literatura foi filtrado? |

O diagrama 5 é o de maior valor probatório: torna visível o ponto em que o identificador
do autor é descartado antes da gravação, ao lado do teste automatizado que confirma o
comportamento.

O diagrama 1 é pré-requisito para qualquer discussão sobre a arquitetura multitenant.

### Diagramas descartados, e por quê

| Diagrama | Motivo |
|---|---|
| Classes completo | Escopo amplo, desatualiza a cada sprint, baixo valor de leitura |
| Implantação | Só se aplica com publicação em nuvem real |
| Atividades | Redundante com os diagramas de sequência |
| Estados | O único caso relevante (PPC) cabe em uma frase de texto |
| Rede/infraestrutura | Fora do escopo do projeto |

### Ferramenta: Mermaid

Diagramas em texto, versionados em `docs/diagramas/*.mmd`:

- Entram no controle de versão; o histórico de mudança é auditável.
- Renderizam nativamente em GitHub e em editores de código.
- Evoluem junto com o código, evitando desatualização.

Exportação para a documentação final:

```bash
npm i -g @mermaid-js/mermaid-cli
mmdc -i docs/diagramas/01-der.mmd -o docs/diagramas/png/01-der.png -b transparent -s 3
```

Os diagramas em `docs/diagramas/00-diagramas-comentados.md` são hipóteses de modelagem
sujeitas a revisão contra a implementação real — não uma especificação fechada.

---

## Parte 4 — Práticas de engenharia adotadas

### Essenciais

1. **Commits vinculados a requisitos** — `RF01: valida unicidade do check-in diário`.
   Evidência direta do processo iterativo, com custo marginal desprezível.
2. **Testes junto ao incremento**, não ao final. Os cenários obrigatórios listados em
   `CLAUDE.md`, com prioridade para o teste de defesa em profundidade.
3. **Migrations como fonte da verdade do schema.** `php artisan migrate:fresh --seed`
   reconstrói o estado completo do banco.
4. **Seeder determinístico** — mesmo comando, mesmos dados, requisito do protocolo de
   avaliação de usabilidade.
5. **`.env.example` versionado, `.env` no `.gitignore`.**

### Recomendadas

6. **README com passo a passo de instalação** verificável em poucos minutos.
7. **Branch por sprint**, merge na `main` ao fechar.
8. **Diário técnico** (`DIARIO.md`) — decisões e obstáculos por sprint.

### Fora do escopo atual

CI/CD, containerização, cobertura de testes acima de 60%, testes end-to-end, sistema de
design isolado (storybook), monorepo, TypeScript. Registrados como trabalho futuro.

---

## Resumo

- **Front-end:** design system migrado integralmente, sem alteração visual.
- **Diagramas:** seis, em Mermaid, versionados em `docs/diagramas/`.
- **Divergências D1 a D6:** cinco resolvidas na Sprint 0, uma (D6) endereçada pelo
  processo corrente.
- **Bloqueador técnico D1** (SQLite → PostgreSQL) era o de maior impacto: sem ele, a
  contribuição técnica central do projeto — defesa em profundidade — não existia no
  artefato.
