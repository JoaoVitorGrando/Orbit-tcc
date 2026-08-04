# Orbit RH — Contexto do Projeto

> Coloque este arquivo na **raiz do repositório**. Ele é lido automaticamente em toda sessão do Claude Code.

## O que é este projeto

Plataforma web multitenant para gestão estratégica de pessoas em pequenas e médias
empresas brasileiras. É o artefato de um TCC conduzido sob Design Science Research,
com apresentação em **28/08/2026**.

Três frentes: escuta contínua (check-in de humor + feedback anônimo), desenvolvimento
individual (gamificação + Plano de Progressão de Carreira) e apoio à decisão da
liderança (painel de indicadores agregados).

Conformidade com **NR-1** e **LGPD** é requisito arquitetural, não funcionalidade opcional.

---

## ⚠️ Como trabalhar comigo (leia antes de escrever código)

O autor deste projeto está reconstruindo o sistema **para dominá-lo**, porque precisa
defendê-lo diante de uma banca acadêmica. Velocidade não é o objetivo; compreensão é.

**Regras de interação, sem exceção:**

1. **Explique antes de implementar.** Para qualquer funcionalidade não trivial,
   apresente primeiro o plano: quais arquivos, qual camada, por quê. Espere aprovação.
2. **Ofereça alternativas.** Quando houver mais de uma forma razoável de resolver,
   apresente 2 e os trade-offs. Deixe a decisão com o autor.
3. **Nunca gere um bloco grande de código de uma vez.** Prefira incrementos que
   caibam em uma leitura.
4. **Comente o "porquê", não o "o quê".** `// incrementa contador` é inútil.
   `// usamos evento em vez de contador para permitir auditoria do XP` é útil.
5. **Ao terminar, faça uma pergunta de verificação** sobre o código que acabou de
   escrever. Se a resposta estiver errada, explique de novo antes de seguir.
6. **Não escreva código que você mesmo não conseguiria explicar em 3 frases.**
7. Responda em **português do Brasil**.

---

## Stack

- **Back-end:** Laravel 11, PHP 8.2+, API REST
- **Auth:** Laravel Sanctum (tokens), HTTPS obrigatório
- **Banco:** PostgreSQL (escolhido pelo suporte nativo a Row Level Security)
- **Front-end:** React + Vite + Tailwind CSS
- **Testes:** PHPUnit
- **Versionamento:** Git, commits vinculados a requisitos (`RF01: ...`)

---

## Regras invioláveis de arquitetura

Estas regras derivam de requisitos declarados no artigo do TCC. Violá-las quebra a
tese do trabalho.

### Isolamento multitenant (RNF02, RNF03)
- **Toda** tabela de dado de negócio tem `organization_id`. Quando fizer sentido,
  também `branch_id`.
- **Duas camadas independentes de isolamento**, sempre:
  1. Global Scope no Eloquent, aplicado automaticamente a toda query.
  2. Políticas de Row Level Security no PostgreSQL.
- Nunca remova uma camada "porque a outra já resolve". A redundância é deliberada
  (defesa em profundidade).
- Exceção documentada: a **busca por competência (RF07)** atravessa filiais de
  propósito, para apoiar mobilidade interna. Ela deve ser explícita, restrita a
  Administrador/Gestor, e nunca vazar entre organizações.

### Anonimato técnico (RNF04, LGPD)
- Feedback anônimo **não persiste o remetente**. O `user_id` não é gravado —
  não é nulo-mas-preenchido, não é criptografado, não é hash. É ausente.
- Deve existir teste automatizado que prova isso.

### Agregação no painel (RNF04, LGPD)
- O painel da liderança exibe **apenas indicadores agregados**.
- Nunca exibir humor individual identificável.
- Não exibir indicador de grupo com menos de 5 respondentes (evita reidentificação).

### Controle de acesso (RF11, RNF01)
- RBAC com exatamente 3 papéis: `admin`, `gestor`, `colaborador`.
- Aplicado por middleware no back-end **e** por proteção de rota no front-end.
- O front-end nunca é a única barreira.

### Arquivos
- Armazenamento privado, acesso via URLs assinadas. Nada em disco público.

---

## Requisitos funcionais

**Essenciais — escopo do TCC:**

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

**Complementares — fora do escopo até que os essenciais estejam prontos:**

| Código | Descrição |
|---|---|
| RF03 | Notificação por e-mail ao Administrador em feedback urgente |
| RF09 | Destaque mensal de maior participação |
| RF10 | Mural de comunicados |
| RF12 | Ranking interno de participação, restrito ao Administrador |

Se eu pedir para implementar RF03, RF09, RF10 ou RF12 antes de os essenciais estarem
concluídos e testados, **me lembre desta lista antes de começar.**

### Fora de escopo — não implementar

- **Recompensas e resgate de pontos.** O protótipo antigo tinha models `Reward`,
  `Recompensa`, `RewardRedemption`, `Resgate` e as telas correspondentes. Nada disso
  existe nos requisitos e não será reimplementado. Se eu pedir, questione.

### Regras específicas do ranking (RF12)

Decisões tomadas em 28/07/2026. Elas sustentam o argumento de privacidade do TCC:

- Ordena **exclusivamente por XP acumulado** (check-ins, ofensivas, metas, avanço no PPC).
- **Nunca ordenar, exibir ou derivar humor por colaborador.** Humor é dado próximo à
  esfera de saúde mental do titular; ranquear pessoas por humor viola o princípio
  declarado no artigo ("escutar continuamente sem vigiar individualmente"). Se algum
  pedido meu levar nessa direção, recuse e me avise.
- Visível **apenas ao papel `admin`**. O Gestor não acessa. O Colaborador não acessa.
  Deve haver teste automatizado provando que gestor e colaborador recebem 403.
- O ranking **não é mecânica de gamificação** — é indicador gerencial. O colaborador
  nunca vê sua posição relativa. A única exposição pública entre pares é o destaque
  mensal (RF09).

---

## Testes obrigatórios

Estes cenários são citados na metodologia do TCC. Devem existir e passar:

- [ ] Unicidade do check-in diário, incluindo transição de data (23h59 → 00h01)
- [ ] Cálculo de XP e de ofensivas de dias consecutivos
- [ ] Integridade do RBAC — colaborador não acessa endpoint de gestor
- [ ] Segregação por tenant via Global Scope
- [ ] Segregação por filial
- [ ] **Defesa em profundidade:** com o Global Scope desabilitado, a RLS ainda barra
- [ ] Feedback anônimo não persiste o remetente

O teste de defesa em profundidade é a evidência central da contribuição técnica do
trabalho. Trate-o como prioritário.

---

## Convenções

- Migrations: `snake_case`, plural (`mood_checkins`)
- Models: `PascalCase`, singular (`MoodCheckin`)
- Rotas API: `/api/v1/...`, kebab-case
- Commits: `RF01: adiciona validação de unicidade do check-in diário`
- Timezone: definido por organização. Nunca assuma o do servidor.

---

## Cronograma

| Sprint | Período | Entrega |
|---|---|---|
| 0 | 28–30/07 | Fundação, modelagem, seeders |
| 1 | 31/07–05/08 | Auth, RBAC, multitenant, RLS |
| 2 | 06–11/08 | Check-in, XP, feedback anônimo |
| 3 | 12–17/08 | PPC, portfólio, busca por competência |
| 4 | 18–21/08 | Painel, polimento, **code freeze 21/08** |
| 5 | 22–25/08 | Teste de usabilidade (SUS, 8 voluntários) |
| — | 26–28/08 | Artigo final, slides, ensaio |

Se uma tarefa ameaçar estourar a sprint, **avise** em vez de seguir. Cortar escopo é
decisão consciente, não acidente.
