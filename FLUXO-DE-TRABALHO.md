# Processo de Desenvolvimento — Orbit RH

Como o trabalho é organizado ao longo das sprints.

---

## 1. Fonte única de verdade

O contexto do projeto vive versionado, junto do código, em vez de depender de memória
ou de ferramentas externas:

| Documento | Papel | Quando é consultado |
|---|---|---|
| `CLAUDE.md` | Regras de arquitetura, stack, escopo, convenções | No início de cada sessão de trabalho |
| `docs/ESTUDO-DO-PRODUTO.md` | Especificação funcional e racional de produto | Sob demanda |
| `docs/PLANO-MESTRE-30-DIAS.md` | Cronograma, riscos e pendências | Início de cada sprint |
| `DIARIO.md` | Decisões técnicas e questões em aberto por dia | Diariamente |
| Histórico do Git | O que foi feito, quando e por quê | Continuamente |

Vantagens de manter o contexto no próprio repositório em vez de em uma ferramenta
externa de anotações:

- **Versionado.** Toda mudança de decisão aparece no diff, com data e motivo.
- **Colocado junto ao código.** A documentação de uma regra fica a um diretório de
  distância do código que a implementa — não desatualiza silenciosamente.
- **É parte do entregável.** A documentação técnica gerada durante o desenvolvimento
  vira anexo do relatório final do projeto.

Se, em algum momento, uma informação de contexto estiver faltando, a correção é
acrescentá-la ao documento correspondente — não criar um sistema paralelo de memória.

---

## 2. Ciclo de uma funcionalidade

Fluxo repetido a cada requisito implementado, em cinco etapas.

### Etapa 1 — Formulação da hipótese

Antes de iniciar a implementação, registrar em duas frases o comportamento esperado e a
camada onde a lógica deve residir, com justificativa.

> *"RF01 exige unicidade de check-in por dia. A validação deve ficar em uma regra
> customizada, e não no controller, para permitir reuso no seeder."*

O objetivo não é acertar de primeira — é ter uma posição registrada antes da
implementação, para que qualquer ajuste seja uma correção rastreável, não uma
substituição silenciosa.

### Etapa 2 — Planejamento antes da implementação

Para qualquer mudança não trivial, o plano precede o código: arquivos afetados, camada
envolvida, alternativas razoáveis e o trade-off de cada uma. A implementação só começa
após esse desenho estar claro.

### Etapa 3 — Implementação incremental

Mudanças pequenas, cada uma cabendo em uma única leitura de revisão. Cada incremento é
testável isoladamente.

### Etapa 4 — Verificação de compreensão

Ao final de cada incremento, o comportamento implementado é descrito de memória, sem
consultar o código. Divergência nessa descrição é sinal de que a implementação precisa
ser revisada antes de seguir.

### Etapa 5 — Teste e commit

Todo requisito é entregue com teste automatizado. Commit vinculado ao requisito:

```bash
git commit -m "RF01: valida unicidade do check-in diário na virada de data"
```

O histórico de commits por requisito serve como evidência do processo iterativo
adotado.

---

## 3. Ciclo diário

| Momento | Atividade | Duração aproximada |
|---|---|---|
| Início | Revisar a entrada anterior do diário técnico, definir o requisito do dia | 10 min |
| Período principal | Ciclo da seção 2, um ou dois requisitos | 7h |
| Fechamento | Rodar a suíte de testes, commitar, registrar entrada no diário | 30 min |

A entrada diária no `DIARIO.md` não é opcional. Três campos mínimos: o que foi
entregue, onde houve impedimento, o que permanece como questão em aberto. O terceiro
campo vazio por dois dias seguidos indica que uma decisão foi aplicada sem compreensão
suficiente — sinal para retomar o item antes de avançar.

---

## 4. Ciclo de sprint

**Abertura (20 min):** revisar o objetivo da sprint no plano mestre, listar os
requisitos, definir critério de pronto.

**Fechamento (40 min):**

1. Rodar toda a suíte de testes.
2. Preencher a retrospectiva no `DIARIO.md`, incluindo o registro para a documentação
   final.
3. **Revisão técnica de encerramento.** Simular perguntas de avaliação sobre as
   decisões de arquitetura da sprint, respondê-las por escrito. Itens não respondidos
   corretamente viram estudo dirigido.
4. Item que não coube desce para "trabalho futuro" na documentação — não é empurrado
   para a sprint seguinte. Empurrar item é a causa mais comum de estouro de cronograma
   em projetos de escopo fixo.

---

## 5. Ambiente de trabalho

| Frente | Ambiente | Motivo |
|---|---|---|
| Código, migrations, testes, depuração | Terminal de desenvolvimento, dentro do repositório | Execução de testes e commits no contexto do projeto |
| Documentação, apêndices, análise de dados, apresentação | Ambiente de redação e planilhas | Mais adequado para documento e análise |
| Acompanhamento de sprint | Quadro Kanban (seção 6) | Visual, e evidência de processo |

---

## 6. Quadro de sprints

Um quadro serve a dois propósitos: disciplina de acompanhamento e evidência do
processo iterativo declarado na metodologia do projeto.

**Regra:** um único quadro, que não substitui o histórico do Git. Duas fontes de
verdade para a mesma informação é como se perde controle sobre o estado real do
projeto.

### Estrutura

Listas: `Backlog` → `Sprint atual` → `Fazendo` → `Em teste` → `Pronto`

Um cartão por requisito, com o código no título (`RF01 — Check-in diário de humor`) e,
na descrição: critério de pronto, testes exigidos, seção da documentação afetada.

Cartões fixos, que não se movem: os cenários de teste obrigatórios, os bloqueadores de
redação, o marco de code freeze.

### Manutenção

O quadro é atualizado ao final de cada sprint, a partir do que foi efetivamente
commitado — nunca antecipado. Um quadro atualizado por expectativa, e não por entrega
real, deixa de ser confiável.

---

## 7. Regras que não mudam

1. Nenhuma linha entra no repositório sem que quem a propôs consiga explicá-la em voz
   alta.
2. Toda tabela de negócio nasce com `organization_id`, na primeira migration.
3. Feedback anônimo não grava o remetente. Nunca.
4. Ranking ordena por XP. Nunca por humor. Visível só ao Administrador.
5. Nada de recompensas ou resgates — fora de escopo.
6. RF03, RF09, RF10 e RF12 são complementares. Só depois dos essenciais.
7. Code freeze em 22/08 às 23h. Depois disso, só correção de bug crítico.
8. Item que não coube na sprint vira "trabalho futuro", não dívida técnica silenciosa.

Todas estão detalhadas em `CLAUDE.md`.

---

## 8. Tratamento de bloqueios

**Impasse com mais de uma hora no mesmo problema.** Interromper tentativas de variação
pontual. Descrever o problema do zero, com a mensagem de erro completa e o que já foi
tentado. Contexto bem registrado reduz o tempo de diagnóstico mais do que tentativa e
erro.

**Row Level Security não coopera.** É a parte mais técnica do projeto. Se ultrapassar
um dia e meio, entregar com Global Scope e testes provando isolamento, e reposicionar a
RLS como camada parcialmente implementada. Tentar a implementação completa primeiro,
porque é o diferencial técnico do trabalho.

**Trecho de código já commitado não está claro.** Revisitar, reescrever o comentário de
cabeçalho com as próprias palavras. Código não compreendido é dívida técnica que se
manifesta no momento da avaliação final.

**Sprint estourou.** Cortar escopo, não qualidade. Um requisito a menos, bem feito e
testado, é preferível a dois pela metade — e é o único dos dois que é defensável.
