# Orbit RH — Especificação de Produto

Documento de referência do produto, escrito para quem nunca teve contato com o sistema.
Consolida a fundamentação teórica do projeto de pesquisa e as decisões de arquitetura
adotadas na implementação.

---

## 1. O que é, em um parágrafo

O Orbit RH é uma plataforma web que permite a uma pequena ou média empresa **saber
continuamente como seus colaboradores estão se sentindo**, sem depender de pesquisas
anuais de clima nem de software corporativo caro. Ele faz isso por três caminhos que se
alimentam: o colaborador registra o próprio humor em segundos, todo dia; ganha pontos e
avança em um plano de carreira por manter esse hábito e por se desenvolver; e a liderança
enxerga, em um painel, indicadores agregados do clima por unidade — sem nunca ver o humor
de uma pessoa específica.

Princípio de projeto: **escutar continuamente sem vigiar individualmente.**

---

## 2. Por que ele existe — a cadeia do problema

O raciocínio do trabalho encadeia cinco fatos, nesta ordem, e juntos formam a
justificativa completa do projeto.

**1. O RH virou função estratégica.** Deixou de ser admissão e folha para responder por
desempenho, retenção e clima (Chiavenato, 2020; Ulrich, 2019).

**2. O engajamento está caindo e custa caro.** A Gallup reporta que apenas **20% dos
colaboradores globais estavam engajados em 2025**, o menor nível desde 2020, com perda
estimada de **US$ 10 trilhões** em produtividade.

**3. No Brasil, o sintoma é o turnover.** O país tem uma das maiores taxas do mundo,
próxima de **51% ao ano**, custando mais de **R$ 600 bilhões**. E — este é o dado que
sustenta a solução — **42% de quem pediu demissão diz que a empresa poderia ter feito algo
para retê-lo** (Gallup, 2024). Ou seja: boa parte da rotatividade é evitável, e o que
falha é escuta.

**4. Quem mais sofre é quem menos tem ferramenta.** As PMEs são **mais de 99% das empresas
brasileiras e ~30% do PIB**, mas gerenciam pessoas com planilha e conversa informal, por
custo de licença, complexidade de implantação e ausência de TI própria.

**5. E agora virou obrigação legal.** A atualização da **NR-1** incluiu riscos
psicossociais entre os riscos que toda empresa deve identificar, avaliar e gerenciar.
Monitorar o clima deixou de ser boa prática e passou a ser exigência — para a qual as PMEs
não têm instrumento.

Some a isso a **LGPD**: coletar percepção sobre ambiente de trabalho é tratar dado
sensível na prática, o que obriga o sistema a proteger quem fala.

> **A pergunta de pesquisa nasce dessa soma:** como apoiar a gestão de pessoas em PMEs
> brasileiras com uma plataforma que seja acessível ao bolso e à operação do segmento,
> eficaz em captar percepção continuamente, e aderente à NR-1 e à LGPD?

---

## 3. Quem usa — os três papéis

O sistema inteiro se organiza em torno de três papéis. Tudo o que uma pessoa vê depende
de qual deles ela ocupa.

| Papel | Quem é | O que faz | O que **nunca** vê |
|---|---|---|---|
| **Colaborador** | Funcionário comum | Registra humor, envia feedback, acompanha seus pontos e seu plano de carreira, mantém seu portfólio de competências | Dados de outras pessoas. Nem sua posição relativa. |
| **Gestor** | Liderança de uma filial | Vê os indicadores agregados **da sua unidade**, busca colaboradores por competência, lê e responde feedbacks | Humor individual de qualquer pessoa. Dados de outras filiais. O ranking. |
| **Administrador** | Dono ou responsável de RH | Tudo do gestor, mais: cadastra usuários e filiais, vê o painel consolidado de todas as unidades, consulta o ranking de participação | Humor individual de qualquer pessoa. |

Note a linha que atravessa a coluna da direita: **ninguém, em nenhum papel, vê o humor de
uma pessoa identificada.** Não é uma tela que faltou fazer — é uma regra estrutural. Essa
é a resposta para a pergunta "e se o chefe usar isso para perseguir alguém?".

Há também um recorte organizacional em três níveis: **organização → filial → setor**. Uma
empresa com três lojas é uma organização com três filiais, e o gestor de cada loja só
enxerga a sua.

---

## 4. As três frentes, e como se conectam

O produto tem três módulos. O que torna a proposta original não é nenhum deles isolado — é
o encaixe entre eles.

```
   ESCUTA CONTÍNUA          DESENVOLVIMENTO           APOIO À DECISÃO
   ───────────────          ───────────────           ───────────────
   Check-in de humor  ──XP──▶  Pontos e níveis          Painel agregado
   Feedback anônimo            Plano de Carreira        Busca por competência
                               Portfólio                Ranking (só admin)
          │                          │                         ▲
          └──────────────────────────┴─────────────────────────┘
                    tudo alimenta os indicadores
```

**A engrenagem central é o XP.** O colaborador registra o humor porque ganha pontos; os
pontos o fazem subir de nível e avançar no plano de carreira; o avanço no plano vira
emblema e entra no portfólio; o portfólio permite que a liderança o encontre para uma
promoção interna. E, como efeito colateral, a empresa passa a ter dado diário de clima.

Sem a gamificação, o check-in diário morreria em duas semanas — é esse o problema que ela
resolve. **Gamificação aqui não é enfeite: é o motor de adesão que viabiliza a escuta.**

---

## 5. Passeio pelas funcionalidades

Para cada uma: o que faz, por que existe, e como funciona de verdade.

### 5.1 Check-in diário de humor (RF01)

**O que faz.** Uma vez por dia, o colaborador escolhe entre três opções: feliz, neutro,
triste. Leva menos de cinco segundos.

**Por que existe.** Pesquisas anuais de clima capturam retratos espaçados de um fenômeno
contínuo. A literatura de *pulse surveys* (Welbourne, 2016) mostra que instrumentos curtos
e frequentes captam variação quase em tempo real. Mas a mesma literatura alerta para
**fadiga de resposta**: se você pergunta demais, as pessoas param de responder. Daí a
escolha de um instrumento minimalista de três opções, e não um questionário diário.

**Como funciona.** Um registro por usuário por dia, garantido por unicidade. A data é
resolvida no fuso da organização, não do servidor — "hoje" é ambíguo e precisa de dono.
Cada check-in concede **10 XP**, ou **15 XP se a ofensiva estiver ativa** (dois ou mais
dias consecutivos).

**Limitação assumida.** Humor em três níveis **não é medida psicométrica de
engajamento**. Não é a UWES de Schaufeli e Bakker. É um indicador comportamental
simplificado, e o painel deve apresentá-lo como sinal de tendência, nunca como
diagnóstico. Essa limitação é declarada explicitamente na fundamentação do projeto.

### 5.2 Canal de feedback (RF02)

**O que faz.** O colaborador escreve uma mensagem sobre o ambiente de trabalho. Pode
marcar como **anônima** e como **urgente**. O canal é bidirecional: a liderança também
envia feedback ao colaborador.

**Por que existe.** Abdulgalimov et al. (2020), no CHI, mostram que sistemas de *employee
voice* só funcionam quando o remetente confia na proteção. Sem segurança psicológica, o
canal existe e ninguém usa.

**Como funciona — e este é o ponto mais importante do sistema.** Quando o envio é anônimo,
o identificador do autor **simplesmente não é gravado**. No código atual:

```php
'user_id' => $anonimo ? null : $autor->id,
```

Não é criptografado, não é hash, não é oculto na interface. **Não existe.** É isso que o
artigo chama de *anonimato técnico irreversível por construção*: não há como reverter
porque não há o que reverter. Só permanecem a organização e a filial, necessárias para
rotear a mensagem à liderança certa.

O feedback marcado como urgente dispara notificação por e-mail ao Administrador (RF03).

**Evidência verificável:** um teste automatizado envia feedback anônimo e confirma que
nenhum registro anônimo tem autor preenchido.

### 5.3 Gamificação — XP, níveis e ofensivas (RF04)

**O que faz.** O colaborador acumula XP por check-ins, ofensivas, metas cumpridas e avanço
no plano de carreira. O XP determina seu nível.

**Por que existe, e por que assim.** Aqui a fundamentação é a mais cuidadosa do trabalho.
Hamari, Koivisto e Sarsa (2014), revisando 24 estudos, encontram efeitos positivos **mas
condicionais** — parte dos estudos reporta efeito nulo ou negativo quando as mecânicas são
percebidas como controle ou geram competição indesejada. Tondello et al. (2016) mostram
que pessoas diferentes respondem de formas diferentes às mesmas mecânicas.

Por isso as mecânicas escolhidas são **centradas no indivíduo**, não na comparação: XP,
níveis, ofensivas e emblemas. O colaborador compete com o próprio histórico. A única
exposição entre pares é o destaque mensal de participação.

**Como funciona — a curva de níveis.** Cada nível exige progressivamente mais:

| Nível | XP acumulado | XP do segmento | Dias de check-in em ofensiva |
|---:|---:|---:|---:|
| 1 | 0 | — | — |
| 2 | 50 | 50 | ~3 |
| 3 | 120 | 70 | 8 |
| 4 | 210 | 90 | 14 |
| 5 | 320 | 110 | ~21 |
| 6 | 450 | 130 | 30 |

O segmento cresce em progressão aritmética de 20 XP. Um mês inteiro de check-ins diários
leva ao nível 6. A curva é desenhada para dar recompensa rápida no começo — quando o hábito
ainda é frágil — e desacelerar depois, quando ele já se sustenta.

### 5.4 Plano de Progressão de Carreira — PPC (RF05)

**O que faz.** Um plano formal, com etapas, que o colaborador percorre. Ao concluir, ganha
emblema e o registro entra no seu portfólio.

**Por que existe.** Dutra (2017) posiciona o colaborador como sujeito ativo do próprio
desenvolvimento, não como recurso administrado. Scholz (2025), no modelo *Talent Tree*,
propõe justamente vincular mecânicas de jogo à progressão de carreira — mas é modelo
conceitual, sem artefato construído. **O Orbit RH implementa o que Scholz propõe.**

**Como funciona.** No protótipo o fluxo é rico: o colaborador **solicita** um PPC, a
liderança **aprova**, ele cumpre tarefas e objetivos (pontuação, ofensiva, metas), registra
uma **reflexão final** e anexa comprovante, e a liderança **conclui** concedendo o emblema.

### 5.5 Portfólio de competências e busca (RF06, RF07)

**O que faz.** Cada colaborador registra no perfil suas habilidades, formações e cursos.
Emblemas do PPC entram automaticamente. A liderança busca por palavra-chave.

**Por que existe.** É a ponte entre desenvolvimento individual e retenção. Chiavenato
(2020) mostra que o turnover disfuncional pesa proporcionalmente mais em empresas
pequenas, onde cada saída leva fatia maior do conhecimento. Promover internamente alguém
que já conhece o negócio abre horizonte de carreira **sem desligamento**.

**Como funciona.** O caso de uso concreto: abre uma vaga que exige domínio de determinada
ferramenta; o administrador busca pela competência; o sistema encontra alguém **de outra
filial** que já a declarou.

**Exceção deliberada ao isolamento.** Essa busca **atravessa a barreira de filial de
propósito**. É uma exceção documentada ao isolamento que o resto do sistema impõe: não é
uma falha de segurança, é requisito de mobilidade interna, restrito a Administrador e
Gestor, e nunca atravessa organizações.

Note também que o portfólio é o **único dado pessoal identificado** exposto à liderança —
e o artigo justifica isso com finalidade declarada e legítima, na linha do art. 7º da LGPD.

### 5.6 Painel de indicadores (RF08)

**O que faz.** Mostra à liderança o clima e o engajamento por unidade: média de humor,
taxa de participação, séries temporais, feedbacks urgentes.

**Como funciona — e a regra que importa.** Tudo **agregado**. Nunca humor individual. E há
uma segunda proteção: não exibir indicador de grupo com menos de cinco respondentes,
porque em um grupo de dois a média revela o indivíduo. Isso se chama proteção contra
reidentificação, e é o tipo de detalhe que impressiona quem entende de privacidade.

O Gestor vê a própria filial; o Administrador vê todas.

### 5.7 Ranking de participação (RF12 — decidido em 28/07)

**O que faz.** Ordena colaboradores por XP acumulado. **Visível exclusivamente ao
Administrador.**

**Por que essa restrição.** A crítica da literatura a rankings dirige-se à competição
**entre pares** — e competição entre pares pressupõe que o colaborador veja sua posição.
Como ninguém além do Administrador vê, não há competição a ser gerada. O ranking deixa de
ser mecânica de gamificação e passa a ser **indicador gerencial**, para identificar
unidades com baixa adesão.

**Ordena por participação, jamais por humor.** Ranquear pessoas por estado emocional
seria exatamente o "vigiar individualmente" que o trabalho se propõe a evitar.

---

## 6. A arquitetura, em camadas

Três camadas, e a segurança mora em duas delas.

```
┌─ APRESENTAÇÃO ─ React + Vite + Tailwind ──────────────────┐
│  Componentes · proteção de rotas por papel · axios        │
└────────────────────────┬───────────────────────────────────┘
                         │  HTTPS + token Bearer
┌─ NEGÓCIO ─ Laravel 11 ─▼───────────────────────────────────┐
│  Middleware Sanctum (autenticação)                         │
│  Middleware RBAC (papel)                                   │
│  Controllers → Services (CheckInService, XpService…)       │
│  🛡️ CAMADA 1 — Global Scope: injeta organization_id        │
│                 e branch_id em toda query                  │
└────────────────────────┬───────────────────────────────────┘
┌─ PERSISTÊNCIA ─ PostgreSQL ▼───────────────────────────────┐
│  🛡️ CAMADA 2 — Row Level Security: políticas por linha,    │
│                independentes da aplicação                  │
│  Tabelas: toda linha de negócio tem organization_id        │
└────────────────────────────────────────────────────────────┘
```

### Por que multitenant

Uma única instalação atende várias empresas. É o que torna o produto viável
economicamente para PME: o custo de servir mais um cliente é quase zero, e ninguém precisa
instalar nada (Mell e Grance, 2011, definição de SaaS do NIST).

Krebs, Momm e Kounev (2012) descrevem três modelos de isolamento: bancos separados,
esquemas separados, ou **banco único com identificação do tenant em cada linha**. O Orbit
RH adota o terceiro — o mais barato e o que concentra o risco na correção do filtro
lógico. **É justamente por concentrar o risco no filtro que existem duas camadas.**

### Por que duas camadas de isolamento

Isso se chama **defesa em profundidade**, e é a contribuição técnica central do trabalho.

- **Camada 1 (Global Scope):** o Laravel adiciona automaticamente `organization_id = X` a
  toda consulta. Se um desenvolvedor esquecer o filtro, o framework não esquece.
- **Camada 2 (Row Level Security):** o PostgreSQL define políticas no nível da linha. Mesmo
  que a aplicação falhe inteira, **o banco recusa devolver a linha**.

A redundância é deliberada. O argumento: em dados de percepção de colaboradores, um
vazamento entre empresas ou entre filiais não é prejuízo comercial — é violação de
privacidade de pessoas que confiaram no anonimato.

**Esta é a razão de o banco ser PostgreSQL e não outro.** A RLS é nativa dele. Se alguém
perguntar "por que não MySQL?", a resposta é essa, e é a única que importa.

### Como se demonstra

Desabilita-se o Global Scope, executa-se a consulta, e observa-se a RLS barrando o
acesso mesmo assim. É a evidência mais direta da arquitetura de defesa em profundidade
descrita nesta seção.

---

## 7. Perguntas frequentes sobre as decisões de arquitetura

| Pergunta | Resposta curta |
|---|---|
| Por que humor em três níveis e não uma escala validada? | Trade-off deliberado: fricção quase zero para sustentar frequência diária. Assume-se explicitamente que é indicador de tendência, não medida psicométrica. |
| Por que PostgreSQL? | Row Level Security nativa. É requisito, não preferência. |
| RLS e Global Scope não é redundante? | É, de propósito. Defesa em profundidade. Posso demonstrar. |
| Como você garante o anonimato? | O autor não é gravado. Não há o que reverter. Tenho teste que prova. |
| Um check-in por dia — e a virada de meia-noite? | Data resolvida no fuso da organização, com unicidade no banco. Testado nos dois lados da virada. |
| A busca por competência não fura o isolamento por filial? | Fura, deliberadamente. É requisito de mobilidade interna, restrito por papel, e nunca atravessa organizações. |
| Você não critica rankings no referencial? | Critico rankings **entre pares**. Este é indicador gerencial, invisível ao colaborador. |
| Oito participantes bastam? | Para teste formativo de usabilidade, sim. A generalização é analítica, não estatística. |
| Isso atende mesmo à NR-1? | Instrumenta a identificação contínua e gera registro de monitoramento. Não substitui avaliação de risco psicossocial por profissional. |
| Qual a diferença para o Feedz, o Pulses, o Officevibe? | Nenhum reúne captação diária + gamificação vinculada a plano de carreira + portfólio consultável + conformidade embarcada. É essa intersecção. |

---

## 8. O que o Orbit RH **não** é

Saber os limites é sinal de domínio. Diga antes que perguntem.

- **Não é folha de pagamento nem ponto eletrônico.** Não faz admissão, cálculo trabalhista
  nem controle de jornada.
- **Não é ferramenta de diagnóstico de saúde mental.** Sinaliza tendência; não substitui
  profissional.
- **Não é sistema de avaliação de desempenho.** Não há nota, não há 360°.
- **Não é ferramenta de vigilância.** Não registra tela, localização ou produtividade.
- **Não foi validado quanto ao efeito real sobre engajamento.** Isso exigiria implantação
  longitudinal na cultura de uma empresa — está previsto como piloto futuro, fora do escopo
  deste trabalho. O que foi avaliado é **usabilidade**.

Esse último ponto é o mais importante. Se perguntarem "e provou que reduz turnover?", a
resposta honesta é: **não, e o trabalho nunca afirmou isso.**

---

## 9. O que muda do protótipo para a reconstrução

O código atual em `Orbit-test/` já implementa boa parte do produto — e algumas coisas bem.
Mas diverge do artigo em pontos que precisam ser corrigidos na reconstrução:

| Item | Protótipo hoje | Na reconstrução |
|---|---|---|
| Banco | **SQLite** — sem RLS possível | PostgreSQL com RLS |
| Isolamento | `tenant_id` adicionado 40 dias depois do schema | `organization_id` na primeira migration de cada tabela |
| XP | Contador em `users.xp` | Log de eventos (`xp_events`), auditável |
| Papéis | Quatro valores: `admin`, `admin_master`, `gestor`, `colaborador` | Três, como diz o artigo |
| Vocabulário | Duplicado: `Goal`/`Meta`, `Reward`/`Recompensa` | Um idioma só, definido no `CLAUDE.md` |
| Recompensas/resgates | Módulo completo, fora dos requisitos | Removido |
| `GET /comunicados` | **Público, sem autenticação** | Autenticado e escopado por organização |
| Testes | 4 arquivos, 2 são exemplos do Laravel | 7 cenários obrigatórios, escritos junto ao incremento |

**O que o protótipo já faz certo e deve ser preservado:** o anonimato do feedback está
implementado corretamente (`user_id` nulo quando anônimo); o ranking já é restrito ao
administrador por middleware; o check-in já valida unicidade diária; os arquivos de
comprovante já usam URLs assinadas; e a separação em Services é boa arquitetura.

---

## 10. Glossário

| Termo | Significado |
|---|---|
| **Pulse survey** | Pesquisa curta e frequente, oposta à pesquisa anual de clima |
| **Turnover disfuncional** | Rotatividade que faz a empresa perder conhecimento — o contrário do funcional, que renova |
| **Multitenant** | Uma instalação do software atende várias empresas, com dados isolados |
| **Tenant** | Cada empresa cliente dentro da instalação |
| **RBAC** | Controle de acesso baseado em papéis: permissões vão para o papel, não para a pessoa |
| **Row Level Security (RLS)** | Recurso do PostgreSQL que restringe quais linhas cada conexão enxerga |
| **Global Scope** | Filtro do Laravel aplicado automaticamente a toda consulta de um model |
| **Defesa em profundidade** | Usar barreiras redundantes, para que a falha de uma não exponha o sistema |
| **Anonimato técnico** | O dado identificador não é armazenado — diferente de ocultá-lo na tela |
| **Ofensiva (streak)** | Sequência de dias consecutivos com check-in |
| **PPC** | Plano de Progressão de Carreira |
| **DSR** | Design Science Research — método cujo produto central é um artefato |
| **SUS** | System Usability Scale — questionário de 10 itens, escore de 0 a 100, média de referência 68 |
| **NR-1** | Norma Regulamentadora que passou a exigir gestão de riscos psicossociais |
| **LGPD** | Lei Geral de Proteção de Dados Pessoais (Lei 13.709/2018) |

---

## Uso deste documento

Este documento consolida a fundamentação do produto e as decisões de arquitetura que a
sustentam. A Seção 7 funciona como referência rápida para perguntas recorrentes sobre
essas decisões; as demais seções contêm o raciocínio completo por trás de cada resposta,
permitindo derivar posicionamento sobre questões não cobertas explicitamente aqui.
