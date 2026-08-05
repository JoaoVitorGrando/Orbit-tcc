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
