# Briefing Técnico de Programação — Tema WordPress "Códice"

Site pessoal Bruno Anastassakis · brunoanastassakis.com

*Este documento traduz a arquitetura editorial (docs/00-arquitetura.md) em especificação de programação. É o insumo direto para os agentes de IA construírem o tema no Antigravity. Não decide nada de conteúdo editorial — textos e imagens são placeholder até a fase de produção.*

---

## 1. Objetivo da fase

Construir, do zero, um **tema WordPress clássico (PHP)** que materialize a arquitetura editorial e o Design System Códice v6. O tema é desenvolvido localmente no **WordPress Studio** e, quando pronto, exportado como ZIP e instalado na **Hostgator**. Nesta fase o site roda com **conteúdo placeholder**; o conteúdo real entra depois (projetos de calendário e produção).

Definição de pronto desta fase (Marco 1): tema instalável, todos os templates renderizando sem erro, tokens do Códice aplicados, responsivo sem estouro horizontal, acessível, com SEO/schema no código e captação Substack nativa funcional — tudo validado com placeholder.

---

## 2. Decisões técnicas (inegociáveis)

1. **Tema clássico em PHP**, não block theme / FSE. Templates PHP dão controle total do markup, essencial para a precisão estética do Códice. (Há um `theme.json` mínimo só para alinhar o editor de blocos à paleta/tipografia ao escrever posts — ver seção 8.)
2. **Sem framework de tema, sem starter, sem page builder.** Nada de Elementor, Divi, Bootstrap, Tailwind. CSS próprio com custom properties.
3. **Tokens do Códice v6 viram CSS custom properties** (seção 6). Nenhum valor de cor, fonte ou espaçamento "solto" no código — sempre via variável.
4. **Estilo de imagem único (retroprint).** O tema não cria variação visual por categoria; só modula a tendência cromática (ver arquitetura, seção 11).
5. **SEO e schema.org no próprio código do tema** (seção 9). Plugin de SEO é opcional e complementar.
6. **Captação Substack nativa**, sem iframe/embed (seção 10).
7. **Mínimo de plugins.** Formulário de contato é custom no tema. Nada de plugin que injete CSS/markup próprio na página.
8. **Fontes self-hosted** (Newsreader, IBM Plex Sans, IBM Plex Mono) via `@font-face` — sem chamar Google Fonts em runtime (performance + privacidade).
9. **Placeholder-first.** O tema nunca depende de um post específico existir. Estados vazios são tratados com elegância.
10. **Só o tema migra para produção.** O banco local (SQLite do Studio) não é migrado; o conteúdo real é criado direto na Hostgator.

---

## 3. Ambiente e fluxo de trabalho

**Local (desenvolvimento):**
1. **LocalWP** cria o site local com MySQL real, PHP e SSL configurados automaticamente. No painel do LocalWP, clicar em **Go to site folder** para abrir a pasta do site.
2. O WordPress fica em `app\public\`. O tema fica em `app\public\wp-content\themes\brunoanastassakis-codice\`. Essa pasta **é o repositório Git** e é o que se abre como Project no Antigravity.
3. Edição via Antigravity (agentes). Preview e teste pelo navegador (LocalWP inicia o servidor local automaticamente).
4. WP-CLI disponível via **Open site shell** no LocalWP — útil para tarefas de linha de comando.
5. Banco de dados acessível via **Adminer** (botão no painel do LocalWP) — útil para inspeção e exportação futura.

**Produção (Hostgator):**
1. Gerar o ZIP do tema (script de build, seção 12) — só os arquivos do tema, sem `docs/`, `.git`, `*.md` de dev.
2. Hostgator → WP Admin → Aparência → Temas → Adicionar novo → Enviar tema → upload do ZIP → Ativar. (Alternativa: cPanel/FTP para `wp-content/themes/`.)
3. Configurar no WP de produção: permalinks "Nome do post"; página inicial estática apontando para a home; menu de 4 itens; as 5 categorias oficiais com slug e descrição (subtítulo) — Conteúdo (`conteudo`), Comunicação (`comunicacao`), Eventos (`eventos`), IA (`ia`) e Ecossistema (`ecossistema`); páginas Sobre e Contato.
4. **Nesta fase, só o tema migra.** O conteúdo é criado direto na produção (placeholder-first). Quando houver conteúdo real, o banco MySQL local pode ser exportado via Adminer ou WP-CLI e importado diretamente na Hostgator — MySQL para MySQL, sem conversão.
5. Validar entrega de e-mail do formulário de contato — a Hostgator pode exigir SMTP em vez de `mail()` do PHP. **[PENDENTE técnico]**

---

## 4. Estrutura de arquivos do projeto

O repositório **é a pasta do tema**. Arquivos de orquestração de IA e documentação ficam na raiz e são excluídos do pacote de produção pelo `.distignore`.

```
brunoanastassakis-codice/              ← repo Git = pasta do tema (wp-content/themes/)
│
│  ── Orquestração de IA (raiz) ──
├── AGENTS.md                          ← regras canônicas (fonte da verdade)
├── CLAUDE.md                          ← Claude Code herda AGENTS.md
├── GEMINI.md                          ← Gemini herda AGENTS.md
├── README.md
├── .gitignore
├── .editorconfig
├── .distignore                        ← exclusões do ZIP de produção
│
│  ── Documentação (insumo dos agentes) ──
├── docs/
│   ├── 00-arquitetura.md
│   ├── 01-briefing-programacao.md     ← este arquivo
│   ├── 02-codice-tokens.md
│   ├── codice-designsystem.html       ← referência visual completa
│   └── referencia/                    ← fonte factual (fase de conteúdo; fora do build)
│       ├── BRUNO_ANASTASSAKIS_CV_2026_PT.docx
│       ├── BRUNO_ANASTASSAKIS_CV_2026_EN.docx
│       ├── BRUNO_ANASTASSAKIS_LINKEDIN_PT.docx
│       └── BRUNO_ANASTASSAKIS_LINKEDIN_EN.docx
│
│  ── Tema WordPress (clássico, PHP) ──
├── style.css                          ← header do tema + reset mínimo
├── theme.json                         ← paleta/tipografia para o editor de blocos
├── functions.php                      ← bootstrap: inclui inc/*
├── index.php                          ← fallback obrigatório
├── front-page.php                     ← home editorial
├── home.php                           ← acervo / listagem de artigos
├── single.php                         ← artigo individual
├── category.php                       ← arquivo por categoria
├── page-sobre.php                     ← página Sobre
├── page-contato.php                   ← página Contato
├── search.php                         ← resultados de busca
├── 404.php                            ← erro
├── header.php
├── footer.php
├── inc/
│   ├── setup.php                      ← supports, menus, image sizes
│   ├── enqueue.php                    ← CSS/JS/fontes
│   ├── categories.php                 ← slugs + subtítulos das 5 categorias
│   ├── seo.php                        ← <title>, meta description, Open Graph
│   ├── schema.php                     ← JSON-LD Article/BlogPosting + WebSite
│   └── contact-form.php              ← handler do formulário de contato
├── template-parts/
│   ├── card-article.php               ← cartão de artigo (acervo, recentes)
│   ├── content-single.php             ← corpo do artigo
│   ├── author-block.php               ← bloco "sobre o autor" (home)
│   ├── related-posts.php              ← leitura relacionada (3 itens)
│   └── newsletter-substack.php        ← captação Substack nativa
└── assets/
    ├── css/
    │   ├── tokens.css                 ← custom properties do Códice
    │   └── main.css                   ← estilos do tema
    ├── fonts/                         ← .woff2 self-hosted
    ├── js/
    │   └── main.js                    ← mínimo (menu mobile, nada pesado)
    └── img/                           ← imagens retroprint + placeholders
```

---

## 5. Especificação dos templates

Regras gerais: HTML5 semântico (`<header> <main> <article> <nav> <footer>`); um único `<h1>` por página; sem `<table>` para layout; nada de CSS inline exceto variáveis dinâmicas pontuais; toda string traduzível via funções de i18n do WP (`esc_html_e` etc.), text domain `codice`.

| Template | Conteúdo e regras |
| --- | --- |
| `header.php` | `<head>` com charset, viewport, hooks de SEO/schema; skip-link "Pular para o conteúdo"; cabeçalho com nome/logotipo textual e menu de 4 itens (Início, Artigos, Sobre, Contato). Sem barra de busca dominante no topo. |
| `front-page.php` | 7 blocos da home na ordem da arquitetura (seção 7): abertura editorial; imagem editorial; artigo em destaque (post fixado, com fallback ao mais recente); artigos recentes; eixos editoriais (5 categorias); bloco do autor; captação Substack. Cada bloco é um `template-part` ou seção própria. Estado vazio elegante quando não há posts. |
| `home.php` | Acervo: listagem cronológica reversa via `card-article.php`; filtro por categoria (links para `category`); busca simples; post fixado opcional no topo; paginação numerada (`paginate_links`). Sem scroll infinito, sem filtro por tag/data. |
| `single.php` | Título; subtítulo/resumo opcional; categoria; data; imagem de abertura (featured, opcional); corpo via `content-single.php`; `related-posts.php`; captação Substack inline; links internos. Schema Article/BlogPosting. |
| `category.php` | Cabeçalho com nome + subtítulo da categoria (da descrição); listagem com `card-article.php`; paginação. |
| `page-sobre.php` | Estrutura da arquitetura (seção 12): bio, tese, trajetória, áreas, prova discreta **por escopo, sem nomear empregadores**, link LinkedIn, link contato. |
| `page-contato.php` | Formulário custom (Nome, E-mail, Contexto, Mensagem) com nonce + honeypot; abaixo, link LinkedIn e e-mail direto. Sem linguagem comercial. |
| `search.php` | Reusa `card-article.php`; mensagem clara quando não há resultados. |
| `404.php` | Mensagem sóbria + link para acervo e home. Sem humor forçado. |
| `index.php` | Fallback que cobre qualquer caso não tratado, reusando os parts. |

---

## 6. Tokens do Códice → CSS (`assets/css/tokens.css`)

Fonte: docs/02-codice-tokens.md (extraído do Códice v6). Todo o tema referencia estas variáveis; nenhum valor cru no código.

```css
:root{
  /* Paleta de interface */
  --bone:#FBFAF6;        /* fundo principal (papel) */
  --vellum:#F4F2EB;      /* fundo secundário */
  --dustcover:#ECEADF;   /* fundo terciário */
  --graphite:#191A1F;    /* texto principal */
  --slate:#3D3F46;       /* texto secundário */
  --pencil:#7A7B82;      /* texto terciário / metadados */
  --petroleum:#0F4346;   /* acento: link, estado, ênfase */
  --abyss:#093033;       /* acento hover */
  --margin:#DDD8CC;      /* borda suave */
  --brass:#A88A4E;       /* detalhe decorativo — só filetes */

  /* Filetes */
  --rule:#191A1F18;
  --rule-strong:#191A1F30;

  /* Tipografia */
  --serif:"Newsreader","Source Serif Pro",Georgia,"Times New Roman",serif;
  --sans:"IBM Plex Sans",ui-sans-serif,system-ui,-apple-system,"Segoe UI",Helvetica,Arial,sans-serif;
  --mono:"IBM Plex Mono",ui-monospace,"SF Mono",Menlo,Consolas,monospace;

  /* Layout */
  --radius:6px;
  --space-section:clamp(76px,10vw,124px);

  /* Foco (acessibilidade) */
  --focus-ring:var(--petroleum);
}
```

**Regras de uso (os agentes devem respeitar):**
- Petroleum é o **único** acento funcional (link, hover→Abyss, ênfase). Brass só em filetes/detalhes de baixa frequência — nunca botão, nunca fundo.
- Hierarquia vem de tipografia (família, peso, tamanho, ritmo), não de cor.
- Serif (Newsreader) em títulos/ledes/citações; Sans (IBM Plex Sans) em corpo/navegação; Mono (IBM Plex Mono, uppercase) em datas/metadados/captions/labels de seção.
- Sem estouro horizontal: grids com `minmax(0,1fr)`, filhos com `min-width:0`, mídia com `max-width:100%`. Breakpoints 1280 / 980 / 880 / 560–360px.

---

## 7. Fontes self-hosted

Baixar os `.woff2` de Newsreader, IBM Plex Sans e IBM Plex Mono (licenças OFL) para `assets/fonts/`. Declarar via `@font-face` com `font-display:swap`. Carregar só os pesos usados (ex.: Newsreader 400/500/600 + itálico; Plex Sans 400/500; Plex Mono 400). Pré-carregar (`<link rel=preload>`) apenas a fonte do título acima da dobra. Não enfileirar Google Fonts.

---

## 8. `theme.json` (mínimo, para o editor de blocos)

Mesmo sendo tema clássico, o `theme.json` alinha o editor (Gutenberg) ao Códice quando os artigos forem escritos. Definir: a paleta (mesmos hex acima, nomes Códice), as 3 famílias tipográficas, escala de tamanhos, `contentSize`/`wideSize` para a largura editorial; **desabilitar** custom colors, custom gradients e custom font sizes para impedir que o conteúdo fuja do sistema. `version` compatível com o WP de destino.

---

## 9. SEO e schema (`inc/seo.php`, `inc/schema.php`)

**SEO (`seo.php`):** `<title>` específico por contexto (via `add_theme_support('title-tag')` + filtro `document_title_parts`); meta description por página/post (excerpt ou campo próprio, com fallback); Open Graph e Twitter Card básicos; canonical; um `<h1>` por página. Sem keyword stuffing.

**Schema (`schema.php`):** JSON-LD injetado no `wp_head`/`wp_footer`. `WebSite` + `Person` (Bruno, sem dados sensíveis — nome, URL, sameAs LinkedIn) na home; `Article`/`BlogPosting` no single (headline, datePublished, dateModified, author, image, articleSection = categoria). `BreadcrumbList` onde houver breadcrumbs. Validar com o Rich Results Test depois.

Plugin de SEO: opcional. Se instalado, **não pode duplicar** title/description/schema já emitidos pelo tema — escolher um dos dois como autoridade (preferência: o tema).

---

## 10. Captação Substack (`template-parts/newsletter-substack.php`)

Componente nativo, estilizado no padrão Códice (label em Mono, campo sóbrio, botão Petroleum, filete fino). **Sem iframe/embed do Substack.** Markup próprio: `<form>` com input de e-mail + botão. Reutilizado na home, no rodapé e no fim do artigo.

Mecanismo de envio (a validar na implementação): (a) `action` do form apontando para o endpoint de inscrição do Substack, ou (b) redirecionar para a página de inscrição do Substack com o e-mail pré-preenchido na query string. Implementar como função única e parametrizável para trocar de mecanismo sem mexer no markup. Acessibilidade: `<label>` associado, mensagem de erro/sucesso textual, foco visível.

---

## 11. Acessibilidade e performance (requisitos)

**Acessibilidade:** skip-link; foco visível (outline Petroleum 2px com offset); links de texto sublinhados; contraste adequado entre Graphite/Slate/Pencil sobre Bone/Vellum; `alt` obrigatório descrevendo tema + tratamento retroprint; `prefers-reduced-motion` respeitado; navegação por teclado; landmarks ARIA corretos.

**Performance:** CSS enxuto e enfileirado corretamente; JS mínimo e adiado; imagens responsivas (`srcset`, `loading=lazy` exceto a da dobra); `width`/`height` para evitar layout shift; sem bibliotecas pesadas; meta de boa pontuação em mobile.

---

## 12. Build e exclusões de produção

`.distignore` (o que **não** vai no ZIP de produção):
```
.git
.gitignore
.editorconfig
.distignore
AGENTS.md
CLAUDE.md
GEMINI.md
README.md
docs/
node_modules/
*.map
.DS_Store
```

Script de empacotamento (ex.: `bin/build-zip.sh` ou tarefa pedida ao agente): copiar o tema para uma pasta temporária, remover tudo do `.distignore`, gerar `brunoanastassakis-codice.zip`. O ZIP é o que sobe na Hostgator.

---

## 13. Ordem de execução (para os agentes, em marcos)

**Etapa 1 — Esqueleto:** `style.css` (header), `functions.php`, `inc/setup.php`, `inc/enqueue.php`, `tokens.css`, fontes, `header.php`, `footer.php`, `index.php`. Tema ativável e renderizando.

**Etapa 2 — Sistema visual:** `main.css` aplicando tokens (tipografia, ritmo, filetes, grid, foco). Validar tipografia e espaçamento com placeholder.

**Etapa 3 — Listagens:** `card-article.php`, `home.php`, `category.php`, `search.php`, paginação, `inc/categories.php` (5 categorias + subtítulos).

**Etapa 4 — Conteúdo:** `single.php`, `content-single.php`, `related-posts.php`, `front-page.php` (7 blocos), `author-block.php`.

**Etapa 5 — Páginas e captação:** `page-sobre.php`, `page-contato.php` + `inc/contact-form.php`, `newsletter-substack.php`, `404.php`.

**Etapa 6 — SEO/schema/perf/a11y:** `inc/seo.php`, `inc/schema.php`, `theme.json`, revisão de acessibilidade e performance.

**Etapa 7 — Build:** `.distignore` + script de ZIP; rodar checklist técnico (arquitetura, seção 18.1); exportar e instalar na Hostgator.

Cada etapa: o agente implementa, mostra o resultado no preview do Studio, valida o checklist parcial, faz commit. Uma etapa por vez.

---

## 14. O que os agentes NÃO devem fazer

- Inventar conteúdo profissional (clientes, métricas, cargos, eventos). Placeholder é placeholder neutro.
- Nomear empregadores na página Sobre.
- Criar variação de estilo de imagem por categoria.
- Usar embed/iframe do Substack.
- Instalar page builder, framework CSS ou plugin que injete markup/estilo na página.
- Hardcodar cores/fontes/espaçamentos fora dos tokens.
- Criar templates ou seções fora do escopo da v1 (portfólio, serviços, cases, currículo, landing comercial, tags).
- Adicionar dependências pesadas (jQuery extra, sliders, animações) sem necessidade.
- Quebrar acessibilidade ou estourar horizontalmente em qualquer breakpoint.
