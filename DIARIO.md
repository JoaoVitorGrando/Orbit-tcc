# Diário de Sprint — Orbit RH

Registro diário de decisões, obstáculos e aprendizados.

**Este arquivo tem três funções**, e a terceira é a que você vai agradecer no dia 26:

1. **Disciplina** — escrever o que não entendeu obriga a admitir o que não entendeu.
2. **Evidência de processo** — é o registro do seu Scrum, que a seção 4.4 do artigo afirma
   ter adotado.
3. **Matéria-prima da Seção 5** — as decisões de projeto que você vai relatar no artigo
   nascem aqui. Escrevendo três parágrafos por sprint, no dia 26 você estará *editando*,
   não *escrevendo do zero*.

**Regra:** uma entrada por dia trabalhado, mesmo curta. A terceira pergunta é obrigatória.

---

## Modelo de entrada

```markdown
### DD/MM — Sprint N — Dia X

**Entreguei:**

**Travei em:**

**Decisão de projeto tomada hoje (e por quê):**

**Ainda não entendo:**
```

> A pergunta "ainda não entendo" é a mais importante do documento. Deixá-la em branco por
> dois dias seguidos é sinal de que você voltou a aceitar código sem ler.

---

## Sprint 0 — Fundação e domínio (28 a 30/07)

**Objetivo:** recuperar o controle do projeto. Nenhuma funcionalidade de negócio.

**Definição de pronto:** você consegue desenhar o diagrama ER de memória, em papel.

### 28/07 — Sprint 0 — Dia 1

**Entreguei:**

**Travei em:**

**Decisão de projeto tomada hoje (e por quê):**

**Ainda não entendo:**

---

### 29/07 — Sprint 0 — Dia 2

**Entreguei:**

**Travei em:**

**Decisão de projeto tomada hoje (e por quê):**

**Ainda não entendo:**

---

### 30/07 — Sprint 0 — Dia 3

**Entreguei:**

**Travei em:**

**Decisão de projeto tomada hoje (e por quê):**

**Ainda não entendo:**

---

## Sprint 1 — Núcleo de segurança (31/07 a 05/08)

**Objetivo:** autenticação, RBAC, Global Scope e Row Level Security.
**É a sprint mais importante do TCC** — aqui mora a contribuição técnica declarada.

**Definição de pronto:** o teste de defesa em profundidade passa — com o Global Scope
desabilitado, a RLS ainda barra o acesso entre organizações.

### Retrospectiva da sprint

**O que funcionou:**

**O que eu faria diferente:**

**Para o artigo (Seção 5):**

---

## Sprint 2 — Escuta contínua (06 a 11/08)

**Objetivo:** check-in diário (RF01), motor de XP (RF04), feedback anônimo (RF02).

**Definição de pronto:** o teste da virada de meia-noite passa e o teste de não persistência
do remetente passa.

### Retrospectiva da sprint

**O que funcionou:**

**O que eu faria diferente:**

**Para o artigo (Seção 5):**

---

## Sprint 3 — Desenvolvimento individual (12 a 17/08)

**Objetivo:** PPC (RF05), portfólio de competências (RF06), busca por competência (RF07).

**Definição de pronto:** a busca localiza colaborador de outra filial, e a exceção ao
isolamento está documentada e testada.

### Retrospectiva da sprint

**O que funcionou:**

**O que eu faria diferente:**

**Para o artigo (Seção 5):**

---

## Sprint 4 — Painel e congelamento (18 a 21/08)

**Objetivo:** painel agregado (RF08), polimento, seed de demonstração, apêndices prontos.

**Definição de pronto:** nenhum indicador expõe humor individual; grupos com menos de 5
respondentes não são exibidos.

> 🔒 **CODE FREEZE — 21/08 às 23h.** A partir daqui, só correção de bug crítico.
> Testar um alvo móvel invalida o teste de usabilidade.

### Retrospectiva da sprint

**O que funcionou:**

**O que eu faria diferente:**

**Para o artigo (Seção 5):**

---

## Sprint 5 — Avaliação (22 a 25/08)

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

### Confronto com os critérios de aceitação (Seção 4.5)

| Critério | Meta | Resultado | Atendido? |
|---|---|---|---|
| (a) Escore SUS médio | ≥ 68 | | |
| (b) Tarefas essenciais concluídas | 100% por ≥ 70% dos participantes | | |
| (c) Utilidade percebida | ≥ 70% dos participantes | | |

> Resultado abaixo do critério **não invalida a pesquisa**. É analisado como evidência de
> revisão de requisitos ou de design, coerente com o caráter iterativo da DSR — como o
> artigo já estabelece. Um trabalho que reporta 61 e explica honestamente as causas é mais
> forte que um que reporta 84 sem discussão.

### Padrões observados nas sessões

**Onde mais participantes travaram:**

**Comentários recorrentes:**

**Bugs encontrados (e não corrigidos, por estar em code freeze):**

---

## Fechamento (26 a 28/08)

- [ ] Seção 5 — Desenvolvimento do artefato
- [ ] Seção 6 — Resultados e discussão
- [ ] Seção 7 — Considerações finais reescritas
- [ ] Artigo inteiro convertido para o pretérito
- [ ] Bloqueadores B1 a B5 corrigidos (ver `docs/PLANO-MESTRE-30-DIAS.md`)
- [ ] Revisões do ranking aplicadas (ver `docs/REVISOES-ARTIGO-ranking.md`)
- [ ] Apêndices A a E montados
- [ ] Diagramas exportados em PNG/SVG
- [ ] Slides da banca
- [ ] Vídeo da demo gravado (plano B)
- [ ] Dois ensaios cronometrados, com perguntas hostis
