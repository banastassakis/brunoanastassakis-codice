# AGENTS.md — Regras do projeto (fonte canônica)

Este arquivo governa **todos os agentes de IA** que trabalham neste repositório (Codex, Claude Code, Gemini e quaisquer outros). `CLAUDE.md` e `GEMINI.md` herdam estas regras. Em caso de conflito, este arquivo prevalece. Idioma de trabalho: **português do Brasil**. Código, identificadores e comentários técnicos em inglês quando for convenção; textos de UI em pt-BR.

## 1. O que é este projeto

Tema WordPress **clássico em PHP** para o site pessoal de Bruno Anastassakis (brunoanastassakis.com) — uma **publicação editorial autoral**, não portfólio, currículo ou landing comercial. O tema materializa o Design System "Códice v6". Desenvolvimento local com **LocalWP**; produção na **Hostgator** (instalação do tema via ZIP).

Documentos de referência (leia antes de agir):
- `docs/00-arquitetura.md` — arquitetura editorial e estrutural (o quê e por quê).
- `docs/01-briefing-programacao.md` — especificação técnica (como).
- `docs/02-codice-tokens.md` — tokens visuais (cores, tipografia, layout).
- `docs/codice-designsystem-v6.html` — referência visual completa.

Material de referência factual (`docs/referencia/`): CV (PT/EN) e perfis de LinkedIn (PT/EN) de Bruno. **São fonte de verdade factual apenas para a fase de conteúdo** (página Sobre, bios). Regras de uso:
- **Não usar na fase atual (Marco 1, placeholder).** O tema não puxa nem exibe dados desses arquivos agora.
- Quando a página Sobre/bio for escrita: usar como referência de escopo e trajetória, mas **não nomear empregadores** (a Sobre descreve só escopo) e **não inventar nada além** do que consta nesses arquivos.
- Nunca empacotar `docs/referencia/` no tema de produção (está no `.distignore`).

## 2. Regras invioláveis

1. **Não inventar conteúdo profissional**: clientes, métricas, resultados, cargos, prêmios, eventos ou ferramentas. Textos e dados são **placeholder neutro** até a fase de produção de conteúdo.
2. **Página Sobre não nomeia empregadores** — descreve apenas escopo de atuação. Detalhe nominal fica no LinkedIn/CV.
3. **Estilo de imagem único (retroprint).** Não criar variação de estilo por categoria; no máximo modular a tendência cromática descrita na arquitetura. Nunca usar estética SaaS, dashboard, stock corporativo, neon, IA futurista clichê, corporate memphis, 3D glossy. Nunca texto/logos dentro das imagens.
4. **Captação de newsletter via Substack nativa** — campo de e-mail próprio estilizado no padrão Códice. **Proibido** iframe/embed do Substack.
5. **Tokens são lei.** Toda cor, fonte e espaçamento vem das custom properties do Códice (`assets/css/tokens.css`). Nenhum valor cru no código.
6. **Petroleum é o único acento funcional** (link, hover→Abyss, ênfase). **Brass só em filetes** — nunca botão, nunca fundo.
7. **Escopo v1 fechado.** Não criar portfólio, serviços, cases, clientes, currículo online, landing comercial, tags do WordPress, calendário editorial. Menu tem 4 itens: Início, Artigos, Sobre, Contato. 5 categorias fixas: Conteúdo, Comunicação, Eventos, IA, Ecossistema.
8. **Sem page builder, sem framework CSS, sem starter theme.** CSS próprio. Sem Tailwind/Bootstrap/Elementor/Divi.
9. **Mínimo de plugins.** Formulário de contato é custom no tema. Nenhum plugin que injete markup/estilo na página.
10. **Placeholder-first.** O tema nunca pode quebrar por falta de um post específico. Todo estado vazio é tratado com elegância.

## 3. Padrões de código

**PHP / WordPress**
- Tema **clássico** (templates PHP), não block theme/FSE. `theme.json` existe só para alinhar o editor de blocos à paleta/tipografia.
- Seguir os WordPress Coding Standards (PHP). Indentação com tabs em PHP.
- **Escape sempre na saída**: `esc_html`, `esc_attr`, `esc_url`, `wp_kses_post`. **Sanitizar toda entrada.**
- Toda string de UI traduzível: `esc_html_e('...', 'codice')`, text domain `codice`.
- Nada de queries SQL cruas; usar `WP_Query` e APIs do WP. Sem lógica pesada nos templates — extrair para `inc/`.
- `functions.php` apenas faz bootstrap (inclui `inc/*`). Cada responsabilidade em seu arquivo de `inc/`.
- Prefixo de funções/hooks/handles: `codice_`.
- Formulários: `wp_nonce_field` + verificação de nonce + honeypot anti-spam.

**CSS**
- Custom properties do Códice; sem valores hardcoded. Sem `!important` exceto justificado.
- Mobile-first; sem estouro horizontal em nenhum breakpoint (1280/980/880/560–360px). Grids `minmax(0,1fr)`, filhos `min-width:0`, mídia `max-width:100%`.
- Hierarquia por tipografia, não por cor. Foco visível (outline Petroleum 2px + offset). Links de texto sublinhados.

**JS**
- Mínimo e adiado. Sem jQuery novo, sem bibliotecas pesadas, sem sliders/animações sem necessidade. Respeitar `prefers-reduced-motion`.

**Acessibilidade e performance**
- HTML5 semântico, um `<h1>` por página, landmarks corretos, skip-link, `alt` descritivo (tema + tratamento retroprint). Imagens com `srcset`, `width`/`height`, `loading=lazy` (exceto a da dobra). Fontes self-hosted `.woff2`, `font-display:swap`.

## 4. Fluxo de trabalho exigido

- **Uma etapa por vez** (ver ordem no briefing, seção 13). Não pular etapas nem gerar o tema inteiro num único passo.
- **Plano antes de código**: para qualquer tarefa não trivial, apresentar o plano e os arquivos que serão tocados antes de escrever.
- **Validar no preview do Studio** após cada etapa; conferir o checklist parcial.
- **Commits pequenos e descritivos** em pt-BR, no presente do indicativo (ex.: "adiciona template de artigo individual"). Não misturar etapas no mesmo commit.
- **Não editar** `docs/`, `style.css` header de forma destrutiva, nem arquivos fora do escopo da tarefa atual sem avisar.
- Ao terminar uma etapa, **resumir o que mudou** e qual o próximo passo sugerido.

## 5. Limites

- Não instalar dependências, plugins ou temas sem necessidade explícita e aprovação.
- Não rodar comandos destrutivos (apagar banco, resetar site) sem confirmação.
- Não publicar, fazer deploy ou subir nada para a Hostgator automaticamente — deploy é manual.
- Não tocar em `.git` de forma destrutiva (rebase forçado, reset hard) sem pedir.
- Diante de ambiguidade que impeça uma boa decisão, **perguntar** em vez de assumir; caso contrário, avançar com a hipótese mais razoável e declará-la.
