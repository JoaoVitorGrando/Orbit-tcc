# APÊNDICE C — Questionário System Usability Scale (SUS) e Entrevista

Instrumento de avaliação de usabilidade do Orbit RH · Brooke (1996)

---

## C.1 Instruções ao participante (ler antes de entregar)

> Agora você vai responder a dez afirmações sobre o sistema que acabou de usar.
>
> Marque a opção que melhor representa sua impressão, de 1 (discordo totalmente) a 5
> (concordo totalmente). **Responda pela primeira impressão, sem pensar muito.** Não
> existe resposta certa.
>
> Se alguma afirmação não fizer sentido para você, marque 3.

O avaliador **não comenta as tarefas** enquanto o participante responde. Qualquer
observação nesse intervalo contamina a resposta.

---

## C.2 Questionário

Participante nº: ______   Papel simulado: _______________   Data: ____/____/2026

| # | Afirmação | 1 | 2 | 3 | 4 | 5 |
|---|---|:-:|:-:|:-:|:-:|:-:|
| 1 | Eu acho que gostaria de usar esse sistema com frequência. | ☐ | ☐ | ☐ | ☐ | ☐ |
| 2 | Eu achei o sistema desnecessariamente complexo. | ☐ | ☐ | ☐ | ☐ | ☐ |
| 3 | Eu achei o sistema fácil de usar. | ☐ | ☐ | ☐ | ☐ | ☐ |
| 4 | Eu acho que precisaria da ajuda de uma pessoa com conhecimentos técnicos para conseguir usar o sistema. | ☐ | ☐ | ☐ | ☐ | ☐ |
| 5 | Eu achei que as várias funções do sistema estavam bem integradas. | ☐ | ☐ | ☐ | ☐ | ☐ |
| 6 | Eu achei que havia muita inconsistência no sistema. | ☐ | ☐ | ☐ | ☐ | ☐ |
| 7 | Eu imagino que a maioria das pessoas aprenderia a usar esse sistema rapidamente. | ☐ | ☐ | ☐ | ☐ | ☐ |
| 8 | Eu achei o sistema complicado de usar. | ☐ | ☐ | ☐ | ☐ | ☐ |
| 9 | Eu me senti confiante ao usar o sistema. | ☐ | ☐ | ☐ | ☐ | ☐ |
| 10 | Eu precisei aprender muitas coisas antes de conseguir lidar com o sistema. | ☐ | ☐ | ☐ | ☐ | ☐ |

**1 = discordo totalmente · 5 = concordo totalmente**

> ⚠️ **Não altere a ordem dos itens.** A alternância entre afirmações positivas (ímpares)
> e negativas (pares) é parte do desenho do instrumento — ela reduz o viés de
> aquiescência. Reordenar invalida a comparação com a referência de 68 pontos.

---

## C.3 Cálculo do escore

O SUS produz um valor de 0 a 100. **Não é porcentagem** — é um escore em escala própria.
Confundir os dois é o erro mais comum em TCCs que aplicam o instrumento; evite escrever
"68%" no artigo.

**Passo 1 — normalizar cada item (resultado de 0 a 4):**

- **Itens ímpares (1, 3, 5, 7, 9)** — afirmações positivas: `contribuição = resposta − 1`
- **Itens pares (2, 4, 6, 8, 10)** — afirmações negativas: `contribuição = 5 − resposta`

**Passo 2 — somar as dez contribuições** (resultado de 0 a 40).

**Passo 3 — multiplicar por 2,5** → escore SUS do participante (0 a 100).

**Passo 4 — calcular a média dos 8 participantes** e o desvio-padrão.

### Exemplo conferido

Respostas: `4, 2, 5, 1, 4, 2, 5, 2, 4, 2`

| Item | Resposta | Regra | Contribuição |
|---|---|---|---|
| 1 (ímpar) | 4 | 4 − 1 | 3 |
| 2 (par) | 2 | 5 − 2 | 3 |
| 3 (ímpar) | 5 | 5 − 1 | 4 |
| 4 (par) | 1 | 5 − 1 | 4 |
| 5 (ímpar) | 4 | 4 − 1 | 3 |
| 6 (par) | 2 | 5 − 2 | 3 |
| 7 (ímpar) | 5 | 5 − 1 | 4 |
| 8 (par) | 2 | 5 − 2 | 3 |
| 9 (ímpar) | 4 | 4 − 1 | 3 |
| 10 (par) | 2 | 5 − 2 | 3 |
| | | **Soma** | **33** |

Escore = 33 × 2,5 = **82,5**

### Fórmula para planilha

Com as respostas nas colunas `B` a `K` (itens 1 a 10), linha 2:

```
=((B2-1)+(5-C2)+(D2-1)+(5-E2)+(F2-1)+(5-G2)+(H2-1)+(5-I2)+(J2-1)+(5-K2))*2.5
```

---

## C.4 Faixas de interpretação

| Escore | Interpretação |
|---|---|
| ≥ 85 | Usabilidade excelente |
| 72 – 84 | Boa |
| **68** | **Média — referência de aceitabilidade (RNF05)** |
| 51 – 67 | Abaixo da média; requer revisão |
| < 51 | Usabilidade insatisfatória |

O valor de referência 68 corresponde à média histórica de aplicações do instrumento e é
o critério (a) da seção 4.5.

**Se o resultado ficar abaixo de 68, isso não invalida a pesquisa.** Seu artigo já
estabelece que resultados abaixo do critério são analisados como evidência de revisão de
requisitos ou de design, postura coerente com o caráter iterativo da DSR. Um trabalho que
reporta 61 e explica honestamente as causas é mais forte do que um que reporta 84 sem
discussão.

---

## C.5 Entrevista de percepção (após o SUS)

Três perguntas abertas, gravadas em áudio ou anotadas. Servem à triangulação e ao
critério de aceitação (c) — utilidade percebida por ao menos 70% dos participantes.

Participante nº: ______

**1. Você usaria um sistema como esse no seu trabalho? Por quê?**

_Detecta utilidade percebida. Para o critério (c), classifique a resposta como
manifestação de utilidade quando o participante indicar benefício concreto — para si, para
a equipe ou para a gestão. Respostas neutras ou evasivas não contam._

<br>

**2. O que mais te incomodou ou confundiu durante o uso?**

_Alimenta a análise temática e a lista de melhorias da Seção 6._

<br>

**3. Você se sentiria à vontade para registrar seu humor e enviar feedbacks nesse sistema,
sabendo que ele é da sua empresa?**

_Pergunta central do trabalho. Mede confiança percebida no anonimato — a conformidade
técnica com a LGPD não vale nada se o colaborador não acreditar nela. Se as respostas
forem majoritariamente negativas, você tem um achado forte para discutir, e ele não é um
fracasso: é evidência de que segurança psicológica exige mais do que garantia técnica._

---

## C.6 Notas de redação para o artigo

- **Citação do instrumento:** BROOKE, John. SUS: a quick and dirty usability scale, 1996.
  Já consta das suas referências.
- **Tradução:** se você usar uma versão em português publicada por terceiros, cite a
  fonte. Se adaptar por conta própria, declare no texto: *"tradução livre do autor, com
  manutenção da estrutura original de dez itens em escala Likert de cinco pontos"*. Não
  deixe a origem implícita — a banca pode perguntar.
- **Reportar sempre:** média, desvio-padrão, mínimo, máximo e n. Só a média esconde a
  dispersão, que com n = 8 é justamente a informação mais relevante.
- **Nunca escrever "SUS de 78%".** O escore não é percentual.
