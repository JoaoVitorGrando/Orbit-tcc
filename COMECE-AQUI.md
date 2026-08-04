# Comece aqui

**Orbit RH — reconstrução do zero · Sprint 0 · Dia 1**

---

## Passo 0 — Mover esta pasta (uma vez só)

Esta pasta foi criada dentro de `Orbit-test` por limitação de acesso. **Mova-a para fora
antes de qualquer outra coisa:**

```
De:    C:\Users\João.Grando\Orbit-test\orbit-rh
Para:  C:\Users\João.Grando\orbit-rh
```

É um arrastar-e-soltar no Explorador de Arquivos.

**Por que isso importa:** a `Orbit-test` tem um `.git` na raiz, com o histórico do
protótipo. Se o projeto novo ficar dentro dela, ele nasce aninhado num repositório que não
é dele — os commits se misturam e o `git log` que você vai apresentar como evidência de
processo fica poluído com os 6 commits antigos.

A `Orbit-test` permanece intacta como arquivo morto. Você ainda vai consultá-la.

---

## Passo 1 — Inicializar o repositório

Abra o terminal na pasta nova:

```bash
cd C:\Users\João.Grando\orbit-rh
git init
git add .
git commit -m "chore: estrutura inicial, documentação e design system"
```

Este é o **commit 1 de um histórico que vai contar a história do seu Scrum.** A partir
daqui, cada commit carrega o código do requisito que ele entrega.

---

## Passo 2 — Ler, nesta ordem

| Ordem | Arquivo | Por quê |
|---|---|---|
| 1 | `docs/ESTUDO-DO-PRODUTO.md` | Entender o produto inteiro antes de escrever a primeira linha |
| 2 | `docs/PLANO-MESTRE-30-DIAS.md` | O cronograma e o diagnóstico do artigo |
| 3 | `CLAUDE.md` | As regras que governam como você e a IA trabalham juntos |
| 4 | `docs/GUIA-FRONTEND-E-DIAGRAMAS.md` | Como portar o visual e quais diagramas fazer |

Os demais são de consulta, quando chegar a hora.

---

## Passo 3 — Subir o esqueleto

**Back-end (Laravel + PostgreSQL):**

```bash
composer create-project laravel/laravel backend
cd backend
composer require laravel/sanctum
php artisan install:api
```

No `backend/.env`, configure PostgreSQL — **não deixe SQLite**:

```
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=orbit_rh
DB_USERNAME=postgres
DB_PASSWORD=sua_senha
```

> ⚠️ Se você não tem PostgreSQL instalado, instale agora. **Esta é a decisão técnica que
> sustenta a contribuição central do seu TCC** — a Row Level Security da seção 2.5 e o
> RNF03 só existem nele. O protótipo antigo usava SQLite, e é por isso que a defesa em
> profundidade nunca saiu do papel.

**Front-end (React + Vite + Tailwind):**

```bash
cd ..
npm create vite@latest frontend -- --template react
cd frontend
npm i @headlessui/react @heroicons/react @tailwindcss/postcss axios \
      date-fns lucide-react postcss react-hot-toast react-router-dom \
      recharts tailwindcss
```

---

## Passo 4 — Instalar o design system

Copie os quatro arquivos de `design-system/` para dentro do projeto React:

```bash
# a partir de orbit-rh/
cp design-system/index.css                        frontend/src/index.css
cp design-system/postcss.config.js                frontend/postcss.config.js
mkdir -p frontend/src/theme frontend/src/components/ui
cp design-system/orbitPalette.js                  frontend/src/theme/
cp design-system/parallax-cosmic-background.jsx   frontend/src/components/ui/
```

No `frontend/index.html`, dentro do `<head>`, acrescente a fonte Poppins — o design system
depende dela:

```html
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
```

**Teste de fumaça.** Rode `npm run dev` e ponha isto no `App.jsx`:

```jsx
import { CosmicParallaxBg } from './components/ui/parallax-cosmic-background'

export default function App() {
  return (
    <div className="relative h-screen w-screen">
      <CosmicParallaxBg head="Orbit RH" text="Escuta,Desenvolvimento,Decisão" loop />
    </div>
  )
}
```

Se o planeta e as estrelas aparecerem, o design system está no lugar e todas as telas
futuras já nascem no estilo certo. **Commite.**

---

## Passo 5 — Antes de escrever qualquer migration

Duas coisas, hoje, que não podem esperar:

**1. Convide 12 pessoas** para o teste de usabilidade de 22 a 24 de agosto. Você precisa
de 8; sempre falta gente. Não precisam entender de RH.

**2. Pergunte ao professor Moacir se o teste dispensa submissão ao CEP.** Sua seção 4.6
argumenta que sim, mas isso varia por instituição. **É a única pendência do projeto sem
solução de última hora** — parecer de CEP leva semanas. Se o Campo Real exigir, o
cronograma inteiro muda e é melhor saber agora.

---

## Passo 6 — Modelar os dados no papel

Antes de rodar `php artisan make:migration`, desenhe o modelo à mão. Use o
`docs/diagramas/01-der.mmd` como ponto de partida — **mas ele é uma hipótese minha, não
verdade.** Revisar e discordar dele faz parte de dominar o projeto.

Regra que não se negocia: **toda tabela de dado de negócio nasce com `organization_id`.**
Na primeira migration, não em uma migration `add_tenant_id_to_...` quarenta dias depois.
É o que torna verdadeira a frase do seu artigo sobre multitenancy "desde o início".

---

## Ritmo diário

| Momento | O quê |
|---|---|
| Antes de pedir código | Escreva em 2 frases o que a funcionalidade faz e onde você acha que ela vai. Se não sabe, **pergunte antes de pedir o código**. |
| Depois de receber | Feche a resposta. Reescreva de memória o que o código faz. Só então commite. |
| Fim do dia | Uma entrada no `DIARIO.md`: o que fiz, o que quebrou, **o que ainda não entendo**. A terceira é a mais importante. |

> **A regra que governa o mês:** nenhuma linha entra no repositório sem que você consiga
> explicá-la em voz alta para alguém que não a leu.
