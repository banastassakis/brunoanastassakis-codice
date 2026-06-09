# GEMINI.md

**A fonte canônica de regras deste projeto é o `AGENTS.md`. Leia-o por completo e siga-o à risca antes de qualquer ação.** Este arquivo apenas reforça o essencial para o Gemini; não substitui o `AGENTS.md`.

Leia também, antes de codar: `docs/00-arquitetura.md`, `docs/01-briefing-programacao.md` e `docs/02-codice-tokens.md`.

## Contexto em uma linha
Tema WordPress **clássico em PHP** para uma **publicação editorial autoral** (brunoanastassakis.com), materializando o Design System "Códice v6". Dev local no WordPress Studio; produção na Hostgator via ZIP.

## Salvaguarda — regras críticas (detalhe no AGENTS.md)
- **Não inventar** clientes, métricas, cargos, eventos ou ferramentas. Conteúdo é **placeholder neutro**.
- Página **Sobre não nomeia empregadores** (só escopo).
- **Estilo de imagem único (retroprint)**; sem variação por categoria; sem estética SaaS/stock/neon; sem texto/logo dentro de imagem.
- Newsletter **Substack nativo**, sem iframe/embed.
- **Tokens do Códice são lei**: nenhuma cor/fonte/espaçamento fora de `assets/css/tokens.css`. Petroleum é o único acento; Brass só em filetes.
- **Escopo v1 fechado**: 4 itens de menu, 5 categorias oficiais (Conteúdo, Comunicação, Eventos, IA, Ecossistema); nada de portfólio/serviços/cases/landing.
- **Sem page builder, framework CSS ou starter.** Mínimo de plugins; formulário de contato custom.
- **Placeholder-first**: o tema nunca quebra por falta de um post.

## Como trabalhar
- **Uma etapa por vez** (ordem no briefing, seção 13). Plano antes do código; declarar os arquivos a tocar.
- Padrões WordPress: escapar saída, sanitizar entrada, i18n com text domain `codice`, prefixo `codice_`, lógica em `inc/` e não nos templates.
- Acessibilidade e performance não são opcionais (skip-link, foco visível, `alt`, sem estouro horizontal, fontes self-hosted).
- Validar no preview do Studio ao fim de cada etapa; commits pequenos em pt-BR; resumir mudanças e próximo passo.
- **Nunca** fazer deploy/upload para a Hostgator automaticamente nem rodar comandos destrutivos sem confirmação.
- Em dúvida que impeça boa decisão, perguntar; senão, avançar declarando a hipótese.
