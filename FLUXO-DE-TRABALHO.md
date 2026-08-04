# Como vamos trabalhar — Orbit RH

Fluxo de trabalho para os 30 dias. Leia uma vez, consulte depois.

---

## 1. A ideia central: o repositório é o cérebro

Você perguntou se vale usar uma ferramenta externa de memória. A resposta curta é **não
precisa — você já tem uma, e ela é melhor.**

O que faz um "cérebro" funcionar é o contexto certo estar disponível na hora certa. No seu
caso, isso já acontece:

| Arquivo | Papel | Quando é lido |
|---|---|---|
| `CLAUDE.md` | Regras, stack, escopo, decisões travadas | **Automaticamente, toda sessão** |
| `docs/ESTUDO-DO-PRODUTO.md` | O produto inteiro explicado | Quando você ou eu precisamos |
| `docs/PLANO-MESTRE-30-DIAS.md` | Cronograma e pendências do artigo | Início de cada sprint |
| `DIARIO.md` | O que aconteceu e o que você não entendeu | Todo dia |
| `git log` | O que foi feito, quando e por quê | Sempre |

Três vantagens que uma ferramenta externa não tem:

- **Versionado.** Você vê o que mudou, quando e por quê. Memória externa não tem `diff`.
- **Junto do código.** O contexto está a um diretório de distância do arquivo que ele
  descreve. Não desatualiza sozinho.
- **É o entregável.** O `docs/` vira apêndice do artigo. Um grafo de conhecimento externo
  não vira nada.

E há uma razão mais importante. **O problema que estamos consertando é que você delegou a
compreensão para uma ferramenta.** Acrescentar um sistema que lembra por você trabalha
contra o objetivo. O `DIARIO.md` existe justamente para forçar o inverso: escrever o que
você não entendeu obriga a admitir o que não entendeu.

> Se em algum momento você sentir falta de memória entre sessões, o sintoma real é que
> falta algo no `CLAUDE.md`. A correção é escrever lá, não instalar um sistema.

---

## 2. O ciclo de uma funcionalidade

Este é o fluxo que se repete dezenas de vezes ao longo do mês. Cinco passos.

### Passo 1 — Você formula a hipótese (2 minutos, sem IA)

Antes de me pedir qualquer coisa, escreva duas frases:

> *"Vou implementar o RF01. Acho que a regra de unicidade deve ficar numa validation rule
> customizada, e não no controller, porque preciso reusar no seeder."*

Não importa se estiver errado. **Importa que exista um palpite seu para ser corrigido.**
Quem só recebe resposta não aprende; quem erra um palpite e é corrigido, sim.

### Passo 2 — Planejamento antes do código

No Claude Code, aperte `Shift+Tab` duas vezes para entrar em **plan mode**. Nesse modo eu
não toco em arquivo nenhum — apresento o plano e espero sua aprovação.

Peça assim:

> *"Antes de escrever código, me mostre onde isso vai (quais arquivos, qual camada) e
> quais são as 2 alternativas razoáveis. Eu decido qual seguir."*

**Este passo é o antídoto direto para o seu problema.** Você entende o código porque
entendeu o plano antes de ele existir.

### Passo 3 — Implementação incremental

Nada de blocos gigantes. Pedaços que cabem em uma leitura. O `CLAUDE.md` já me instrui a
trabalhar assim e a fazer uma pergunta de verificação ao final.

### Passo 4 — Você reescreve o que o código faz

Feche minha resposta. Sem olhar, escreva de memória o que aquele código faz. Se travar,
não commite: pergunte de novo.

Esse passo parece perda de tempo e é o mais valioso do fluxo.

### Passo 5 — Teste e commit

Todo requisito entrega com teste. Commit vinculado ao requisito:

```bash
git commit -m "RF01: valida unicidade do check-in diário na virada de data"
```

No dia 26 você roda `git log --oneline` e tem a evidência do seu Scrum pronta.

---

## 3. O dia

| Horário | O quê | Duração |
|---|---|---|
| Início | Abrir o `DIARIO.md`, reler a entrada de ontem, escolher o requisito do dia | 10 min |
| Manhã | Ciclo do item 2, uma ou duas funcionalidades | 4h |
| Tarde | Continuação + testes | 3h |
| Fim | Rodar `php artisan test`, commitar, escrever a entrada do diário | 30 min |

**A entrada do diário não é opcional.** Três campos: o que entreguei, onde travei, o que
ainda não entendo. O terceiro em branco por dois dias seguidos significa que você voltou a
aceitar código sem ler.

---

## 4. A sprint

**Abertura (segunda-feira, 20 min).** Reler o objetivo da sprint no `PLANO-MESTRE`, listar
os requisitos, definir o critério de pronto.

**Fechamento (última noite, 40 min):**

1. Rodar toda a suíte de testes.
2. Preencher a retrospectiva no `DIARIO.md` — inclusive o campo *"Para o artigo (Seção 5)"*.
3. **A sabatina.** Peça:

> *"Você é a banca do meu TCC. Leia o código desta sprint e me faça 8 perguntas técnicas
> difíceis sobre as decisões de arquitetura. Não me dê as respostas."*

Responda por escrito. As que errar viram estudo do fim de semana. Isso vale mais que
qualquer revisão de slides — é literalmente o ensaio da defesa, cinco vezes.

4. Se algum item não coube, ele **desce para "evolução futura" no artigo**. Não sobe para
   a sprint seguinte. Empurrar item é o que destrói cronogramas.

---

## 5. Divisão de ferramentas

| Frente | Onde | Por quê |
|---|---|---|
| Código, migrations, testes, debug | **Claude Code no terminal**, dentro do repo | Roda testes, commita, lê o `CLAUDE.md` sozinho |
| Artigo, apêndices, análise do SUS, slides | **Cowork** (onde estamos agora) | Melhor para documento e planilha |
| Acompanhamento de sprint | Quadro (ver item 6) | Visual, e vira evidência de processo |

---

## 6. Quadro de sprints

Um quadro serve a dois propósitos: sua disciplina, e **evidência do processo de Scrum que a
seção 4.4 do artigo afirma ter adotado**. O segundo é o que justifica o esforço.

**A regra de ouro: um só quadro, e ele não substitui o `git log`.** Duas fontes de verdade
para a mesma informação é como se perde controle.

### Estrutura sugerida

Listas: `Backlog` → `Sprint atual` → `Fazendo` → `Em teste` → `Pronto`

Um cartão por requisito, com o código no título (`RF01 — Check-in diário de humor`) e no
verso: critério de pronto, testes exigidos, seção do artigo afetada.

Cartões fixos de contexto, que não se movem: **os 7 cenários de teste obrigatórios**, os
**bloqueadores do artigo (B1–B5)**, e o **code freeze de 21/08**.

### O risco, e como matá-lo

Quadro de projeto solo morre por volta do dia 10 — você para de mover cartão e ele passa a
mentir sobre o estado real. **A mitigação é eu manter o quadro atualizado**, no fechamento
de cada sprint, a partir do que realmente foi commitado. Aí ele nunca mente.

---

## 7. As regras que não mudam

1. **Nenhuma linha entra no repositório sem que você consiga explicá-la em voz alta.**
2. Toda tabela de negócio nasce com `organization_id`. Na primeira migration.
3. Feedback anônimo não grava o remetente. Nunca.
4. Ranking ordena por XP. Nunca por humor. Visível só ao Administrador.
5. Nada de recompensas ou resgates — fora de escopo.
6. RF03, RF09, RF10 e RF12 são complementares. Só depois dos essenciais.
7. Code freeze em 21/08 às 23h. Depois disso, só bug crítico.
8. Item que não coube na sprint vira "trabalho futuro", não dívida.

Todas estão no `CLAUDE.md`, então eu as aplico sozinho. Se eu escorregar, me cobre.

---

## 8. Quando as coisas derem errado

**Travou mais de 1 hora no mesmo erro.** Pare de tentar variações. Descreva o problema do
zero, com a mensagem de erro completa e o que você já tentou. Vinte minutos de contexto bem
escrito valem mais que três horas de tentativa.

**A RLS não coopera.** É a parte mais técnica do projeto. Se passar de um dia e meio,
entregue com Global Scope + testes provando isolamento e reposicione a RLS como camada
parcial. Mas tente de verdade primeiro — é seu diferencial.

**Você não entende um código que já commitou.** Volte nele. Peça explicação linha a linha e
reescreva o comentário do topo com suas palavras. Código não compreendido é dívida que
vence no dia da banca.

**A sprint estourou.** Corte escopo, não qualidade. Um requisito a menos, bem feito e
testado, é melhor que dois pela metade — e é defensável na banca. O contrário, não.
