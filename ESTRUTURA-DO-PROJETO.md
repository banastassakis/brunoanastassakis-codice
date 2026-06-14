# Estrutura exata do projeto (local)

O repositório **é a pasta do tema**, dentro da instalação WordPress do LocalWP. Caminho local:

```
D:\BA\Sites\brunoanastassakis\app\public\wp-content\themes\brunoanastassakis-codice\
```

> **Estrutura do LocalWP:** o site fica em `<pasta-do-site>\app\public\`. O WordPress core (`wp-admin`, `wp-includes`, `wp-config.php`) fica em `app\public\`. O tema fica em `app\public\wp-content\themes\`. Para navegar até lá, abra o LocalWP → clique em **Go to site folder** → entre em `app\public\wp-content\themes\`.

Abra **só a pasta `brunoanastassakis-codice\`** como Project no Antigravity — não a raiz do site, não `app\public\`.

Legenda: **[VOCÊ]** = você coloca/cria agora, manualmente · **[AGENTES]** = os agentes geram durante a programação.

```
brunoanastassakis-codice/                      ← repo Git = pasta do tema
│
│  ── Orquestração de IA (raiz) ──
├── AGENTS.md                                  [VOCÊ]  regras canônicas
├── CLAUDE.md                                  [VOCÊ]  herda AGENTS.md
├── GEMINI.md                                  [VOCÊ]  herda AGENTS.md
├── README.md                                  [VOCÊ]
├── .gitignore                                 [VOCÊ]
├── .editorconfig                              [VOCÊ]
├── .distignore                                [VOCÊ]
│
│  ── Documentação e referência ──
├── docs/
│   ├── 00-arquitetura.md                      [VOCÊ]  (renomear o doc de arquitetura)
│   ├── 01-briefing-programacao.md             [VOCÊ]
│   ├── 02-codice-tokens.md                    [VOCÊ]
│   ├── codice-designsystem.html               [VOCÊ]  (o upload que você já tem)
│   └── referencia/                            [VOCÊ]  fonte factual; fora do build
│       ├── BRUNO_ANASTASSAKIS_CV_2026_PT.docx
│       ├── BRUNO_ANASTASSAKIS_CV_2026_EN.docx
│       ├── BRUNO_ANASTASSAKIS_LINKEDIN_PT.docx
│       └── BRUNO_ANASTASSAKIS_LINKEDIN_EN.docx
│
│  ── Tema WordPress (clássico, PHP) ──
├── style.css                                  [AGENTES]  header do tema + reset
├── theme.json                                 [AGENTES]  paleta/tipografia p/ o editor
├── functions.php                              [AGENTES]  bootstrap: inclui inc/*
├── index.php                                  [AGENTES]  fallback obrigatório
├── front-page.php                             [AGENTES]  home editorial
├── home.php                                   [AGENTES]  acervo / listagem
├── single.php                                 [AGENTES]  artigo individual
├── category.php                               [AGENTES]  arquivo por categoria
├── page-sobre.php                             [AGENTES]  página Sobre
├── page-contato.php                           [AGENTES]  página Contato
├── search.php                                 [AGENTES]  resultados de busca
├── 404.php                                    [AGENTES]  erro
├── header.php                                 [AGENTES]
├── footer.php                                 [AGENTES]
├── inc/                                       [AGENTES]
│   ├── setup.php                              supports, menus, image sizes
│   ├── enqueue.php                            CSS/JS/fontes
│   ├── categories.php                         slugs + subtítulos das 5 categorias
│   ├── seo.php                                title, meta description, Open Graph
│   ├── schema.php                             JSON-LD Article/BlogPosting + WebSite
│   └── contact-form.php                       handler do formulário de contato
├── template-parts/                            [AGENTES]
│   ├── card-article.php                       cartão de artigo
│   ├── content-single.php                     corpo do artigo
│   ├── author-block.php                       bloco "sobre o autor" (home)
│   ├── related-posts.php                      leitura relacionada (3 itens)
│   └── newsletter-substack.php                captação Substack nativa
└── assets/                                    [AGENTES]
    ├── css/
    │   ├── tokens.css                         custom properties do Códice
    │   └── main.css                           estilos do tema
    ├── fonts/                                 .woff2 self-hosted (você baixa os arquivos)
    ├── js/
    │   └── main.js                            mínimo (menu mobile)
    └── img/                                   imagens retroprint + placeholders
```

## Passo a passo para montar agora

1. Instalar o LocalWP e criar o site local `brunoanastassakis`.
2. No painel do LocalWP, clicar em **Go to site folder** → entrar em `app\public\wp-content\themes\`.
3. Descompactar o ZIP do projeto aqui — cria a pasta `brunoanastassakis-codice\`.
4. Abrir **só** `brunoanastassakis-codice\` como Project no Antigravity.
5. `git init` dentro dessa pasta.
6. A partir daí, os agentes geram os arquivos **[AGENTES]** seguindo a ordem de execução do briefing (seção 13), uma etapa por vez.

## Notas

- A pasta `assets/fonts/` você popula com os `.woff2` de Newsreader, IBM Plex Sans e IBM Plex Mono (licenças OFL). Os agentes declaram o `@font-face`, mas os arquivos de fonte você baixa.
- `docs/` inteira (incluindo `referencia/`) está no `.distignore` — não vai para o ZIP de produção.
- Só o tema migra para a Hostgator. O conteúdo real é criado direto na produção (placeholder-first).
