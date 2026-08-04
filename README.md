# Orbit RH

Plataforma web multitenant para a gestão estratégica de pessoas em pequenas e médias
empresas brasileiras.

Artefato do Trabalho de Conclusão de Curso de **João Vitor Souza Grando**, sob orientação
do **Prof. Moacir Guedes** — Centro Universitário Campo Real, 2026.

---

## O que o sistema faz

Permite que uma PME saiba continuamente como seus colaboradores estão se sentindo, sem
depender de pesquisas anuais de clima nem de software corporativo caro. Três frentes que
se alimentam:

- **Escuta contínua** — check-in diário de humor em três níveis e canal de feedback com
  anonimato técnico.
- **Desenvolvimento individual** — gamificação (XP, níveis, ofensivas, emblemas) vinculada
  a um Plano de Progressão de Carreira e a um portfólio de competências.
- **Apoio à decisão** — painel de indicadores agregados por unidade e busca de
  colaboradores por competência.

Conformidade com a **NR-1** e a **LGPD** é tratada como requisito arquitetural, não como
funcionalidade opcional.

> Princípio de projeto: **escutar continuamente sem vigiar individualmente.**

---

## Stack

| Camada | Tecnologia |
|---|---|
| Apresentação | React 19 · Vite · Tailwind CSS 4 |
| Regras de negócio | Laravel 11 · PHP 8.2+ · API REST |
| Autenticação | Laravel Sanctum (tokens) sobre HTTPS |
| Persistência | PostgreSQL — escolhido pelo suporte nativo a Row Level Security |
| Testes | PHPUnit |

---

## Requisitos para executar

- PHP 8.2 ou superior
- Composer 2
- Node.js 20 ou superior
- **PostgreSQL 14 ou superior** (obrigatório — a segregação de dados depende de RLS)

---

## Instalação

```bash
git clone <url-do-repositorio> orbit-rh
cd orbit-rh
```

### Back-end

```bash
cd backend
composer install
cp .env.example .env
php artisan key:generate
```

Configure o banco no `.env`:

```
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=orbit_rh
DB_USERNAME=postgres
DB_PASSWORD=sua_senha
```

Crie o banco e popule com dados de demonstração:

```bash
createdb orbit_rh          # ou via pgAdmin
php artisan migrate:fresh --seed
php artisan serve          # http://localhost:8000
```

### Front-end

```bash
cd ../frontend
npm install
cp .env.example .env       # VITE_API_URL=http://localhost:8000/api/v1
npm run dev                # http://localhost:5173
```

---

## Credenciais de demonstração

Geradas pelo seeder. Também exibidas na própria tela de login.

| Papel | E-mail | Senha |
|---|---|---|
| Administrador | `admin@demo.local` | `12345678` |
| Gestor | `gestor@demo.local` | `12345678` |
| Colaborador | `colaborador@demo.local` | `12345678` |

O seeder é **determinístico**: `php artisan migrate:fresh --seed` sempre reconstrói o mesmo
estado. Isso é requisito do protocolo de avaliação — todos os participantes do teste de
usabilidade precisam encontrar o sistema idêntico.

---

## Testes

```bash
cd backend
php artisan test
```

Cenários cobertos, conforme a metodologia do trabalho:

- [ ] Unicidade do check-in diário, incluindo a transição de data (23h59 → 00h01)
- [ ] Cálculo de XP e de ofensivas de dias consecutivos
- [ ] Integridade do RBAC — colaborador não acessa endpoint de gestor
- [ ] Segregação por organização via Global Scope
- [ ] Segregação por filial
- [ ] **Defesa em profundidade — com o Global Scope desabilitado, a RLS ainda barra**
- [ ] Feedback anônimo não persiste o remetente
- [ ] Ranking acessível apenas ao Administrador

O penúltimo é a evidência executável da contribuição técnica do trabalho.

---

## Arquitetura

Três camadas, com o isolamento de dados aplicado em duas delas de forma independente
(defesa em profundidade):

1. **Global Scope** no Eloquent — injeta `organization_id` e `branch_id` em toda consulta.
2. **Row Level Security** no PostgreSQL — políticas no nível da linha, independentes da
   aplicação. Se a camada 1 falhar, o banco ainda recusa o acesso.

Diagramas em `docs/diagramas/`. Para renderizar em imagem:

```bash
npm i -g @mermaid-js/mermaid-cli
for f in docs/diagramas/*.mmd; do
  mmdc -i "$f" -o "docs/diagramas/png/$(basename "${f%.mmd}").png" -b transparent -s 3
done
```

---

## Estrutura do repositório

```
orbit-rh/
├── backend/                  API Laravel
├── frontend/                 SPA React
├── design-system/            Tokens e componentes visuais (fonte)
├── docs/
│   ├── ESTUDO-DO-PRODUTO.md      Explicação completa do produto
│   ├── PLANO-MESTRE-30-DIAS.md   Cronograma e diagnóstico do artigo
│   ├── GUIA-FRONTEND-E-DIAGRAMAS.md
│   ├── REVISOES-ARTIGO-ranking.md
│   ├── apendices/                Roteiro de tarefas, SUS e TCLE
│   └── diagramas/                Diagramas em Mermaid
├── CLAUDE.md                 Regras de trabalho do projeto
├── DIARIO.md                 Diário de sprint
└── COMECE-AQUI.md            Passo a passo do dia 1
```

---

## Licença e créditos

Trabalho acadêmico. Os tokens do design system derivam do template Figma *Sales Dashboard*
(NickelFox) — verificar termos de licença antes de uso comercial.
