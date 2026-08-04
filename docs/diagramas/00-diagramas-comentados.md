# Orbit RH — Diagramas (Mermaid)

Salve cada bloco como um arquivo `.mmd` em `docs/diagramas/` do repositório novo.

> ⚠️ **Estes diagramas são hipóteses sobre o seu modelo, não verdade.** Revise cada um
> contra o que você vai implementar e ajuste. Revisar é parte de dominar o projeto —
> se você aceitar sem ler, repetimos o erro que estamos consertando.

---

## 01 — Entidade-Relacionamento

`docs/diagramas/01-der.mmd`

Legenda para o artigo: *Figura X — Modelo de dados do Orbit RH. Toda entidade de
negócio carrega `organization_id`, base do isolamento multitenant descrito em 2.4.*

```mermaid
erDiagram
    ORGANIZATIONS ||--o{ BRANCHES : possui
    ORGANIZATIONS ||--o{ USERS : emprega
    BRANCHES     ||--o{ USERS : lotado_em

    USERS ||--o{ MOOD_CHECKINS : registra
    USERS ||--o{ XP_EVENTS : acumula
    USERS ||--o{ USER_COMPETENCIES : declara
    USERS ||--o{ CAREER_PLANS : segue
    USERS ||--o{ USER_BADGES : conquista

    CAREER_PLANS      ||--o{ CAREER_PLAN_STEPS : composto_por
    CAREER_PLAN_STEPS ||--o| USER_BADGES : concede
    BADGES            ||--o{ USER_BADGES : referencia

    ORGANIZATIONS ||--o{ FEEDBACKS : recebe
    USERS         ||--o{ FEEDBACKS : envia_identificado

    ORGANIZATIONS {
        bigint id PK
        string name
        string timezone
        timestamp created_at
    }

    BRANCHES {
        bigint id PK
        bigint organization_id FK
        string name
    }

    USERS {
        bigint id PK
        bigint organization_id FK
        bigint branch_id FK
        string name
        string email
        string password_hash
        enum   role "admin|gestor|colaborador"
    }

    MOOD_CHECKINS {
        bigint id PK
        bigint organization_id FK
        bigint branch_id FK
        bigint user_id FK
        smallint mood "1|2|3"
        date   checkin_date "UNIQUE(user_id, checkin_date)"
        timestamp created_at
    }

    XP_EVENTS {
        bigint id PK
        bigint organization_id FK
        bigint user_id FK
        enum   source "checkin|streak|goal|ppc_step"
        int    points
        bigint reference_id "polimorfico, nullable"
        timestamp created_at
    }

    FEEDBACKS {
        bigint id PK
        bigint organization_id FK
        bigint branch_id FK
        bigint user_id FK "NULL quando anonimo - nunca gravado"
        boolean is_anonymous
        boolean is_urgent
        text   message
        timestamp created_at
    }

    CAREER_PLANS {
        bigint id PK
        bigint organization_id FK
        bigint user_id FK
        string title
        enum   status "nao_iniciado|em_andamento|concluido"
    }

    CAREER_PLAN_STEPS {
        bigint id PK
        bigint career_plan_id FK
        string title
        int    order
        int    xp_reward
        timestamp completed_at "nullable"
    }

    USER_COMPETENCIES {
        bigint id PK
        bigint organization_id FK
        bigint user_id FK
        string name
        enum   kind "habilidade|curso|certificacao"
        enum   origin "autodeclarada|ppc"
    }

    BADGES {
        bigint id PK
        bigint organization_id FK
        string name
        string icon
    }

    USER_BADGES {
        bigint id PK
        bigint user_id FK
        bigint badge_id FK
        timestamp awarded_at
    }
```

**Pontos para você defender:**

- `organization_id` em toda tabela de negócio → é o que o Global Scope e as políticas de
  RLS usam como filtro (RNF02, RNF03).
- `UNIQUE(user_id, checkin_date)` → a unicidade do RF01 é garantida **pelo banco**, não
  só pela aplicação. Se a banca perguntar "e se dois requests chegarem juntos?", a
  resposta é a constraint.
- `XP_EVENTS` como log de eventos, não contador no `users` → auditável e reconstruível.
  Se perguntarem por que, a resposta é: você consegue provar de onde veio cada ponto.
- `FEEDBACKS.user_id` nullable **e não preenchido** quando anônimo → o campo existe para
  o feedback identificado; no anônimo ele simplesmente não é escrito.

---

## 02 — Casos de uso

`docs/diagramas/02-casos-de-uso.mmd`

Mermaid não tem diagrama de caso de uso nativo. Este `flowchart` é a convenção aceita e
renderiza bem.

```mermaid
flowchart LR
    COL(["👤 Colaborador"])
    GES(["👤 Gestor"])
    ADM(["👤 Administrador"])

    subgraph ESCUTA["Escuta contínua"]
        UC01["RF01 · Registrar check-in diário de humor"]
        UC02["RF02 · Enviar feedback (identificado ou anônimo)"]
    end

    subgraph DESENV["Desenvolvimento individual"]
        UC04["RF04 · Acumular XP e ofensivas"]
        UC05["RF05 · Avançar no Plano de Progressão de Carreira"]
        UC06["RF06 · Manter portfólio de competências"]
    end

    subgraph LIDER["Apoio à decisão"]
        UC07["RF07 · Buscar colaborador por competência"]
        UC08["RF08 · Consultar painel de indicadores agregados"]
        UC12["Responder feedback"]
    end

    subgraph ADMIN["Administração"]
        UC11["RF11 · Gerenciar usuários e papéis"]
        UC13["Gerenciar filiais"]
        UC14["Definir PPCs e emblemas"]
        UC15["RF12 · Consultar ranking interno de participação<br/><b>exclusivo do Administrador</b>"]
    end

    COL --> UC01
    COL --> UC02
    COL --> UC04
    COL --> UC05
    COL --> UC06

    GES --> UC07
    GES --> UC08
    GES --> UC12
    GES --> UC14

    ADM --> UC07
    ADM --> UC08
    ADM --> UC12
    ADM --> UC11
    ADM --> UC13
    ADM --> UC14
    ADM --> UC15

    UC01 -.->|inclui| UC04
    UC05 -.->|inclui| UC04
    UC05 -.->|estende| UC06

    classDef restrito fill:#332200,stroke:#f5b942,stroke-width:2px,color:#fff
    class UC15 restrito
```

**Note o que este diagrama torna visível:** o Gestor vê o painel da sua unidade; o
Administrador vê todas. Essa distinção precisa aparecer também no código (RBAC + escopo
por filial). Se você desenhar assim e implementar diferente, criou uma contradição nova.

**O UC15 está destacado de propósito.** Ele é o único caso de uso ligado a um único
ator, e é o que carrega a decisão de privacidade mais delicada do sistema: o ranking
nomeia pessoas. Ao desenhá-lo isolado no Administrador, o diagrama já comunica que a
comparação entre pares não é exposta a pares — que é exatamente o argumento da seção 2.3
revisada.

---

## 03 — Arquitetura em camadas

`docs/diagramas/03-arquitetura.mmd`

Este é o diagrama que falta na seção 4.4 — você descreve as três camadas em texto, sem
figura.

```mermaid
flowchart TB
    subgraph CLIENTE["Camada de apresentação — React + Vite + Tailwind"]
        UI["Componentes de interface<br/>design system Orbit"]
        RT["Proteção de rotas por papel<br/>ProtectedRoute"]
        AX["Cliente HTTP (axios)<br/>token no header"]
    end

    subgraph SERVIDOR["Camada de regras de negócio — Laravel 11"]
        MW1["Middleware: autenticação<br/>Laravel Sanctum"]
        MW2["Middleware: RBAC<br/>admin | gestor | colaborador"]
        CTRL["Controllers · API REST /api/v1"]
        SRV["Services<br/>XpService · CheckinService · FeedbackService"]
        SCOPE["🛡️ Camada 1 — Global Scope<br/>injeta organization_id e branch_id<br/>em toda query do Eloquent"]
    end

    subgraph BANCO["Camada de persistência — PostgreSQL"]
        RLS["🛡️ Camada 2 — Row Level Security<br/>políticas por linha<br/>independentes da aplicação"]
        TB[("Tabelas de negócio<br/>toda linha tem organization_id")]
    end

    UI --> RT --> AX
    AX -->|"HTTPS + Bearer token"| MW1
    MW1 --> MW2 --> CTRL --> SRV --> SCOPE
    SCOPE -->|"SET app.current_org"| RLS
    RLS --> TB

    classDef defesa fill:#003333,stroke:#80d4d4,stroke-width:2px,color:#fff
    class SCOPE,RLS defesa
```

**Os dois blocos destacados são a sua contribuição técnica.** Este diagrama é o slide
central da sua apresentação: mostre que, se a Camada 1 falhar, a Camada 2 ainda barra.
Depois rode o teste ao vivo.

---

## 04 — Sequência: check-in diário + XP

`docs/diagramas/04-seq-checkin.mmd`

```mermaid
sequenceDiagram
    autonumber
    actor C as Colaborador
    participant F as React (SPA)
    participant M as Middleware<br/>Sanctum + RBAC
    participant CT as CheckinController
    participant S as XpService
    participant DB as PostgreSQL<br/>(Global Scope + RLS)

    C->>F: seleciona humor (1, 2 ou 3)
    F->>M: POST /api/v1/checkins {mood}<br/>Bearer token
    M->>M: valida token e papel
    M->>CT: requisição autorizada

    CT->>CT: resolve a data local<br/>usando timezone da organização
    CT->>DB: existe check-in para (user_id, checkin_date)?
    DB-->>CT: não

    CT->>DB: INSERT mood_checkins<br/>+ organization_id, branch_id
    Note over DB: UNIQUE(user_id, checkin_date)<br/>protege contra requisição concorrente

    CT->>S: registrarXp(user, origem: checkin)
    S->>DB: houve check-in ontem?
    DB-->>S: sim → ofensiva continua
    S->>S: pontos = base × 2 (ofensiva ativa)
    S->>DB: INSERT xp_events {source, points}

    S-->>CT: {xp_ganho, ofensiva_dias, nivel}
    CT-->>F: 201 Created
    F-->>C: confirmação + XP e ofensiva atualizados

    rect rgba(220,38,38,0.12)
    Note over C,DB: Segunda tentativa no mesmo dia
    C->>F: tenta novo check-in
    F->>CT: POST /api/v1/checkins
    CT->>DB: já existe para hoje?
    DB-->>CT: sim
    CT-->>F: 409 Conflict
    F-->>C: "Você já registrou seu humor hoje."
    end
```

**Cenários de teste que este diagrama define** (e que o artigo, em 4.4, promete):

- Check-in duplicado no mesmo dia → 409.
- Check-in às 23h59 e outro às 00h01 → **dois registros válidos**, dias diferentes.
- Ofensiva quebra quando há um dia sem check-in.
- XP dobrado só quando a ofensiva está ativa.

---

## 05 — Sequência: feedback anônimo ⭐

`docs/diagramas/05-seq-feedback-anonimo.mmd`

O diagrama mais importante do conjunto. Ele torna visual a afirmação mais forte do seu
trabalho.

```mermaid
sequenceDiagram
    autonumber
    actor C as Colaborador
    participant F as React (SPA)
    participant M as Middleware<br/>Sanctum
    participant CT as FeedbackController
    participant DB as PostgreSQL

    C->>F: escreve mensagem + marca "enviar anonimamente"
    F->>M: POST /api/v1/feedbacks<br/>{message, is_anonymous: true}<br/>Bearer token

    M->>M: autentica → identidade conhecida AQUI
    M->>CT: request com auth()->user()

    rect rgba(0,128,128,0.18)
    Note over CT: PONTO DE DESCARTE DA IDENTIDADE
    CT->>CT: is_anonymous === true?
    CT->>CT: monta payload SEM a chave user_id<br/>organization_id e branch_id são mantidos<br/>(necessários para roteamento e RLS)
    end

    CT->>DB: INSERT feedbacks<br/>(organization_id, branch_id,<br/>is_anonymous, message)
    Note over DB: coluna user_id permanece NULL<br/>não há log, hash ou referência<br/>a identidade não existe em lugar nenhum

    DB-->>CT: id do registro
    CT-->>F: 201 Created
    F-->>C: "Feedback enviado anonimamente."

    rect rgba(255,255,255,0.06)
    Note over C,DB: Consulta posterior pela liderança
    participant A as Administrador
    A->>CT: GET /api/v1/feedbacks
    CT->>DB: SELECT ... WHERE organization_id = ?
    DB-->>CT: registros
    CT-->>A: remetente exibido como "Anônimo"<br/>— não por ocultação na interface,<br/>mas por ausência do dado
    end
```

**O argumento que você faz na banca:** o anonimato não é uma decisão de apresentação
(esconder o nome na tela), é uma decisão de persistência (o nome nunca foi gravado).
Reversibilidade é impossível porque não há o que reverter. Isso é o que a seção 2.6
chama de "anonimato técnico irreversível por construção", e atende ao princípio da
necessidade da LGPD.

**Teste que prova isso:**

```php
public function test_feedback_anonimo_nao_persiste_remetente(): void
{
    $user = User::factory()->create();

    $this->actingAs($user)->postJson('/api/v1/feedbacks', [
        'message'      => 'Sobrecarga na equipe de logística.',
        'is_anonymous' => true,
    ])->assertCreated();

    $this->assertDatabaseHas('feedbacks', [
        'message' => 'Sobrecarga na equipe de logística.',
        'user_id' => null,
    ]);

    // nenhum feedback anônimo, em nenhuma hipótese, carrega remetente
    $this->assertSame(
        0,
        Feedback::where('is_anonymous', true)->whereNotNull('user_id')->count()
    );
}
```

---

## 06 — Fluxo de seleção do mapeamento sistemático

`docs/diagramas/06-selecao-mapeamento.mmd`

Resolve a lacuna A2 do plano mestre. O protocolo de Kitchenham espera este fluxo, e hoje
seu artigo só informa o começo (353) e o fim (5).

⚠️ **Os números intermediários abaixo são ilustrativos.** Substitua pelos reais quando
refizer a triagem — e é por isso que vale refazer com mais bases (item A1 do plano).

```mermaid
flowchart TD
    A["Registros identificados na busca<br/><b>n = 353</b>"] --> B{"CE1<br/>duplicados entre bases"}
    B -->|"removidos: n = ?"| C["Registros únicos<br/><b>n = ?</b>"]

    C --> D{"CI1 · CI2<br/>idioma (PT/EN)<br/>período (2024–2026)"}
    D -->|"excluídos: n = ?"| E["Triados por título e resumo<br/><b>n = ?</b>"]

    E --> F{"CI3<br/>alinhamento temático"}
    F -->|"excluídos: n = ?"| G["Elegíveis para texto integral<br/><b>n = ?</b>"]

    G --> H{"CE2 · CE3 · CE4<br/>restrito a grandes corporações<br/>texto inacessível<br/>método insuficiente"}
    H -->|"excluídos: n = ?"| I["<b>Estudos incluídos<br/>n = 5</b>"]

    I --> J["Siswanto et al. (2024)"]
    I --> K["Obaid e Farooq (2024)"]
    I --> L["Souza (2025)"]
    I --> M["Scholz (2025)"]
    I --> N["Abdulgalimov et al. (2020)*"]

    N -.-> O["*fora do recorte temporal<br/>incluído por snowballing —<br/>justificar no texto"]

    classDef inc fill:#003333,stroke:#80d4d4,stroke-width:2px,color:#fff
    classDef nota fill:#332200,stroke:#f5b942,stroke-width:1px,color:#fff
    class I inc
    class O nota
```

Este diagrama já embute a correção do bloqueador **B2** (Abdulgalimov é de 2020, fora do
recorte 2024–2026). Ao desenhá-lo assim, você transforma uma inconsistência em uma
decisão metodológica explícita — que é como se resolve esse tipo de problema em pesquisa.

---

## Renderizar para o artigo

```bash
npm i -g @mermaid-js/mermaid-cli

for f in docs/diagramas/*.mmd; do
  mmdc -i "$f" -o "docs/diagramas/png/$(basename "${f%.mmd}").png" \
       -b transparent -s 3
done
```

`-s 3` gera em 3× a resolução — necessário para não sair borrado no PDF impresso.

Para o Word/PDF final, prefira **SVG** (`-o arquivo.svg`) se o seu editor aceitar:
vetorial não perde qualidade em nenhum zoom.
