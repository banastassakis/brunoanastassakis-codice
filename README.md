# Códice — Tema WordPress de brunoanastassakis.com

Tema clássico em PHP para a publicação editorial autoral de Bruno Anastassakis, materializando o Design System "Códice v6". Desenvolvido localmente no WordPress Studio e instalado na Hostgator via ZIP.

Este repositório **é a pasta do tema** (`wp-content/themes/brunoanastassakis-codice/`). Os arquivos de orquestração de IA e a documentação ficam na raiz e são excluídos do pacote de produção.

## Documentação

- `docs/00-arquitetura.md` — arquitetura editorial e estrutural (o quê e por quê).
- `docs/01-briefing-programacao.md` — especificação técnica de programação (como).
- `docs/02-codice-tokens.md` — tokens visuais (cores, tipografia, layout).
- `docs/codice-designsystem.html` — referência visual completa.
- `docs/referencia/` — CV (PT/EN) e LinkedIn (PT/EN). Fonte factual **só para a fase de conteúdo** (Sobre/bios); não usada na fase placeholder; fora do build de produção.

## Orquestração de IA (Antigravity)

- `AGENTS.md` — **fonte canônica** de regras para todos os agentes.
- `CLAUDE.md` / `GEMINI.md` — herdam o `AGENTS.md` (Claude Code / Gemini). Codex usa o `AGENTS.md`.

## Setup local (LocalWP)

1. Instalar o [LocalWP](https://localwp.com/) e criar um site local (ex.: `brunoanastassakis`).
2. No painel do LocalWP, clicar em **Go to site folder** para abrir a pasta do site no Explorer.
3. Navegar até `app\public\wp-content\themes\`.
4. Descompactar o ZIP do projeto dentro de `themes\` — vai criar a pasta `brunoanastassakis-codice\`.
5. Abrir **só a pasta `brunoanastassakis-codice\`** como Project no Antigravity.
6. Fazer `git init` dentro da pasta `brunoanastassakis-codice\`.
7. Ativar o tema no WP Admin do site local (Aparência → Temas).
8. Configurar: permalinks "Nome do post"; menu de 4 itens (Início, Artigos, Sobre, Contato); as 5 categorias oficiais (Conteúdo, Comunicação, Eventos, IA, Ecossistema) com slug e descrição (subtítulo); páginas Sobre e Contato; página inicial estática.

> O LocalWP usa MySQL real (não SQLite), o que facilita eventual migração do banco para a Hostgator. Como o projeto é placeholder-first, só o tema migra por enquanto — o banco fica de lado até a fase de produção de conteúdo.

## Build para produção (ZIP)

Gerar o ZIP só com os arquivos do tema (exclusões em `.distignore`). Sugestão de comando manual:

```bash
rsync -a --exclude-from='.distignore' ./ /tmp/brunoanastassakis-codice/
cd /tmp && zip -r brunoanastassakis-codice.zip brunoanastassakis-codice -x '*.git*'
```

(Ou peça ao agente para criar `bin/build-zip.sh` com essa lógica.)

## Deploy na Hostgator

1. WP Admin → Aparência → Temas → Adicionar novo → Enviar tema → upload do ZIP → Ativar.
   (Alternativa: cPanel/FTP para `wp-content/themes/`.)
2. Repetir a configuração de permalinks, menu, categorias e páginas no WP de produção.
3. **Só o tema migra nesta fase.** O conteúdo real é criado direto na produção (placeholder-first).
4. Quando chegar a fase de conteúdo, o banco MySQL local pode ser exportado via Adminer (LocalWP) ou WP-CLI e importado diretamente na Hostgator.
5. Validar a entrega de e-mail do formulário de contato — a Hostgator pode exigir SMTP.

## Arquivos de configuração

**`.gitignore`**
```
# WordPress / ambiente
wp-config.php
*.log
.DS_Store
Thumbs.db

# Dependências e build
node_modules/
vendor/
/tmp/
*.zip
*.map

# Editores
.vscode/
.idea/
```

**`.editorconfig`**
```
root = true

[*]
charset = utf-8
end_of_line = lf
insert_final_newline = true
trim_trailing_whitespace = true

[*.php]
indent_style = tab

[*.{css,js,json,md,yml}]
indent_style = space
indent_size = 2
```

**`.distignore`** (excluído do ZIP de produção)
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
vendor/
*.map
*.zip
.DS_Store
```

## Status

Fase atual: **Marco 1 — programação do tema com conteúdo placeholder.** Conteúdo real e lançamento público são marcos posteriores (ver arquitetura, seções 16 e 20).
