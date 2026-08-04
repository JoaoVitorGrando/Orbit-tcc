# APÊNDICE B — Roteiro de Tarefas Guiadas

**Avaliação de usabilidade do Orbit RH**
Participantes: 8 · Duração prevista: 40 min por sessão · Período: 22 a 24/08/2026

---

## B.1 Preparação do avaliador (antes de cada sessão)

1. Restaurar o estado inicial do sistema: `php artisan migrate:fresh --seed`.
   **Todos os participantes devem encontrar exatamente o mesmo estado.**
2. Abrir o navegador na tela de login, em janela limpa (sem sessão anterior).
3. Ter em mãos: este roteiro, a ficha de observação (B.5), o TCLE impresso em duas vias
   e o questionário SUS.
4. Cronômetro iniciado no começo de cada tarefa.

---

## B.2 Script de abertura (ler ao participante, sem alterar)

> Obrigado por participar. Vou pedir que você use um sistema de gestão de pessoas
> chamado Orbit RH e realize algumas tarefas.
>
> É importante deixar claro: **não é você que está sendo avaliado, é o sistema.** Se
> algo ficar confuso ou você não conseguir concluir, o problema é do sistema, não seu.
> Essa informação é justamente o que eu preciso coletar.
>
> Durante as tarefas eu não vou poder ajudar, mesmo que você fique em dúvida. Não é
> falta de atenção da minha parte — é o método. Se você travar completamente, diga "não
> consigo" e passamos para a próxima.
>
> Se puder, vá pensando em voz alta: o que está procurando, o que espera que aconteça.
> Ao final, farei algumas perguntas rápidas.
>
> Você pode desistir a qualquer momento, sem precisar justificar e sem nenhum prejuízo.
> Alguma dúvida antes de começar?

**Depois da leitura:** colher a assinatura do TCLE (Apêndice D) nas duas vias.

---

## B.3 Conduta durante a sessão

| Situação | O que fazer |
|---|---|
| Participante pergunta "é aqui?" | "O que você acha?" — devolva sem confirmar. |
| Participante trava e pede ajuda | "Prefiro não responder agora, para não influenciar. Tente do jeito que fizer sentido para você." |
| Participante desiste da tarefa | Registrar como **não concluída**. Seguir para a próxima. |
| Erro do sistema (bug) | Registrar na ficha, anotar a tarefa afetada e **não corrigir**. Se impedir a continuidade, marcar a tarefa como inválida. |
| Participante em silêncio prolongado | "Pode me contar o que está pensando?" — só isso, nunca uma dica. |

Tempo máximo por tarefa: **3 minutos.** Ultrapassou, registre como não concluída e siga.

---

## B.4 Tarefas por papel

Distribuição sugerida dos 8 participantes: **3 Colaboradores · 3 Gestores · 2
Administradores.** O fluxo do colaborador é o mais utilizado e o que mais precisa de
evidência.

As credenciais são fornecidas na própria tela de login (bloco "Ver credenciais de
demonstração"), o que faz parte do desenho do protótipo.

---

### Papel: COLABORADOR

| # | Tarefa (ler ao participante) | RF | Critério de conclusão bem-sucedida | Essencial |
|---|---|---|---|---|
| C1 | "Entre no sistema com o perfil de colaborador." | RF11 | Chegou à tela inicial do colaborador sem auxílio. | ✅ |
| C2 | "Registre como você está se sentindo hoje." | RF01 | Check-in registrado; confirmação visível na tela. | ✅ |
| C3 | "Tente registrar seu humor novamente." | RF01 | Percebeu e verbalizou que já havia registrado hoje. | ⬜ |
| C4 | "Descubra quantos pontos você tem e há quantos dias seguidos vem registrando." | RF04 | Localizou XP e ofensiva e informou os dois valores corretamente. | ✅ |
| C5 | "Veja em que etapa está o seu plano de carreira." | RF05 | Localizou o PPC e identificou a etapa atual. | ✅ |
| C6 | "Adicione ao seu perfil uma habilidade que você tenha — pode ser qualquer uma." | RF06 | Competência salva e visível no portfólio. | ✅ |
| C7 | "Envie um comentário sobre o ambiente de trabalho, sem que ninguém saiba que foi você." | RF02 | Feedback enviado **com a opção anônima ativada**. | ✅ |

> **C7 é a tarefa mais informativa do roteiro.** Se o participante enviar sem marcar
> anônimo, significa que a opção não está evidente — achado relevante, mesmo (ou
> principalmente) sendo um resultado negativo. Registre exatamente o que ele fez.

---

### Papel: GESTOR

| # | Tarefa (ler ao participante) | RF | Critério de conclusão bem-sucedida | Essencial |
|---|---|---|---|---|
| G1 | "Entre no sistema com o perfil de gestor." | RF11 | Chegou à tela inicial do gestor. | ✅ |
| G2 | "Descubra como está o clima da sua unidade nos últimos dias." | RF08 | Localizou o painel e interpretou ao menos um indicador em voz alta. | ✅ |
| G3 | "Sua equipe precisa de alguém que saiba Excel. Descubra se já existe alguém na empresa com essa competência." | RF07 | Executou a busca por competência e localizou ao menos um resultado. | ✅ |
| G4 | "Veja se há algum feedback pendente da sua equipe." | RF02 | Acessou a lista de feedbacks. | ✅ |
| G5 | "Você consegue descobrir qual foi o humor registrado hoje por um colaborador específico?" | RNF04 | **Sucesso = concluir que não é possível.** Verbalizou que os dados são apresentados de forma agregada. | ⬜ |

> **G5 não é uma tarefa comum — é uma verificação de privacidade por design.** O
> resultado esperado é o participante não encontrar o dado e perceber por quê. Se ele
> encontrar humor individual identificado, você tem uma violação do RNF04 para tratar
> antes da defesa. Registre a fala exata.

---

### Papel: ADMINISTRADOR

| # | Tarefa (ler ao participante) | RF | Critério de conclusão bem-sucedida | Essencial |
|---|---|---|---|---|
| A1 | "Entre no sistema com o perfil de administrador." | RF11 | Chegou ao painel administrativo. | ✅ |
| A2 | "Cadastre um novo colaborador, vinculando-o a uma filial." | RF11 | Usuário criado com papel e filial definidos. | ✅ |
| A3 | "Compare o clima entre as filiais da empresa." | RF08 | Localizou os indicadores por unidade e comparou ao menos duas. | ✅ |
| A4 | "Encontre alguém na empresa, de qualquer filial, que saiba usar Excel." | RF07 | Busca executada abrangendo mais de uma filial. | ✅ |
| A5 | "Veja quais colaboradores vêm participando mais das atividades." | RF12 | Localizou a ordenação por participação. | ⬜ |

---

## B.5 Ficha de observação (uma por participante)

```
Participante nº: ____    Papel simulado: ( ) Colaborador ( ) Gestor ( ) Administrador
Data: ____/____/2026     Início: ____:____    Término: ____:____

┌──────┬──────────┬───────────┬──────────────┬────────────────────────────────────┐
│Tarefa│  Tempo   │ Concluiu? │ Pediu ajuda? │ Observações (hesitações, caminhos, │
│      │          │  S/N/Inv  │    S/N       │  falas espontâneas, erros)         │
├──────┼──────────┼───────────┼──────────────┼────────────────────────────────────┤
│      │          │           │              │                                    │
│      │          │           │              │                                    │
│      │          │           │              │                                    │
│      │          │           │              │                                    │
│      │          │           │              │                                    │
│      │          │           │              │                                    │
│      │          │           │              │                                    │
└──────┴──────────┴───────────┴──────────────┴────────────────────────────────────┘

Inv = tarefa invalidada por falha do sistema

Bugs observados: ________________________________________________________________
_________________________________________________________________________________

Comentários espontâneos relevantes: _____________________________________________
_________________________________________________________________________________
```

---

## B.6 Cobertura dos requisitos

Verificação de que o roteiro cobre os requisitos essenciais do Quadro 4:

| RF | Descrição | Coberto por |
|---|---|---|
| RF01 | Check-in diário de humor | C2, C3 |
| RF02 | Feedback com opção anônima | C7, G4 |
| RF04 | XP e ofensivas | C4 |
| RF05 | Plano de Progressão de Carreira | C5 |
| RF06 | Portfólio de competências | C6 |
| RF07 | Busca por competência | G3, A4 |
| RF08 | Painel de indicadores agregados | G2, A3 |
| RF11 | Controle de acesso por papel | C1, G1, A1, A2 |
| RF12 | Ranking (complementar) | A5 |
| RNF04 | Agregação / privacidade | G5 |

**Todos os oito requisitos essenciais estão cobertos por ao menos uma tarefa
classificada como essencial** — condição necessária para verificar o critério de
aceitação (b) da seção 4.5.

---

## B.7 Encerramento da sessão

1. Aplicar o questionário SUS (Apêndice C). **Sem comentar as tarefas antes** — qualquer
   observação sua nesse intervalo contamina a resposta.
2. Aplicar as três perguntas da entrevista (Apêndice C, seção C.4).
3. Agradecer e informar que os resultados serão reportados de forma agregada e anônima.
