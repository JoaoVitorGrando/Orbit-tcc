# Orbit RH — Plano Mestre de 30 Dias

**Orientando:** João Vitor Souza Grando
**Início:** 28/07/2026 · **Apresentação:** 28/08/2026
**Escopo alvo:** 8 requisitos essenciais funcionando ponta a ponta + SUS aplicado com 8 voluntários
**Capacidade declarada:** 8h/dia (~240h)

---

## 0. Diagnóstico de abertura

Seu artigo está bom. Está acima da média de qualificação: o problema é real, a fundamentação é honesta (você reconhece que a literatura de RH 4.0 é prescritiva, que gamificação tem evidência condicional, que humor em 3 níveis não é UWES — isso é maturidade), e o recorte da lacuna é defensável.

**O risco do seu TCC não é o prazo. É que você não entende o código que a IA escreveu.**

Isso é fatal por três motivos:

1. **Na banca**, a pergunta "por que você usou Row Level Security se já tem Global Scope na aplicação?" tem resposta no seu artigo (defesa em profundidade, seção 2.5). Mas se te perguntarem *"me mostre onde isso está implementado e o que acontece se eu comentar essa linha"*, você precisa saber. Bancas de Sistemas/Engenharia perguntam isso.
2. **Tecnicamente**, código que você não entende você não consegue depurar. Em 30 dias você vai encontrar bugs. Se cada bug vira uma sessão de "IA, conserta aí", você perde controle do cronograma.
3. **Metodologicamente**, seu artigo afirma na seção 4.4 que você adotou "práticas do Scrum" com "testes automatizados com PHPUnit escritos junto a cada incremento". Se o código veio pronto de um prompt, essa afirmação é falsa. Isso é um problema de integridade, não de técnica.

**A boa notícia:** refazer do zero com 8h/dia é totalmente viável para os 8 essenciais. Laravel + React + PostgreSQL é um stack maduro, e seu escopo é CRUD bem-arquitetado com três regras de negócio interessantes (unicidade do check-in diário, cálculo de XP/ofensiva, isolamento multitenant). Nada disso é difícil. O que é difícil é fazer *e entender* em 30 dias — e é para isso que serve este plano.

---

## 1. A regra que governa o mês inteiro

> **Nenhuma linha de código entra no repositório sem que você consiga explicá-la em voz alta para uma pessoa que não a leu.**

Operacionalização prática, todo dia:

| Momento | O que você faz |
|---|---|
| Antes de pedir código | Escreve em 2 frases o que a funcionalidade deve fazer e **por que** você acha que ela vai onde vai (controller? model? middleware?). Se não sabe, pergunte *primeiro* — não peça o código. |
| Depois de receber código | Fecha a resposta da IA. Reescreve o que o código faz, de memória, em comentário no topo do arquivo ou no seu diário de sprint. Só então commita. |
| Fim do dia | Um parágrafo no `DIARIO.md`: o que fiz, o que quebrou, o que ainda não entendo. A terceira coluna é a mais importante. |

O `DIARIO.md` não é burocracia — ele vira a **evidência do seu processo de Scrum** e material bruto da seção de Resultados do artigo. Você vai agradecer no dia 25.

---

## 2. Correções necessárias no artigo

Trabalhei o PDF do início ao fim. Abaixo, por criticidade.

### 2.1 Bloqueadores (corrigir esta semana)

| # | Problema | Onde | Correção |
|---|---|---|---|
| B1 | **Referência com placeholder não preenchido:** `SOUZA, [autor]. Protótipo de aplicativo... [Trabalho de Conclusão de Curso/Periódico], 2025.` | Referências, p. 24 | Isso é o tipo de coisa que a banca circula em vermelho. Ou você localiza a referência completa (autor, instituição, tipo, ano) ou remove o trabalho do corpus. Se removeu, o corpus cai de 5 para 4 e o texto de 3.2, 3.3, 3.4 e o Quadro 2 precisam ser ajustados. **Souza é o trabalho mais próximo do seu check-in de humor — vale o esforço de localizar.** |
| B2 | **Ano inconsistente de Abdulgalimov:** o texto em 3.3 diz "Abdulgalimov et al. (2024)"; em 3.4 e no Quadro 2 diz 2020; a referência é CHI 2020. | p. 12, 13, 14 | Padronizar para **2020** em todos os lugares. Também revise o recorte temporal: se o critério CI2 é "2024 a 2026", um trabalho de 2020 no corpus contradiz o próprio protocolo. Você precisa **ou** ajustar o CI2, **ou** justificar Abdulgalimov como inclusão por *snowballing* / referência seminal fora do recorte. A segunda opção é mais honesta e a banca aceita bem. |
| B3 | **Contradição sobre a empresa parceira.** Seção 2.4: "para a etapa de validação, conduzida em **uma única organização parceira com múltiplas filiais**, a plataforma será implantada em modo dedicado". Seção 4.5: avaliação com "voluntários... **sem vínculo com uma organização específica**". Seção 4.6 reforça que a empresa não é estudo de caso. | p. 7-8 vs p. 19-20 | A seção 2.4 é resquício de uma versão anterior do escopo. Reescreva o trecho final de 2.4 para falar de **implantação piloto futura**, alinhando com o que já está nas Considerações Finais ("a partir do ano seguinte à conclusão deste trabalho"). |
| B4 | **Palavras-chave PT ≠ Keywords EN.** PT: Turnover, Engajamento, Pulse survey, Gamificação, Retenção de talentos. EN: Strategic people management, Engagement, Pulse survey, Gamification, Personal data protection. | p. 1-2 | Normas exigem correspondência. Escolha um conjunto e traduza fielmente. Sugestão: *Gestão estratégica de pessoas · Engajamento · Pulse survey · Gamificação · Proteção de dados pessoais* (esse conjunto cobre melhor as 4 dimensões da sua contribuição do que "Turnover/Retenção"). |
| B5 | **Todo o artigo está em tempo futuro.** "será implementado", "prevê-se", "está previsto". | Seções 2.7, 4.4, 4.5 | Na versão final, tudo que foi feito vira **pretérito**. Este é um trabalho mecânico grande — reserve o dia 26/08 só para isso. Não deixe para a véspera. |

### 2.2 Pontos que a banca vai atacar (prepare a defesa)

| # | O ataque provável | Sua defesa |
|---|---|---|
| A1 | *"353 registros, uma única string, uma única base, e sobraram 5 trabalhos. Isso é um mapeamento sistemático?"* | Duas saídas. **(a)** Ampliar: rode a mesma string em Scopus, IEEE Xplore, ACM DL e SciELO, e reporte a contagem por base numa tabela. Custo: 1 dia. **(b)** Reposicionar: chame de "mapeamento exploratório" e assuma a limitação com mais força em 3.6. Recomendo **(a)** — 1 dia de trabalho compra muita legitimidade, e você não precisa mudar as conclusões, só engrossar o denominador. |
| A2 | *"Cadê o fluxograma de seleção?"* | Kitchenham espera um fluxo `identificados → após duplicatas → triados → elegíveis → incluídos`. Você tem os números (353 → 5) mas não o caminho. Faça uma figura simples (estilo PRISMA). Custo: 1h. Alto retorno. |
| A3 | *"Você diz que o feedback anônimo tem 'anonimato técnico irreversível'. Prove."* | Essa é sua afirmação mais forte e mais atacável. Precisa de **evidência executável**: um teste PHPUnit que cria um feedback anônimo e asserta que a tabela não possui coluna de autor preenchida, mais o `\Schema::getColumnListing` ou o próprio migration mostrado no apêndice. Coloque isso no roteiro da apresentação — é seu melhor momento de demo. |
| A4 | *"Por que RLS **e** Global Scope? Não é redundante?"* | É redundante **de propósito** — está escrito na 2.5. Mas prepare a demonstração: comente o Global Scope, mostre que a RLS ainda barra. É um dos momentos mais impressionantes que você pode ter na banca. Ensaie. |
| A5 | *"8 voluntários é amostra suficiente?"* | Sua defesa já está no artigo (generalização analítica, não estatística — p. 20). Reforce com Nielsen: ~5 usuários revelam a maioria dos problemas de usabilidade em testes formativos. Cite. |
| A6 | *"O check-in de humor em 3 níveis atende mesmo à NR-1?"* | Cuidado aqui. Seu artigo já é honesto (2.2: "sinal de tendência, e não diagnóstico"). Mantenha essa modéstia na fala. A NR-1 exige **identificar, avaliar e gerenciar**; o Orbit RH instrumenta a *identificação contínua* e gera *registro de evidência de monitoramento*. Ele não substitui avaliação de risco psicossocial feita por profissional. Diga isso antes que perguntem. |

### 2.3 Estrutura que falta para a versão final

O artigo atual é de qualificação e termina na Metodologia + Considerações. A versão de defesa precisa de:

- **Seção 5 — Desenvolvimento do artefato** (nova): arquitetura implementada, decisões de projeto tomadas durante as sprints, diagramas (entidade-relacionamento, componentes), telas do sistema pronto, trechos de código representativos (RLS policy, cálculo de XP, migration do feedback anônimo).
- **Seção 6 — Resultados e discussão** (nova): resultados do SUS (escore médio, desvio, distribuição por item), taxa de conclusão das tarefas, análise temática das entrevistas, confronto com os 3 critérios de aceitação, e o que você mudaria.
- **Seção 7 — Considerações finais** (reescrita a partir da atual, agora com resultados na mão).
- **Apêndices A–E** — citados no texto, ausentes no PDF. Você precisa produzir: (A) mapa de funcionalidades/backlog, (B) roteiro de tarefas guiadas, (C) questionário SUS, (D) TCLE, (E) telas do protótipo.

Os apêndices B, C e D você precisa **antes** de rodar o teste (dia 21/08). Eu monto com você na Sprint 4.

### 2.4 Verificação factual

Confirmei os dados externos citados:

- ✅ Gallup: 20% de engajamento em 2025, custo de ~US$ 10 trilhões — confere com o relatório 2026. Sugestão de precisão na redação: o valor é *produtividade perdida estimada*, não custo contábil.
- ✅ Turnover Brasil ~51% e custo > R$ 600 bi/ano — confere com Sólides.
- ✅ PMEs > 99% das empresas e ~30% do PIB — confere com Sebrae.

Seus números estão certos. Não mexa neles.

---

## 3. Cronograma — 5 sprints + fechamento

Cada sprint termina com **incremento funcionando, testado e commitado**. Se um item não coube, ele desce para "evolução futura" no artigo — não empurre para a sprint seguinte, isso destrói cronogramas.

### Sprint 0 — Fundação e domínio (28–30/07, 3 dias)

O objetivo aqui **não é código de funcionalidade**. É você recuperar o controle.

**Dia 1 (28/07)**
- Arquive o projeto antigo em `orbit-rh-legacy/`. Não delete: ele é sua referência de "o que eu queria fazer" e fonte de telas.
- Repositório novo, do zero: `git init`, primeiro commit vazio.
- Suba o esqueleto: Laravel 11 + Sanctum, PostgreSQL, React + Vite + Tailwind. **Você digita os comandos**, não copia um dump.
- Escreva o `CLAUDE.md` na raiz (arquivo pronto entregue junto com este plano).

**Dia 2 (29/07)**
- Modelagem de dados no papel, à mão, antes de qualquer migration. Entidades mínimas: `organizations`, `branches`, `users`, `roles`, `mood_checkins`, `feedbacks`, `career_plans`, `career_plan_steps`, `user_competencies`, `xp_events`, `badges`.
- Toda tabela de dados de negócio carrega `organization_id` e, quando aplicável, `branch_id`. Essa é a espinha do multitenant — decida agora, não depois.
- Migrations escritas e rodando. `php artisan migrate:fresh` limpo.

**Dia 3 (30/07)**
- Seeders com dados fictícios realistas: 2 organizações, 3 filiais, ~20 usuários. Você vai usar isso o mês inteiro e na demo da banca.
- Pipeline de testes rodando: `php artisan test` verde com 1 teste trivial.
- **Critério de pronto da sprint:** você consegue desenhar o diagrama ER de memória em um papel.

---

### Sprint 1 — Núcleo de segurança (31/07–05/08, 6 dias)

Esta é a sprint mais importante do TCC. É onde mora sua contribuição técnica declarada (RNF01–RNF04) e é o que a banca vai sondar.

- **RF11** — RBAC com 3 papéis (Administrador, Gestor, Colaborador) via middleware.
- Autenticação Sanctum, login/logout, proteção de rotas no React.
- **RNF02** — Global Scope no Eloquent injetando `organization_id` + `branch_id` automaticamente.
- **RNF03** — Políticas de Row Level Security no PostgreSQL. Esta é a parte que mais gente erra: a RLS depende de a conexão setar uma variável de sessão (`SET app.current_org`). Reserve tempo.
- **Testes obrigatórios** (são a prova da sua tese técnica):
  - Usuário da org A não lê registro da org B — nem via API, nem via query direta.
  - Gestor da filial 1 não lê dados da filial 2.
  - Colaborador não acessa endpoint de gestor.
  - **Teste de defesa em profundidade:** com o Global Scope desabilitado, a RLS ainda barra.

Esse último teste é o que você mostra na banca. Ele vale mais que 10 slides.

---

### Sprint 2 — Escuta contínua (06–11/08, 6 dias)

- **RF01** — Check-in diário de humor, 3 níveis, 1x/dia.
  - Regra crítica: unicidade por usuário/dia. Cuidado com fuso horário — "um dia" é ambíguo. Defina o timezone da organização e teste a transição de data (23h59 → 00h01). Seu artigo já cita esse cenário de teste na 4.4; entregue-o.
- **RF04** — Motor de XP: pontos por check-in, dobro em ofensiva de dias consecutivos, pontos por metas e por avanço no PPC.
  - Modele como tabela de eventos (`xp_events`), não como contador no `users`. Contador você não audita nem explica; log de eventos você reconstrói e prova.
- **RF02** — Canal de feedback bidirecional com opção anônima.
  - **Anonimato técnico:** quando anônimo, o `user_id` simplesmente **não é gravado**. Não é `null`ável-mas-preenchido, não é criptografado — é ausente. Escreva o teste que prova isso.

---

### Sprint 3 — Desenvolvimento individual (12–17/08, 6 dias)

- **RF05** — Plano de Progressão de Carreira: níveis, etapas, emblemas atualizados automaticamente.
- **RF06** — Portfólio de competências no perfil (auto-declaradas + conquistas do PPC).
- **RF07** — Busca por competência pela liderança, **inclusive entre filiais**.
  - Atenção: essa busca **atravessa a barreira de filial** de propósito (mobilidade interna). Ou seja, é uma exceção deliberada ao seu próprio isolamento. Você precisa implementá-la explicitamente e **justificá-la no artigo** — não deixe parecer um furo na RLS. É um ponto sofisticado; a banca vai gostar se você levantar antes deles.

---

### Sprint 4 — Painel, congelamento e protocolo (18–21/08, 4 dias)

- **RF08** — Painel de indicadores agregados por unidade. Regra de ouro: **nunca exibir humor individual**. Defina um n mínimo (ex.: não mostrar indicador de grupo com menos de 5 respondentes) — isso é privacidade por design e um ótimo detalhe de defesa.
- Polimento de UX, responsividade (RNF06), mensagens de erro, estados vazios.
- Seed de demonstração com 30 dias de histórico fictício, para o painel não aparecer vazio no teste.
- **Produção dos apêndices B, C e D** — roteiro de tarefas guiadas, questionário SUS em português e TCLE.
- Recrutamento dos 8 voluntários (comece a convidar **agora**, não no dia 21).

**🔒 CODE FREEZE em 21/08 às 23h.** A partir daqui, só corrige bug crítico. Testar um alvo móvel invalida o teste.

---

### Sprint 5 — Avaliação (22–25/08, 4 dias)

- **22–24/08:** sessões com os 8 voluntários. 2–3 por dia, ~40min cada. Distribua entre os 3 papéis.
  - Protocolo: tarefas guiadas → observação não intervencionista (anote onde travam, **não ajude**) → SUS → entrevista curta (3 perguntas).
- **25/08:** tabulação e análise.
  - SUS: escore médio, desvio-padrão, distribuição por item.
  - Taxa de conclusão por tarefa.
  - Análise temática das entrevistas (códigos → categorias).
  - Confronto com os 3 critérios de aceitação.

**Se o SUS vier abaixo de 68, não é fracasso.** Seu artigo já blindou isso (p. 20): resultados abaixo do critério viram evidência de revisão, coerente com o caráter iterativo da DSR. Um TCC que reporta SUS 61 e analisa honestamente o porquê é *melhor* que um que reporta 84 sem discussão.

---

### Fechamento (26–28/08, 3 dias)

- **26/08:** escrita das Seções 5 e 6. Conversão de todo o artigo para pretérito. Correção dos bloqueadores B1–B5.
- **27/08:** revisão completa, formatação, apêndices, slides da banca.
- **28/08:** ensaio cronometrado (2x), com alguém fazendo perguntas hostis. Preparar a demo ao vivo com plano B (vídeo gravado, caso a rede falhe).

---

## 4. Como usar o Claude em cada fase

Você já viu o que acontece quando delega demais. O objetivo agora é usar a IA como **par**, não como fornecedor.

### 4.1 Onde trabalhar

Para desenvolvimento com git, testes e execução, **Claude Code no terminal** é a ferramenta certa — ela vive dentro do repositório. O Cowork (onde estamos agora) é melhor para o lado acadêmico: artigo, apêndices, slides, análise do SUS, planejamento.

Sugestão de divisão:

| Frente | Ferramenta |
|---|---|
| Código, migrations, testes, debug | Claude Code no terminal, dentro do repo |
| Artigo, apêndices, TCLE, roteiro SUS | Cowork (aqui) |
| Análise estatística do SUS | Cowork (planilha + gráficos) |
| Slides da banca | Cowork |

Se preferir fazer tudo aqui no Cowork, dá — basta você conectar a pasta do projeto. Só é um pouco mais lento para o ciclo de edita-roda-testa.

### 4.2 Recursos que valem a pena, por fase

**`CLAUDE.md` na raiz do projeto** — o mais importante de todos. É um arquivo de contexto lido automaticamente em toda sessão. Define stack, convenções, regras invioláveis (ex.: "toda migration de dado de negócio deve ter `organization_id`") e — crucialmente — **como você quer ser ensinado**. Entreguei um pronto junto com este plano.

**Plan mode (`Shift+Tab` duas vezes no Claude Code)** — força a IA a apresentar o plano antes de tocar em arquivo. Use no início de **toda** funcionalidade. É o antídoto direto para o seu problema: você aprova o desenho antes de existir código, então você entende o código porque entendeu o plano.

**`/init`** — se você quiser aproveitar algo do projeto antigo, rode `/init` dentro dele para gerar um mapa do que existe. Serve para você *entender o legado*, não para copiá-lo.

**Subagente `Explore`** — para responder "onde no projeto isso está implementado?" sem despejar arquivos inteiros no seu contexto. Útil quando o projeto crescer.

**Subagente `Plan`** — para decisões de arquitetura com trade-off. Ex.: "como implementar RLS no PostgreSQL com Laravel, considerando pool de conexões?"

**`/security-review`** — rode ao final da Sprint 1 e antes do code freeze. Segurança é requisito declarado do seu TCC (RNF01–RNF04); ter passado por revisão de segurança é algo que você **cita na banca**.

**Skills de documento** — `docx` para o artigo final formatado, `pptx` para os slides, `xlsx` para a planilha de tabulação do SUS. Use no Cowork, na fase de fechamento.

**Tarefas agendadas** — dá para agendar uma revisão diária às 19h que te pergunta o que foi entregue e atualiza o diário de sprint. Cerimônia de Scrum automatizada. Se quiser, eu configuro.

**Artefato de acompanhamento** — uma página HTML persistente com o burndown das 5 sprints, RFs concluídos e dias restantes. Bom para disciplina e ótimo como um slide da apresentação. Também posso montar.

### 4.3 Como pedir código (isso importa mais do que parece)

❌ **Não faça:** "cria o sistema de check-in de humor"

✅ **Faça:**
> "Vou implementar o RF01: check-in diário de humor, 3 níveis, um por usuário por dia. Meu palpite é que a regra de unicidade deve ficar em uma validation rule customizada, e não no controller, porque preciso reusar no seeder. Está certo? Antes de escrever código, me explique como o Laravel resolve isso e quais são as 2 alternativas. Depois eu decido e você implementa a que eu escolher."

A diferença é que na segunda você fica no comando. Você formula hipótese, recebe correção, decide. É assim que se aprende — e é assim que você chega na banca sabendo responder.

**Faça a IA te sabatinar.** Ao fim de cada sprint:
> "Você é a banca do meu TCC. Leia o código da Sprint 1 e me faça 8 perguntas técnicas difíceis sobre as decisões de arquitetura. Não me dê as respostas."

Responda por escrito. As que você errar viram estudo. Isso vale mais que qualquer revisão de slides.

---

## 5. Riscos e mitigações

| Risco | Prob. | Impacto | Mitigação |
|---|---|---|---|
| RLS no PostgreSQL consumir mais tempo que o previsto | Alta | Alto | É a parte mais técnica do projeto. Se travar >1,5 dia, entregue com Global Scope + testes provando isolamento, e reposicione a RLS como camada implementada parcialmente/trabalho futuro. **Mas tente de verdade — é seu diferencial.** |
| Não conseguir 8 voluntários | Média | Alto | Comece a convidar **hoje**. Convide 12 para garantir 8. Colegas de turma, familiares, colegas de trabalho. Não precisam ser de RH. |
| Escopo inflar ("só mais essa telinha") | Alta | Alto | RF03, RF09 e RF10 são **complementares** — o próprio artigo os classifica assim. Só entram se sobrar tempo depois do code freeze. Não negocie isso consigo mesmo. |
| Bug crítico durante a demo | Média | Médio | Grave um vídeo de 4 min do fluxo completo em 21/08. É seu plano B. |
| Escrever as seções 5 e 6 em 1 dia | Alta | Alto | Por isso o `DIARIO.md` diário. Se você escrever 3 parágrafos por sprint sobre decisões de projeto, no dia 26 você está *editando*, não *escrevendo*. |

---

## 6. Primeiras 5 ações, em ordem

1. **Hoje:** arquive o projeto antigo, crie o repositório novo, coloque o `CLAUDE.md` na raiz, primeiro commit.
2. **Hoje:** mande mensagem para 12 pessoas convidando para o teste de usabilidade de 22–24/08. Não deixe para depois.
3. **Hoje:** decida sobre a referência SOUZA (B1) — localizar ou remover.
4. **Amanhã:** modelagem de dados no papel antes de qualquer migration.
5. **Amanhã:** crie o `DIARIO.md` e escreva a primeira entrada.

---

## 7. O que eu faço em seguida, se você quiser

- Rodar a string de busca em Scopus/IEEE/ACM/SciELO e montar a tabela por base + fluxograma de seleção (resolve A1 e A2 em um dia).
- Escrever o Apêndice B (roteiro de tarefas guiadas para os 3 papéis) e o Apêndice D (TCLE).
- Montar a planilha de tabulação do SUS já com as fórmulas de escore prontas.
- Configurar a revisão diária de sprint automatizada.
- Montar o artefato de acompanhamento das sprints.
- Trabalhar contigo, dia a dia, no código.

Diga por onde começamos.
