# Orbit RH — Portar o Front-end + Plano de Diagramas

Análise do protótipo em `Orbit-test/` · 28/07/2026

---

## Parte 1 — Sim, dá para sair idêntico. E é fácil.

Boa notícia: **sua identidade visual não depende do back-end.** Ela vive em três
arquivos com acoplamento zero. Você copia, cola, e está pronto.

### Os 3 arquivos que carregam 100% do visual

| Arquivo | Linhas | O que contém |
|---|---|---|
| `src/index.css` | 1.156 | Todo o design system: tokens (`@theme`), gradientes, e ~60 classes de componente (`.panel-card`, `.data-table`, `.modal-panel`, `.profile-*`, `.btn-*`) **+ toda a CSS do fundo cósmico** (estrelas, órbita, planeta `#earth`, estrelas cadentes) |
| `src/components/ui/parallax-cosmic-background.jsx` | 234 | O componente `<CosmicParallaxBg>` — gera as estrelas via `box-shadow`, os detritos em órbita e o texto animado |
| `src/theme/orbitPalette.js` | 29 | Paleta em JS para os gráficos Recharts (eixos, grid, tooltip) |

O planeta que você quer na tela de login é o `#earth` no `index.css` (linha ~700):
um `<div>` de 580px com `radial-gradient` e `box-shadow` interno. Não é imagem, não é
biblioteca 3D. É CSS puro. Portável sem risco.

### Procedimento — Dia 1 da Sprint 0 (30 minutos)

```bash
# 1. Crie o projeto novo
npm create vite@latest frontend -- --template react
cd frontend

# 2. Instale exatamente as mesmas dependências do protótipo
npm i @headlessui/react @heroicons/react @tailwindcss/postcss axios \
      date-fns lucide-react postcss react-hot-toast react-router-dom \
      recharts tailwindcss

# 3. Copie os 3 arquivos do protótipo, sem alterar nada
#    (ajuste o caminho de origem conforme onde você arquivou o legado)
cp ../orbit-rh-legacy/frontend/src/index.css                              src/
cp ../orbit-rh-legacy/frontend/postcss.config.js                          .
mkdir -p src/theme src/components/ui
cp ../orbit-rh-legacy/frontend/src/theme/orbitPalette.js                  src/theme/
cp ../orbit-rh-legacy/frontend/src/components/ui/parallax-cosmic-background.jsx  src/components/ui/

# 4. Fonte Poppins no index.html (o design system depende dela)
#    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
```

Pronto. A partir daqui, qualquer tela nova que você escrever usando as classes
`.panel-card`, `.data-table`, `.section-card`, `.btn-primary` sai **automaticamente**
no mesmo estilo. É esse o ponto de ter um design system: a consistência não depende
da sua disciplina, depende do CSS.

### Componentes de apoio — copie também (são casca, sem regra de negócio)

`components/ui/`: `button.jsx`, `card.jsx`, `badge.jsx`, `ModalBase.jsx`,
`PageContainer.jsx`, `EmptyState.jsx`, `ErrorBanner.jsx`, `LoadingScreen.jsx`,
`Skeleton.jsx` · e `components/layout/AppLayout.jsx` (sidebar + topbar).

Esses são seguros porque não têm lógica de domínio. **Mas leia cada um antes de
commitar** — vale a regra do plano: você precisa saber explicar.

### O que **não** copiar

As páginas (`pages/**`). Elas misturam layout com chamadas de API e regra de negócio,
e é exatamente onde mora o código que você não entende. Você vai reescrevê-las sprint
a sprint. Só que agora, com o design system já no lugar, cada tela nova sai pronta
visualmente — você foca na lógica, que é o que precisa dominar.

**Use as páginas antigas como referência visual**, não como fonte. Abra lado a lado,
veja como a tela era, reescreva o JSX aplicando as classes. Leva 30–40 min por tela e
você termina sabendo o que cada linha faz.

### Uma sugestão sobre o login

Os campos de login vêm pré-preenchidos com `admin@araufer.local` / `12345678`, e há um
bloco "Ver credenciais de demonstração". Isso é ótimo para o teste de usabilidade
(os 8 voluntários não vão querer digitar credenciais) — **mantenha**. Só registre no
Apêndice B que as credenciais foram fornecidas na tela, porque isso afeta a primeira
tarefa do roteiro.

---

## Parte 2 — Divergências entre o artigo e o protótipo

Aqui está o que mais importa nesta análise. Encontrei cinco pontos onde o **artefato
contradiz o que o artigo afirma**. Duas são graves.

### 🔴 D1 — O banco é SQLite, não PostgreSQL

```
backend/.env → DB_CONNECTION=sqlite
backend/database/database.sqlite
```

Seu artigo, seção 2.7, diz textualmente:

> "A persistência será realizada em PostgreSQL, **escolha determinada pelo mecanismo
> nativo de Row Level Security** descrito em 2.5."

E o RNF03 exige "defesa em profundidade: filtro automático na aplicação e Row Level
Security no banco de dados".

**SQLite não tem Row Level Security.** Não é uma questão de não ter implementado
ainda — é impossível de implementar nesse banco. Ou seja: hoje, a contribuição
técnica central do seu TCC (a defesa em profundidade, seção 2.5, RNF03) **não existe
no artefato**.

Se a banca abrir o `.env`, o trabalho perde credibilidade inteiro. Por isso a Sprint 1
do plano começa com PostgreSQL e RLS — não é preciosismo, é o que sustenta sua tese.

### 🔴 D2 — Existe uma página de Ranking

`pages/admin/AdminRankingPage.jsx` + rota `/app/admin/ranking` + item "Ranking" no menu.

Seu artigo, seção 2.3, diz:

> "Evitam-se rankings detalhados que a literatura associa a competição disfuncional."

Você construiu justamente o que se comprometeu a evitar, e a justificativa teórica
(Hamari, Koivisto e Sarsa, 2014; Tondello et al., 2016) está no texto. Essa é a
pergunta mais fácil e mais devastadora que a banca pode fazer.

**Duas saídas, escolha uma:**

- **(a) Remover o ranking** e manter apenas o destaque mensal (RF09), que é o que o
  artigo descreve como "exposição pública restrita ao destaque mensal de maior
  participação". É coerente e é o caminho que recomendo.
- **(b) Manter e reescrever a seção 2.3**, assumindo que você adotou ranking visível
  apenas ao Administrador (não aos colaboradores), o que descaracteriza a competição
  entre pares. Defensável — mas só se for verdade no código.

Não dá para deixar como está.

### 🟡 D3 — Módulo de Recompensas/Resgates que não existe no artigo

Models `Reward`, `RewardRedemption`, `Recompensa`, `Resgate` · páginas
`AdminRewardsPage.jsx`, `AdminRedemptionsPage.jsx`.

Nenhum dos requisitos RF01–RF11 menciona recompensas ou resgate de pontos. É escopo
que apareceu do nada — provavelmente porque "gamificação" foi interpretado de forma
expansiva em algum prompt.

**Decisão:** fora do escopo. Não reimplemente. Se você quiser mantê-lo como diferencial,
tem que virar RF12 no Quadro 4 e ganhar fundamentação na seção 2.3. Em 30 dias, não vale.

### 🟡 D4 — Vocabulário duplicado em português e inglês

| Conceito | Model A | Model B |
|---|---|---|
| Comunicado | `Announcement.php` | `Comunicado.php` |
| Meta | `Goal.php` | `Meta.php` |
| Recompensa | `Reward.php` | `Recompensa.php` |
| Resgate | `RewardRedemption.php` | `Resgate.php` |

Duas entidades para a mesma coisa. Isso é a assinatura clássica de código gerado em
sessões diferentes sem contexto compartilhado — e é exatamente o problema que o
`CLAUDE.md` resolve.

No projeto novo: **escolha um idioma e registre no `CLAUDE.md`.** Recomendo inglês para
código (models, tabelas, rotas) e português para a interface. É a convenção mais comum
em projetos Laravel brasileiros e evita `PpcTarefa` convivendo com `RewardRedemption`.

### 🟡 D5 — O multitenant foi parafusado depois, não projetado

Olhe a ordem das migrations:

```
2026_04_17_*  → users, announcements, check_ins, feedback, goals, rewards...
2026_05_27_100000_create_tenants_table.php          ← tenant chega 40 dias depois
2026_05_27_100200_add_tenant_id_to_business_tables.php
2026_05_27_100300_extend_ppcs_for_orbit.php
2026_05_27_100600_extend_feedbacks_for_orbit.php
```

Seu artigo (2.4) afirma que a estrutura multitenant está "contemplada no modelo de
dados e nas políticas de segurança **desde o início**". As migrations contam outra
história — o tenant foi adicionado a um schema que já existia.

No projeto novo isso se resolve sozinho: `organization_id` entra na primeira migration
de cada tabela. E aí a frase do artigo passa a ser verdadeira.

### ⚪ D6 — Evidência de processo é fina

- **6 commits** no total. O artigo (4.4) fala em "histórico de commits vinculado a
  funcionalidades".
- **4 arquivos de teste**, dois deles são os `ExampleTest` que vêm com o Laravel. O
  artigo fala em "testes automatizados com PHPUnit escritos junto a cada incremento".

Não é desonestidade — é que o processo descrito não foi o processo executado. Refazer
com commits por RF e testes por sprint torna a seção 4.4 verdadeira. É mais um motivo
para o plano de 30 dias.

---

## Parte 3 — Figma: não. E aqui está o porquê.

**Resposta curta: não use Figma. Seria retrabalho.**

Figma serve para explorar o visual *antes* de existir código. Você já passou dessa
fase — seu design system está implementado, tokenizado e funcionando. Um arquivo Figma
agora seria uma cópia de menor fidelidade do que você já tem, e ficaria desatualizado
na primeira mudança de CSS.

Duas notas de contexto:

1. Seu `index.css` já credita a origem: *"design tokens extraídos do template Figma
   'Sales Dashboard', NickelFox"*. Ou seja, o Figma já cumpriu seu papel no projeto.
   **Verifique a licença desse template** — se for de uso comercial restrito, você
   precisa mencionar a atribuição na seção de tecnologias. É rápido e evita problema.
2. Seu artigo (4.4) cita um "protótipo navegável (Apêndice E)". Para o Apêndice E,
   **capturas de tela do sistema real** valem mais que telas de Figma. Faça os prints
   depois do code freeze (21/08).

**O que você precisa não é Figma. São os artefatos de engenharia que a banca espera —
e que hoje você não tem.**

---

## Parte 4 — Diagramas: quais fazer, e quais ignorar

Regra: cada diagrama precisa **responder a uma pergunta que a banca vai fazer**. Se
não responde, é enfeite e consome tempo que você não tem.

### Faça (4 obrigatórios + 2 de alto retorno)

| # | Diagrama | Pergunta que responde | Esforço |
|---|---|---|---|
| 1 | **Entidade-Relacionamento (DER)** | "Como você isola os dados de cada organização?" | 2h |
| 2 | **Casos de uso (UML)** | "O que cada papel pode fazer?" | 1h |
| 3 | **Arquitetura em camadas** | "Como as três camadas conversam?" (você descreve em 4.4, sem figura) | 1h |
| 4 | **Sequência — check-in + XP** | "Como você garante um check-in por dia e calcula a ofensiva?" | 1h |
| 5 | **Sequência — feedback anônimo** ⭐ | "Prove que o remetente não é persistido." | 1h |
| 6 | **Fluxo de seleção do mapeamento** | "353 registros viraram 5 como?" (protocolo Kitchenham) | 1h |

O **#5 é o mais valioso do conjunto.** Ele torna visual a afirmação mais forte e mais
atacável do seu trabalho (anonimato técnico irreversível, seção 2.6). Um diagrama de
sequência que mostra o `user_id` sendo descartado antes do `INSERT`, ao lado do teste
PHPUnit que prova isso, encerra a discussão.

O **#1 é obrigatório e inegociável.** Você não defende uma arquitetura multitenant sem
mostrar o modelo de dados.

### Não faça

| Diagrama | Por quê |
|---|---|
| Diagrama de classes completo | Enorme, desatualiza em uma sprint, ninguém lê. Se quiser um, faça só do módulo de gamificação. |
| Diagrama de implantação | Só se você realmente publicar em nuvem. Se rodar local na apresentação, seria ficção. |
| Diagrama de atividades para tudo | Redundante com os de sequência. |
| Diagrama de estados | Opcional. O único que faria sentido é o do PPC (não iniciado → em andamento → concluído), e ele cabe em uma frase de texto. |
| Diagrama de rede/infra | Fora do escopo do seu trabalho. |

### Ferramenta: Mermaid, não draw.io nem Figma

Escreva os diagramas em **Mermaid**, em arquivos `.mmd` versionados dentro do
repositório, em `docs/diagramas/`.

Três razões, todas defensáveis na banca:

1. **É texto** — entra no Git, aparece no diff, evolui junto com o código. Um `.png`
   exportado do draw.io não tem histórico.
2. **Renderiza sozinho** no GitHub e no VS Code. Seu README fica com os diagramas
   embutidos.
3. **É "documentação como código"** — uma boa prática de engenharia que você pode
   citar. Diagrama que mora fora do repositório sempre desatualiza; a literatura de
   engenharia de software é unânime nisso.

Para o artigo, exporte em PNG/SVG:

```bash
npm i -g @mermaid-js/mermaid-cli
mmdc -i docs/diagramas/01-der.mmd -o docs/diagramas/png/01-der.png -b transparent -s 3
```

Já deixei os 6 diagramas escritos para você em `diagramas-orbit-rh.md` — revise cada
um contra o que você vai implementar, porque **eles são hipóteses minhas sobre o seu
modelo**, não verdade. Ajustá-los é parte de dominar o projeto.

---

## Parte 5 — Boas práticas de engenharia, na ordem que importa

Só o que rende defesa na banca e cabe em 30 dias.

### Essencial (não negocie)

1. **Commits vinculados a requisitos** — `RF01: valida unicidade do check-in diário`.
   No fim do mês você roda `git log --oneline` e tem a evidência do seu Scrum. Custo
   marginal zero, altíssimo retorno.
2. **Testes junto ao incremento**, não no fim. Os 7 cenários listados no `CLAUDE.md`.
   O teste de defesa em profundidade é a prova executável da sua tese.
3. **Migrations como fonte da verdade do schema.** Nunca altere o banco pela mão.
   `php artisan migrate:fresh --seed` tem que reconstruir tudo.
4. **Seeder determinístico** — mesmo comando, mesmos dados. Você vai rodar antes de
   cada sessão do teste de usabilidade, e todos os 8 voluntários precisam ver o mesmo
   estado. Sem isso, seu teste não é comparável.
5. **`.env.example` versionado, `.env` no `.gitignore`.** Já está certo no protótipo.

### Vale muito (baixo custo)

6. **README com passo a passo de instalação.** A banca pode pedir para rodar. Se
   você levar mais de 5 minutos, é constrangedor.
7. **Branch por sprint** (`sprint-1-seguranca`), merge na `main` ao fechar. Dá o
   histórico de incrementos que a DSR pede.
8. **`DIARIO.md`** — decisões e obstáculos por sprint. Vira a Seção 5 do artigo.
9. **Um `CHANGELOG.md` por sprint** — três linhas cada. Vira o burndown que você mostra
   no slide de metodologia.

### Ignore neste mês

CI/CD, Docker, cobertura de testes acima de 60%, testes E2E (Cypress/Playwright),
storybook, monorepo, TypeScript. Tudo isso é boa prática — e tudo isso é tempo que
você não tem. Cite como "trabalhos futuros" e siga.

---

## Resumo executivo

- **Front-end:** 3 arquivos, 30 minutos, sai idêntico. Risco zero. ✅
- **Figma:** não. Você já tem coisa melhor implementada.
- **Diagramas:** 6, em Mermaid, versionados. Prontos no arquivo anexo.
- **Duas decisões que preciso de você:** o ranking (D2) e o módulo de recompensas (D3).
- **Um bloqueador técnico:** SQLite → PostgreSQL (D1). É a Sprint 1 inteira e é o que
  sustenta a contribuição técnica do TCC.
