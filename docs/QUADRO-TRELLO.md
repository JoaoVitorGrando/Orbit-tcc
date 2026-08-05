# Quadro Trello — Orbit RH

Especificação do quadro de sprints. Revise antes que eu crie os cartões.

**Quadro:** `Orbit RH — TCC 2026`

---

## 1. Listas

| Ordem | Lista | Função |
|---|---|---|
| 1 | 📌 **Contexto fixo** | Cartões de referência que **nunca se movem**. Regras, testes obrigatórios, marcos. |
| 2 | 📋 **Backlog** | Tudo que ainda não entrou em sprint. Complementares vivem aqui. |
| 3 | 🎯 **Sprint atual** | Só o que foi comprometido para a sprint em curso. |
| 4 | 🔨 **Fazendo** | Um cartão por vez. Se há dois aqui, você está dispersando. |
| 5 | 🧪 **Em teste** | Código escrito, teste ainda não passa ou não existe. |
| 6 | ✅ **Pronto** | Testado e commitado. Só entra com commit associado. |

> **Regra de entrada em "Pronto": existe commit e o teste passa.** Sem isso, o quadro
> mente — e um quadro que mente é pior que nenhum.

---

## 2. Etiquetas

Duas famílias: **prioridade** (uma por cartão, obrigatória) e **natureza** (opcional,
pode acumular).

### Prioridade

| Cor | Etiqueta | Significado |
|---|---|---|
| 🔴 Vermelho | `P0 · Crítico` | Bloqueia o TCC. Se não sair, o trabalho não se sustenta. |
| 🟠 Laranja | `P1 · Essencial` | Escopo declarado do TCC (Quadro 4 do artigo). |
| 🟡 Amarelo | `P2 · Complementar` | Só depois dos essenciais concluídos e testados. |
| ⚫ Preto | `Fora de escopo` | Não implementar. Existe para lembrar da decisão. |

### Natureza

| Cor | Etiqueta | Aplica-se a |
|---|---|---|
| 🔵 Azul | `Segurança e isolamento` | Multitenant, RBAC, RLS, LGPD |
| 🟢 Verde | `Teste obrigatório` | Os 7 cenários citados na metodologia |
| 🟣 Roxo | `Artigo e banca` | Escrita, correções, apêndices, slides |
| 🩵 Ciano | `Front-end` | Telas, design system, responsividade |
| 🩷 Rosa | `Prazo fixo` | Datas que não se movem (code freeze, sessões, defesa) |

---

## 3. Cartões

Todos os cartões de sprint recebem **data de entrega igual ao fim da sprint**, para o
calendário do Trello mostrar o burndown.

### 📌 Contexto fixo — 5 cartões

---

**📌 As 8 regras invioláveis**
`P0 · Crítico`

> Regras que governam o mês inteiro. Todas estão no `CLAUDE.md`, e valem para todo o projeto, independentemente de quem
> escreve o código.
>
> 1. Nenhuma linha entra no repositório sem que você consiga explicá-la em voz alta.
> 2. Toda tabela de negócio nasce com `organization_id`, na primeira migration.
> 3. Feedback anônimo não grava o remetente. Nunca.
> 4. Ranking ordena por XP, jamais por humor. Visível só ao Administrador.
> 5. Nada de recompensas ou resgates.
> 6. RF03, RF09, RF10 e RF12 só depois dos essenciais.
> 7. Code freeze em 22/08 às 23h.
> 8. Item que não coube na sprint vira "trabalho futuro", não dívida.

---

**📌 Os 7 cenários de teste obrigatórios**
`P0 · Crítico` · `Teste obrigatório`

> Citados na metodologia do artigo (seção 4.4). Precisam existir e passar.
>
> - [ ] Unicidade do check-in diário, incluindo a virada 23h59 → 00h01
> - [ ] Cálculo de XP e de ofensivas de dias consecutivos
> - [ ] RBAC — colaborador não acessa endpoint de gestor
> - [ ] Segregação por organização via Global Scope
> - [ ] Segregação por filial
> - [ ] **Defesa em profundidade — Global Scope desabilitado, RLS ainda barra**
> - [ ] Feedback anônimo não persiste o remetente
>
> O penúltimo é a evidência central da contribuição técnica. É o que você demonstra ao
> vivo na banca.

---

**📌 Bloqueadores do artigo (B1–B5)**
`P0 · Crítico` · `Artigo e banca`

> Correções que a banca circula em vermelho se passarem. Detalhe em
> `docs/PLANO-MESTRE-30-DIAS.md`, seção 2.1.
>
> - [ ] **B1** — Referência `SOUZA, [autor]` com placeholder não preenchido. Localizar ou remover do corpus.
> - [ ] **B2** — Abdulgalimov aparece como 2024 no texto e 2020 no quadro. É 2020, e está fora do recorte 2024–2026: padronizar e justificar como inclusão por snowballing.
> - [ ] **B3** — Seção 2.4 fala em validação numa empresa parceira; 4.5 e 4.6 dizem o contrário. Reescrever 2.4 como piloto futuro.
> - [ ] **B4** — Palavras-chave em português ≠ keywords em inglês.
> - [ ] **B5** — Artigo inteiro em tempo futuro. Converter para pretérito no dia 26.

---

**📌 ⚠️ Pendência CEP — resolver esta semana**
`P0 · Crítico` · `Artigo e banca` · `Prazo fixo`

> **A única pendência do projeto sem solução de última hora.**
>
> A seção 4.6 argumenta que o teste dispensa submissão ao Comitê de Ética, por ser
> pesquisa de baixo risco sem dados sensíveis. O argumento é razoável, mas a Resolução
> CNS 510/2016 tem hipóteses de dispensa específicas, e muitas instituições exigem
> parecer para qualquer pesquisa com participantes humanos.
>
> **Parecer de CEP leva semanas.** Se o Campo Real exigir, o cronograma inteiro muda.
>
> **Ação:** perguntar ao Prof. Moacir. Hoje.

---

**📌 Fora de escopo — recompensas e resgates**
`Fora de escopo`

> O protótipo antigo tinha `Reward`, `Recompensa`, `RewardRedemption`, `Resgate` e as
> telas correspondentes. **Nada disso existe nos requisitos** (Quadro 4) e não será
> reimplementado.
>
> Este cartão existe para lembrar que a ausência é decisão, não esquecimento. Se em
> algum momento surgir a tentação, releia aqui.

---

### 🎯 Sprint 0 — Fundação e domínio · 04 a 05/08 — 5 cartões

> **Objetivo:** recuperar o controle do projeto. Nenhuma funcionalidade de negócio.
> **Pronto quando:** você desenha o diagrama ER de memória, no papel.

---

**S0.1 — Inicializar repositório e arquivar o legado**
`P0 · Crítico` · Entrega 04/08

> Repositório novo, separado do protótipo. O legado permanece intacto como referência
> visual e fonte do design system — não se apaga.
>
> **Passos:**
> - `git init` na pasta `orbit-rh` (Desktop)
> - Primeiro commit com a estrutura e a documentação
> - Conferir que o `.gitignore` cobre `.env`, `vendor/`, `node_modules/` e `docs/coleta/`
>
> **Por que separar:** a `Orbit-test` tem `.git` próprio com 6 commits do protótipo. O
> `git log` que você vai apresentar como evidência de Scrum precisa conter só o que você
> construiu e entende.
>
> **Pronto quando:** `git log --oneline` mostra 1 commit seu.

---

**S0.2 — Subir Laravel 11 + Sanctum com PostgreSQL**
`P0 · Crítico` · `Segurança e isolamento` · Entrega 04/08

> ```bash
> composer create-project laravel/laravel backend
> cd backend && composer require laravel/sanctum && php artisan install:api
> ```
>
> No `.env`: `DB_CONNECTION=pgsql`, banco `orbit_rh`.
>
> **⚠️ Não deixe SQLite.** O protótipo usava SQLite, e é por isso que a defesa em
> profundidade (RNF03) nunca saiu do papel — SQLite não tem Row Level Security. A seção
> 2.7 do artigo afirma que o PostgreSQL foi escolhido justamente por isso. Sem ele, a
> contribuição técnica central do TCC não existe.
>
> **Pronto quando:** `php artisan migrate` roda contra o PostgreSQL sem erro.

---

**S0.3 — Subir React + Vite + Tailwind e instalar o design system**
`P1 · Essencial` · `Front-end` · Entrega 04/08

> Criar o projeto Vite, instalar as dependências do protótipo, copiar os 4 arquivos de
> `design-system/` e adicionar a fonte Poppins no `index.html`.
>
> Passo a passo completo em `COMECE-AQUI.md`, passos 3 e 4.
>
> **Teste de fumaça:** renderizar `<CosmicParallaxBg />` e ver o planeta e as estrelas.
>
> **Por que primeiro:** com o design system no lugar, toda tela futura já nasce no estilo
> certo. Você passa a gastar tempo com lógica, não com CSS.
>
> **Pronto quando:** o planeta aparece em `npm run dev` e está commitado.

---

**S0.4 — Modelar os dados no papel e escrever as migrations**
`P0 · Crítico` · `Segurança e isolamento` · Entrega 05/08

> **Desenhe à mão antes de rodar `make:migration`.** Use `docs/diagramas/01-der.mmd` como
> ponto de partida — mas ele é uma hipótese, não verdade. Discordar dele faz parte de
> dominar o projeto.
>
> Entidades mínimas: `organizations`, `branches`, `users`, `mood_checkins`, `feedbacks`,
> `career_plans`, `career_plan_steps`, `user_competencies`, `xp_events`, `badges`,
> `user_badges`.
>
> **Duas decisões que não se adiam:**
> - `organization_id` em toda tabela de negócio, **na primeira migration** — não em um
>   `add_tenant_id_to_...` quarenta dias depois, como aconteceu no protótipo.
> - `UNIQUE(user_id, checkin_date)` em `mood_checkins` — a unicidade do RF01 é garantida
>   pelo banco, não só pela aplicação.
>
> **Pronto quando:** `php artisan migrate:fresh` roda limpo e você explica cada tabela.

---

**S0.5 — Seeders determinísticos**
`P1 · Essencial` · Entrega 05/08

> 2 organizações, 3 filiais, ~20 usuários distribuídos entre os 3 papéis.
>
> **Determinístico significa:** `migrate:fresh --seed` sempre produz o mesmo estado. Isso
> é requisito do protocolo de avaliação — os 8 participantes do teste precisam encontrar
> o sistema idêntico, senão os resultados não são comparáveis.
>
> Você vai usar esses dados o mês inteiro e na demonstração da banca.
>
> **Pronto quando:** rodar duas vezes produz o mesmo banco.

---

### 🎯 Sprint 1 — Núcleo de segurança · 06 a 10/08 — 5 cartões

> **A sprint mais importante do TCC.** Aqui mora a contribuição técnica declarada.
> **Pronto quando:** o teste de defesa em profundidade passa.

---

**RF11 — Controle de acesso por 3 papéis (RBAC)**
`P1 · Essencial` · `Segurança e isolamento` · Entrega 07/08

> Exatamente 3 papéis: `admin`, `gestor`, `colaborador`.
>
> **Atenção:** o protótipo tinha **quatro** valores (`admin`, `admin_master`, `gestor`,
> `colaborador`), com `isAdmin()` e `isAdminMaster()` verificando exatamente a mesma
> lista. O artigo declara três. Corrija na origem.
>
> Aplicado por middleware no back-end **e** por proteção de rota no front-end. O
> front-end nunca é a única barreira.
>
> **Teste:** colaborador recebe 403 em endpoint de gestor.

---

**S1.2 — Autenticação Sanctum e proteção de rotas**
`P1 · Essencial` · `Segurança e isolamento` · Entrega 07/08

> Login, logout, emissão e validação de token. `ProtectedRoute` no React redirecionando
> por papel.
>
> Fundamentação no artigo: Al-Atraqchi (2022) — tokens emitidos pelo servidor e validados
> por middleware, sobre HTTPS, com armazenamento protegido no cliente.
>
> **Pronto quando:** os 3 papéis logam e caem na home correta.

---

**RNF02 — Global Scope de isolamento (Camada 1)**
`P0 · Crítico` · `Segurança e isolamento` · Entrega 08/08

> Global Scope no Eloquent injetando `organization_id` — e `branch_id` quando aplicável —
> automaticamente em toda consulta.
>
> **A ideia:** se um desenvolvedor esquecer o filtro, o framework não esquece.
>
> **Exceção documentada:** a busca por competência (RF07) atravessa filiais de propósito.
> Ela precisa ser explícita e justificada, nunca acidental.
>
> **Testes:** usuário da org A não lê org B; gestor da filial 1 não lê filial 2.

---

**RNF03 — Row Level Security no PostgreSQL (Camada 2)**
`P0 · Crítico` · `Segurança e isolamento` · Entrega 10/08

> ⭐ **O cartão mais importante do quadro.** É a contribuição técnica central do trabalho.
>
> Políticas de RLS restringindo por linha, independentes da aplicação. Depende de a
> conexão setar uma variável de sessão (`SET app.current_org`) a cada request — **é aqui
> que a maioria trava. Reserve tempo.**
>
> **O argumento (seção 2.5):** a redundância é deliberada. Em dados de percepção de
> colaboradores, um vazamento entre organizações não é prejuízo comercial — é violação de
> privacidade de quem confiou no anonimato.
>
> **Se passar de 1 dia e meio:** entregue com Global Scope + testes provando isolamento e
> reposicione a RLS como camada parcial no artigo. Mas tente de verdade — é seu
> diferencial.

---

**S1.5 — Teste de defesa em profundidade**
`P0 · Crítico` · `Teste obrigatório` · Entrega 10/08

> ⭐ **A evidência executável da tese do trabalho.**
>
> Desabilite o Global Scope, execute a consulta e verifique que a RLS ainda barra o
> acesso entre organizações.
>
> **Este é o momento que você demonstra ao vivo na banca.** Vale mais que dez slides.
> Ensaie a demonstração até ficar fluida.
>
> **Pronto quando:** o teste passa e você consegue executá-lo sem consultar anotação.

---

### 🎯 Sprint 2 — Escuta contínua · 11 a 15/08 — 3 cartões

---

**RF01 — Check-in diário de humor**
`P1 · Essencial` · Entrega 13/08

> Três níveis (feliz, neutro, triste), um registro por usuário por dia.
>
> **A parte difícil é a data.** "Um por dia" é ambíguo quando existe fuso horário. A data
> se resolve no **timezone da organização**, nunca no do servidor.
>
> **Fundamentação (2.2):** pulse surveys captam variação quase em tempo real, mas
> frequência excessiva gera fadiga de resposta. Daí o instrumento minimalista de 3 opções
> em vez de questionário diário.
>
> **Cuidado na defesa:** humor em 3 níveis não é medida psicométrica. É indicador de
> tendência, não diagnóstico. Diga antes que perguntem.
>
> **Testes:** segundo check-in no mesmo dia retorna 409; check-in às 23h59 e às 00h01 são
> dois registros válidos.

---

**RF04 — Motor de XP, níveis e ofensivas**
`P1 · Essencial` · Entrega 14/08

> 10 XP por check-in, 15 com ofensiva ativa (2+ dias consecutivos). Também pontua metas e
> avanço no PPC.
>
> **Curva de níveis** (do protótipo, validada): cada nível exige 50 + 20×(N−1) XP no
> segmento. Nível 2 em ~3 dias, nível 3 em 8, nível 6 em 30. Recompensa rápida quando o
> hábito é frágil, desaceleração quando já se sustenta.
>
> **Decisão de arquitetura — mude o que o protótipo fazia:** modele como **tabela de
> eventos** (`xp_events`), não como contador em `users.xp`. Contador você não audita nem
> explica; log de eventos você reconstrói e prova de onde veio cada ponto.
>
> **Teste:** ofensiva quebra quando há um dia sem check-in; XP dobra só com ofensiva ativa.

---

**RF02 — Canal de feedback com anonimato técnico**
`P0 · Crítico` · `Segurança e isolamento` · Entrega 15/08

> ⭐ Bidirecional, com opção de envio anônimo e marcação de urgente.
>
> **A regra que não se negocia:** quando anônimo, o `user_id` **não é gravado**. Não é
> nulo-mas-preenchido, não é criptografado, não é hash. É ausente.
>
> ```php
> 'user_id' => $anonimo ? null : $autor->id,
> ```
>
> **Boa notícia:** o protótipo já fazia isso corretamente. Preserve o comportamento.
>
> Permanecem apenas `organization_id` e `branch_id`, necessários para rotear a mensagem à
> liderança certa.
>
> **Teste obrigatório:** nenhum feedback anônimo, em nenhuma hipótese, carrega remetente.
> É a prova da afirmação mais forte do seu artigo.

---

### 🎯 Sprint 3 — Desenvolvimento individual · 16 a 19/08 — 3 cartões

---

**RF05 — Plano de Progressão de Carreira**
`P1 · Essencial` · Entrega 17/08

> Plano com etapas, níveis e emblemas concedidos automaticamente ao concluir.
>
> **Fundamentação:** Scholz (2025), modelo *Talent Tree*, propõe vincular mecânicas de
> jogo à progressão de carreira — mas é modelo conceitual, sem artefato construído. **O
> Orbit RH implementa o que Scholz propõe.** Esse é um argumento forte de originalidade.
>
> O protótipo tinha fluxo rico (solicitar → aprovar → tarefas → reflexão → concluir).
> Avalie se cabe no prazo; um fluxo mais simples é aceitável e defensável.

---

**RF06 — Portfólio de competências**
`P1 · Essencial` · Entrega 18/08

> Habilidades, formações e cursos autodeclarados no perfil. Emblemas do PPC entram
> automaticamente, preservando o histórico.
>
> **Nota de LGPD:** este é o **único dado pessoal identificado** exposto à liderança. O
> artigo (2.6) justifica com finalidade declarada e legítima — desenvolvimento e
> mobilidade interna. Saiba citar isso.

---

**RF07 — Busca de colaboradores por competência**
`P1 · Essencial` · `Segurança e isolamento` · Entrega 19/08

> ⚠️ **O detalhe sofisticado do sistema — e o mais delicado.**
>
> A busca **atravessa a barreira de filial de propósito**, para apoiar mobilidade interna.
> É uma exceção deliberada ao isolamento que o resto do sistema impõe.
>
> **Implemente explicitamente e justifique no artigo.** Se a banca descobrir sozinha,
> parece furo de segurança. Se você levantar antes, parece sofisticação.
>
> Restrita a Administrador e Gestor. **Nunca atravessa organizações.**
>
> **Caso de uso:** abre uma vaga que exige uma ferramenta; a busca encontra alguém de
> outra filial que já declarou a competência; promove-se internamente em vez de recorrer
> ao mercado.

---

### 🎯 Sprint 4 — Painel e congelamento · 20 a 22/08 — 6 cartões

---

**RF08 — Painel de indicadores agregados**
`P1 · Essencial` · `Segurança e isolamento` · Entrega 21/08

> Clima e engajamento por unidade. Gestor vê a própria filial; Administrador vê todas.
>
> **Duas regras de privacidade por design:**
> - **Nunca exibir humor individual identificável.** Nenhum papel, nenhuma tela.
> - **Não exibir indicador de grupo com menos de 5 respondentes** — em um grupo de dois, a
>   média revela o indivíduo. Isso se chama proteção contra reidentificação, e é o tipo de
>   detalhe que impressiona quem entende de privacidade.

---

**S4.2 — Polimento, responsividade e estados vazios**
`P1 · Essencial` · `Front-end` · Entrega 22/08

> RNF06: interface acessível de qualquer dispositivo. Mensagens de erro claras, estados
> vazios, telas de carregamento.
>
> **Por que importa agora:** os 8 participantes vão encontrar erros de interface e isso
> contamina o escore SUS. Cada tela sem estado vazio é um ponto perdido.

---

**S4.3 — Seed de demonstração com 30 dias de histórico**
`P1 · Essencial` · Entrega 22/08

> Histórico fictício para o painel não aparecer vazio durante o teste.
>
> Um painel sem dados não é avaliável — o participante não consegue julgar algo que não
> vê. E na banca, gráfico vazio parece funcionalidade incompleta.

---

**S4.4 — Finalizar Apêndices B, C e D**
`P1 · Essencial` · `Artigo e banca` · Entrega 21/08

> Já escritos em `docs/apendices/`. Falta:
> - [ ] Preencher os campos entre colchetes do TCLE (telefone, curso, e-mail do orientador)
> - [ ] Imprimir **16 vias** do TCLE (2 por participante)
> - [ ] Imprimir 8 cópias do roteiro e do questionário SUS
>
> Ficar sem via impressa no dia inviabiliza a coleta.

---

**S4.5 — Confirmar os 8 voluntários**
`P0 · Crítico` · `Prazo fixo` · Entrega 20/08

> **Convide 12 para garantir 8.** Sempre falta gente.
>
> Distribuição sugerida: 3 colaboradores, 3 gestores, 2 administradores. O fluxo do
> colaborador é o mais usado e o que mais precisa de evidência.
>
> Não precisam entender de RH — simulam o papel dentro do sistema. Pode ser por chamada de
> vídeo com compartilhamento de tela.
>
> Convites devem começar no início do projeto, não no final da Sprint 4.

---

**🔒 CODE FREEZE — 22/08 às 23h**
`P0 · Crítico` · `Prazo fixo` · Entrega 22/08

> A partir daqui, **só correção de bug crítico**.
>
> **Por quê:** testar um alvo móvel invalida o teste. Se o participante 3 usa uma versão
> diferente do participante 7, os escores não são comparáveis e o SUS perde sentido.
>
> **Antes de congelar:**
> - [ ] Rodar toda a suíte de testes
> - [ ] Gravar vídeo de 4 min do fluxo completo (plano B da demo)
> - [ ] Rodar `/security-review`
> - [ ] Tirar as capturas de tela do Apêndice E

---

### 🎯 Sprint 5 — Avaliação · 23 a 25/08 — 2 cartões

---

**S5.1 — Sessões com os 8 voluntários**
`P0 · Crítico` · `Prazo fixo` · Entrega 24/08

> 2 a 3 por dia, ~40 min cada. Roteiro completo no Apêndice B.
>
> **Três regras que valem mais que o resto:**
> - **Não ajude.** Se travar, anote e espere. Dizer "clica ali" mata aquele dado.
> - **Mesmo estado inicial** — rode `migrate:fresh --seed` antes de cada sessão.
> - **Mesmo roteiro, mesma ordem**, para todos.
>
> Colher assinatura do TCLE **antes** de abrir o sistema.
>
> Duas tarefas merecem atenção: **C7** (feedback anônimo — se o participante não marcar a
> opção, você descobriu que ela não está evidente) e **G5** (tentar ver humor individual —
> sucesso é ele concluir que não dá).

---

**S5.2 — Tabulação e análise**
`P1 · Essencial` · `Artigo e banca` · Entrega 25/08

> - Escore SUS por participante, média, desvio-padrão, mínimo e máximo (fórmula no
>   Apêndice C)
> - Taxa de conclusão por tarefa
> - Análise temática das entrevistas
> - Confronto com os 3 critérios de aceitação da seção 4.5
>
> **Se o SUS vier abaixo de 68, não é fracasso.** O artigo já blindou isso: resultados
> abaixo do critério viram evidência de revisão, coerente com o caráter iterativo da DSR.
> Um trabalho que reporta 61 e explica honestamente é mais forte que um que reporta 84 sem
> discussão.
>
> **Nunca escreva "SUS de 78%".** O escore não é percentual.

---

### 🎯 Fechamento · 26 a 28/08 — 5 cartões

---

**S6.1 — Escrever as Seções 5 e 6**
`P0 · Crítico` · `Artigo e banca` · Entrega 26/08

> **Seção 5 — Desenvolvimento do artefato:** arquitetura implementada, decisões tomadas
> durante as sprints, diagramas, telas, trechos de código representativos (política de
> RLS, cálculo de XP, migration do feedback anônimo).
>
> **Seção 6 — Resultados:** SUS, taxa de conclusão, análise temática, confronto com os
> critérios, o que você mudaria.
>
> **Se você escreveu o `DIARIO.md` todo dia, aqui você está editando, não escrevendo.**
> É por isso que ele existe.

---

**S6.2 — Converter o artigo para o pretérito**
`P1 · Essencial` · `Artigo e banca` · Entrega 26/08

> Todo o texto está em futuro: "será implementado", "prevê-se", "está previsto". Na versão
> final, tudo que foi feito vira pretérito.
>
> Trabalho mecânico e grande. Seções 2.7, 4.4 e 4.5 são as mais afetadas.

---

**S6.3 — Aplicar as revisões do ranking**
`P1 · Essencial` · `Artigo e banca` · Entrega 26/08

> Textos prontos em `docs/REVISOES-ARTIGO-ranking.md`. Quatro pontos: seção 2.3
> (distinção entre mecânica de gamificação e indicador gerencial), seção 2.6 (dois regimes
> de dados), Quadro 4 (acrescentar RF12) e seção 4.4.

---

**S6.4 — Exportar diagramas e montar apêndices**
`P2 · Complementar` · `Artigo e banca` · Entrega 27/08

> ```bash
> mmdc -i docs/diagramas/01-der.mmd -o docs/diagramas/png/01-der.png -b transparent -s 3
> ```
>
> `-s 3` gera em 3× a resolução — sem isso sai borrado no PDF impresso.
>
> Apêndices A a E: mapa de funcionalidades, roteiro, SUS, TCLE, capturas do sistema.

---

**S6.5 — Slides e ensaios**
`P0 · Crítico` · `Artigo e banca` · `Prazo fixo` · Entrega 28/08

> - [ ] Slides da banca
> - [ ] **Dois ensaios cronometrados**, com alguém fazendo perguntas hostis
> - [ ] Ensaiar a demonstração ao vivo da defesa em profundidade
> - [ ] Confirmar que o vídeo do plano B está acessível
>
> Estude as 10 perguntas prováveis em `docs/ESTUDO-DO-PRODUTO.md`, seção 7 — cobrindo a
> coluna de respostas.

---

### 📋 Backlog — complementares · 4 cartões

> **Só entram se os essenciais estiverem concluídos e testados.** O artigo os classifica
> como complementares; o sistema cumpre a proposta sem eles.

---

**RF03 — Notificação por e-mail em feedback urgente**
`P2 · Complementar`

> E-mail ao Administrador quando um feedback é marcado como urgente. O protótipo já tinha
> o evento `FeedbackUrgenteCriado` disparando — só falta o listener de envio.

---

**RF09 — Destaque mensal de maior participação**
`P2 · Complementar`

> Cálculo automático ao final do mês. **É a única exposição pública entre pares prevista
> no artigo** (seção 2.3) — por isso é conceitualmente relevante mesmo sendo complementar.

---

**RF10 — Mural de comunicados**
`P2 · Complementar`

> ⚠️ No protótipo, `GET /comunicados` era **público, sem autenticação**. Se reimplementar,
> autentique e escope por organização.

---

**RF12 — Ranking interno de participação**
`P2 · Complementar` · `Segurança e isolamento`

> Decidido em 04/08. Ordena **exclusivamente por XP acumulado**. Visível **apenas ao
> `admin`** — gestor e colaborador recebem 403, com teste provando.
>
> **Nunca ordena, exibe ou deriva humor por colaborador.**
>
> **O argumento:** a crítica da literatura a rankings dirige-se à competição entre pares, e
> competição entre pares pressupõe que o colaborador veja sua posição. Como só o
> Administrador vê, não é mecânica de gamificação — é indicador gerencial.
>
> Exige as revisões do cartão S6.3 no artigo.

---

## 4. Resumo

| Lista | Cartões |
|---|---|
| 📌 Contexto fixo | 5 |
| 📋 Backlog (complementares) | 4 |
| 🎯 Sprints 0 a 5 + fechamento | 29 |
| **Total** | **38** |

**Manutenção:** o quadro é atualizado no fechamento de cada sprint, a partir do que foi
realmente commitado — nunca por expectativa.
