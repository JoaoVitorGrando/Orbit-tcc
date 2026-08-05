# Orbit RH — Plano de Projeto

**Responsável:** João Vitor Souza Grando
**Início:** 04/08/2026 · **Entrega:** 28/08/2026 · **24 dias**
**Escopo alvo:** 8 requisitos essenciais funcionando ponta a ponta + avaliação de
usabilidade (SUS) com 8 voluntários
**Capacidade declarada:** 8h/dia (~192h)

> Cronograma corrigido em 04/08/2026. O planejamento original havia sido montado sobre
> uma data de início equivocada (28/07); esta versão reflete o calendário real.

---

## 0. Contexto

A fundamentação teórica do projeto está consolidada: o problema é real, a
fundamentação reconhece explicitamente as limitações da literatura de RH 4.0 (natureza
prescritiva, evidência condicional da gamificação, humor em 3 níveis como indicador de
tendência e não medida psicométrica validada), e o recorte da lacuna de pesquisa é
defensável.

O artefato está sendo reconstruído desde a fundação, com três objetivos:

1. **Rastreabilidade técnica completa.** Toda decisão de arquitetura precisa ter
   justificativa registrada e ser reproduzível a partir do histórico de commits.
2. **Depurabilidade.** Um código cuja lógica não está documentada e compreendida não é
   depurável com eficiência quando surgem bugs — e surgirão, ao longo de 24 dias.
3. **Integridade metodológica.** A metodologia declarada no projeto descreve práticas
   de Scrum com testes automatizados escritos junto a cada incremento. O processo
   registrado em `DIARIO.md` e no histórico do Git precisa corresponder ao processo
   efetivamente executado.

Laravel + React + PostgreSQL é um stack maduro para o escopo definido: CRUD
bem-arquitetado com três regras de negócio centrais (unicidade do check-in diário,
cálculo de XP e ofensiva, isolamento multitenant em duas camadas independentes).

---

## 1. Regra que governa o cronograma

> **Nenhuma linha de código entra no repositório sem que possa ser explicada em voz
> alta para uma pessoa que não a escreveu.**

Operacionalização diária:

| Momento | Prática |
|---|---|
| Antes de implementar | Registrar em 2 frases o comportamento esperado e a camada de destino (controller? model? middleware?) |
| Depois de implementar | Reescrever de memória o que o código faz, em comentário de cabeçalho ou no diário técnico, antes de commitar |
| Fim do dia | Uma entrada em `DIARIO.md`: entregas, impedimentos, questões em aberto |

`DIARIO.md` é a evidência do processo iterativo e a matéria-prima da seção de
desenvolvimento do artefato no relatório final.

---

## 2. Correções necessárias na redação do relatório final

### 2.1 Bloqueadores (prioridade máxima)

| # | Problema | Onde | Correção |
|---|---|---|---|
| B1 | Referência com placeholder não preenchido: `SOUZA, [autor]. Protótipo de aplicativo... [Trabalho de Conclusão de Curso/Periódico], 2025.` | Referências | Localizar a referência completa (autor, instituição, tipo, ano) ou remover do corpus. Se removido, o corpus cai de 5 para 4 e as seções que o discutem precisam de ajuste. |
| B2 | Ano inconsistente de Abdulgalimov: 2024 em um trecho, 2020 em outro; a referência original é CHI 2020 | Seções de fundamentação | Padronizar para 2020. Como está fora do recorte temporal declarado (2024–2026), justificar a inclusão por *snowballing* / referência seminal. |
| B3 | Contradição sobre a etapa de validação: um trecho descreve implantação em uma organização parceira; outro descreve voluntários sem vínculo organizacional | Seções de metodologia e avaliação | Reescrever o primeiro trecho como implantação piloto futura, alinhado ao restante do texto. |
| B4 | Palavras-chave em português sem correspondência exata com as keywords em inglês | Resumo | Normas exigem correspondência. Traduzir fielmente um conjunto único. |
| B5 | Texto integralmente em tempo futuro ("será implementado", "prevê-se") | Seções de metodologia e resultados | Converter para pretérito na fase de fechamento — trabalho mecânico, reservar tempo dedicado. |

### 2.2 Pontos de maior escrutínio na avaliação

| # | Questionamento provável | Resposta fundamentada |
|---|---|---|
| A1 | O mapeamento de literatura usou uma única string, uma única base, e chegou a 5 trabalhos — isso caracteriza um mapeamento sistemático? | Ampliar a busca para múltiplas bases (Scopus, IEEE Xplore, ACM DL, SciELO) e reportar a contagem por base, ou reclassificar como mapeamento exploratório e reforçar a limitação no texto. |
| A2 | Onde está o fluxograma de seleção dos trabalhos? | Produzir um fluxo `identificados → após duplicatas → triados → elegíveis → incluídos`, no padrão do protocolo de referência (Kitchenham). |
| A3 | A afirmação de "anonimato técnico irreversível" precisa de prova. | Teste automatizado que cria um feedback anônimo e confirma ausência de autor na tabela, citado junto à migration correspondente. |
| A4 | Row Level Security e Global Scope não é redundante? | É redundante por decisão de projeto — defesa em profundidade. Demonstrável: comentar o Global Scope e mostrar que a RLS ainda barra o acesso. |
| A5 | Oito voluntários constituem amostra suficiente? | Generalização analítica, não estatística — já fundamentado no texto. Reforça-se com a literatura de usabilidade formativa (amostras pequenas revelam a maioria dos problemas de interface). |
| A6 | O check-in de humor em 3 níveis atende à NR-1? | A NR-1 exige identificar, avaliar e gerenciar riscos psicossociais. O sistema instrumenta a identificação contínua e gera registro de monitoramento; não substitui avaliação de risco psicossocial conduzida por profissional. Essa distinção é declarada explicitamente no texto. |

### 2.3 Estrutura pendente na versão final

- **Seção de desenvolvimento do artefato** (nova): arquitetura implementada, decisões de
  projeto tomadas durante as sprints, diagramas, telas do sistema concluído, trechos de
  código representativos (política de RLS, cálculo de XP, migration do feedback
  anônimo).
- **Seção de resultados e discussão** (nova): resultados do SUS, taxa de conclusão de
  tarefas, análise temática das entrevistas, confronto com os critérios de aceitação.
- **Considerações finais** (reescrita, com resultados consolidados).
- **Apêndices** citados no texto, a produzir: mapa de funcionalidades, roteiro de
  tarefas guiadas, questionário SUS, termo de consentimento, capturas do sistema.

Os apêndices de roteiro de tarefas, questionário SUS e termo de consentimento precisam
estar prontos antes da coleta (Sprint 5).

### 2.4 Verificação factual

Dados externos citados no texto, conferidos contra as fontes originais:

- Gallup — 20% de engajamento global em 2025, ~US$ 10 trilhões em produtividade
  perdida: confere com o relatório de referência. Precisão de redação recomendada: o
  valor é produtividade perdida estimada, não custo contábil direto.
- Turnover no Brasil ~51% ao ano, custo superior a R$ 600 bilhões: confere com a fonte
  setorial citada.
- PMEs como mais de 99% das empresas brasileiras e ~30% do PIB: confere com dados do
  Sebrae.

Nenhuma correção necessária nesses números.

---

## 3. Cronograma

Cada sprint entrega um incremento funcional, testado e commitado. Item que não couber
desce para "trabalho futuro" no relatório final — não é transferido para a sprint
seguinte.

### Sprint 0 — Fundação e domínio (04–05/08, 2 dias)

Objetivo: ambiente e modelo de dados estabelecidos. Nenhuma funcionalidade de negócio.

- Repositório novo, distinto do protótipo (preservado como referência visual).
- Esqueleto da aplicação: Laravel 11 + Sanctum, PostgreSQL, React + Vite + Tailwind.
- Modelagem de dados no papel antes de qualquer migration. Entidades mínimas:
  `organizations`, `branches`, `users`, `mood_checkins`, `feedbacks`, `career_plans`,
  `career_plan_steps`, `user_competencies`, `xp_events`, `badges`, `user_badges`.
- Toda tabela de negócio com `organization_id` desde a primeira migration.
- Seeders com dados determinísticos: 2 organizações, 3 filiais, ~20 usuários.
- **Critério de pronto:** o modelo entidade-relacionamento é reproduzível de memória.

### Sprint 1 — Núcleo de segurança (06–10/08, 5 dias)

Sprint de maior peso técnico — concentra a contribuição central declarada (RNF01–RNF04).

- **RF11** — RBAC com 3 papéis via middleware.
- Autenticação Sanctum, proteção de rotas no front-end.
- **RNF02** — Global Scope no Eloquent, injetando `organization_id` e `branch_id`.
- **RNF03** — Políticas de Row Level Security no PostgreSQL. Depende de a conexão
  definir uma variável de sessão (`SET app.current_org`) a cada requisição — ponto que
  historicamente consome mais tempo do que o previsto.
- **Testes obrigatórios:** usuário da organização A não lê registro da organização B;
  gestor da filial 1 não lê filial 2; colaborador não acessa endpoint de gestor;
  **defesa em profundidade** — com o Global Scope desabilitado, a RLS ainda barra.

### Sprint 2 — Escuta contínua (11–15/08, 5 dias)

- **RF01** — Check-in diário de humor, 3 níveis, 1x/dia. Unicidade por
  usuário/dia resolvida no timezone da organização, testada na transição 23h59 → 00h01.
- **RF04** — Motor de XP como tabela de eventos (`xp_events`), não contador —
  auditável e reconstruível.
- **RF02** — Canal de feedback bidirecional com opção anônima. Quando anônimo, o
  `user_id` não é gravado — ausente, não nulo-mas-preenchido nem criptografado.

### Sprint 3 — Desenvolvimento individual (16–19/08, 4 dias)

- **RF05** — Plano de Progressão de Carreira: níveis, etapas, emblemas automáticos.
- **RF06** — Portfólio de competências no perfil.
- **RF07** — Busca por competência pela liderança, **inclusive entre filiais** —
  exceção deliberada ao isolamento, documentada e restrita por papel.

Trecho mais apertado do cronograma: três requisitos em quatro dias.

### Sprint 4 — Painel e congelamento (20–22/08, 3 dias)

- **RF08** — Painel de indicadores agregados. Nunca humor individual identificável;
  indicador de grupo com menos de 5 respondentes não é exibido.
- Polimento de interface, responsividade, mensagens de erro, estados vazios.
- Seed de demonstração com histórico fictício de 30 dias.
- Apêndices de roteiro, questionário SUS e termo de consentimento finalizados.
- Confirmação dos 8 voluntários para a avaliação.

**Code freeze em 22/08 às 23h.** A partir daqui, apenas correção de bug crítico.

### Sprint 5 — Avaliação (23–25/08, 3 dias)

- Sessões com os 8 voluntários, distribuídas entre os 3 papéis simulados. Protocolo:
  tarefas guiadas → observação não intervencionista → questionário SUS → entrevista
  curta.
- Tabulação: escore SUS médio e desvio-padrão, taxa de conclusão por tarefa, análise
  temática das entrevistas, confronto com os critérios de aceitação.

Um escore abaixo do critério de referência (68) não invalida a avaliação — é tratado
como evidência de revisão de requisitos ou design, coerente com o caráter iterativo do
método adotado. Resultado analisado com honestidade tem mais valor metodológico do que
resultado favorável sem discussão.

### Fechamento (26–28/08, 3 dias)

- Redação das seções de desenvolvimento e resultados. Conversão integral do texto para
  o pretérito. Correção dos bloqueadores B1–B5.
- Revisão completa, formatação, apêndices, material de apresentação.
- Ensaios cronometrados com perguntas de sabatina técnica. Preparação de contingência
  para a demonstração ao vivo (vídeo gravado).

---

## 4. Ambiente e ferramentas

| Frente | Ambiente |
|---|---|
| Código, migrations, testes, depuração | Terminal de desenvolvimento, dentro do repositório |
| Documentação, apêndices, análise de dados, apresentação | Ambiente de redação e planilhas |
| Acompanhamento de sprint | Quadro Kanban (ver `docs/QUADRO-TRELLO.md`) |

O processo de trabalho — ciclo diário, ciclo de sprint, disciplina de revisão — está
detalhado em `FLUXO-DE-TRABALHO.md`.

---

## 5. Riscos e mitigações

| Risco | Prob. | Impacto | Mitigação |
|---|---|---|---|
| RLS no PostgreSQL consumir mais tempo que o previsto | Alta | Alto | Parte mais técnica do projeto. Se ultrapassar 1,5 dia, entregar com Global Scope + testes provando isolamento, reposicionando a RLS como camada parcialmente implementada. Tentar a implementação completa primeiro — é o diferencial técnico do trabalho. |
| Não reunir 8 voluntários | Média | Alto | Convidar 12 para garantir 8. Não é necessário vínculo com a área de RH. |
| Expansão de escopo durante o desenvolvimento | Alta | Alto | RF03, RF09 e RF10 são complementares por definição. Só entram após code freeze, se houver tempo. |
| Bug crítico durante a demonstração | Média | Médio | Vídeo de contingência de 4 min do fluxo completo, gravado antes do freeze. |
| Concentração da redação final em um único dia | Alta | Alto | Mitigado pelo `DIARIO.md` diário — três parágrafos por sprint tornam a redação final uma edição, não uma escrita do zero. |

---

## 6. Estado atual e próximas ações

**Concluído (Sprint 0, dias 1–2):** repositório inicializado; Laravel 11 + Sanctum
conectado a PostgreSQL; front-end Vite + React com o design system integrado e validado
por teste de fumaça.

**Em andamento:** modelagem de dados e migrations de domínio (S0.4); seeders
determinísticos (S0.5).

**Pendências que não têm solução de última hora:**

- Confirmação junto à orientação sobre exigência de submissão ao Comitê de Ética em
  Pesquisa — decisão que, se afirmativa, altera o cronograma da Sprint 5 por completo.
- Convite aos 12 candidatos a voluntários para a avaliação de usabilidade, com
  antecedência suficiente para garantir 8 confirmados.
- Localização ou remoção da referência incompleta (bloqueador B1).
