# Checklist de instalacao e configuracao manual

Checklist tecnico para instalar o tema `brunoanastassakis-codice` no WP Admin da Hostgator ou em ambiente local. Esta etapa e manual: nao criar deploy automatico, nao migrar banco e nao publicar conteudo real.

## 1. Instalacao do tema

- [ ] Acessar `Aparencia -> Temas -> Adicionar novo -> Enviar tema`.
- [ ] Enviar manualmente o arquivo `brunoanastassakis-codice.zip`.
- [ ] Ativar o tema `Códice`.
- [ ] Confirmar que nenhum plugin, page builder, starter theme ou framework CSS foi instalado para substituir o tema.

## 2. Configuracao basica do WordPress

- [ ] Em `Configuracoes -> Links permanentes`, selecionar `Nome do post` e salvar.
- [ ] Criar/configurar manualmente as paginas abaixo, com texto neutro de placeholder quando necessario:
  - [ ] `Inicio`
  - [ ] `Artigos`
  - [ ] `Sobre`
  - [ ] `Contato`
- [ ] Em `Configuracoes -> Leitura`, configurar pagina inicial estatica conforme necessario:
  - [ ] `Pagina inicial`: `Inicio`
  - [ ] `Pagina de posts`: `Artigos`
- [ ] Confirmar que a pagina `Sobre` usa o slug `sobre`.
- [ ] Confirmar que a pagina `Contato` usa o slug `contato`.
- [ ] Confirmar que a pagina `Artigos` usa o slug `artigos`.

## 3. Menu primario

- [ ] Criar um menu primario em `Aparencia -> Menus`.
- [ ] Atribuir o menu a localizacao `Menu principal`.
- [ ] Manter apenas quatro itens, nesta ordem:
  - [ ] Inicio
  - [ ] Artigos
  - [ ] Sobre
  - [ ] Contato
- [ ] Nao adicionar portfolio, servicos, cases, clientes, curriculo, landing comercial, tags ou paginas fora da v1.

## 4. Categorias editoriais

Criar/configurar manualmente as cinco categorias fixas. Usar as descricoes abaixo como subtitulos no WordPress.

- [ ] `Conteúdo`
  - Slug: `conteudo`
  - Descricao: `Produto, estrutura e governança editorial.`
- [ ] `Comunicação`
  - Slug: `comunicacao`
  - Descricao: `Linguagem, posicionamento e construção de autoridade.`
- [ ] `Eventos`
  - Slug: `eventos`
  - Descricao: `Curadoria, formatos e circulação de ideias.`
- [ ] `IA`
  - Slug: `ia`
  - Descricao: `Automação, inteligência operacional e fluxos de conteúdo.`
- [ ] `Ecossistema`
  - Slug: `ecossistema`
  - Descricao: `Integração entre conteúdo, comunicação, canais, eventos, IA e operação.`



## 5. Validacao de rotas

Validar sem criar conteudo real. Posts de placeholder podem ser usados apenas para testar renderizacao.

- [ ] `/`
- [ ] `/artigos`
- [ ] `/sobre`
- [ ] `/contato`
- [ ] Arquivos das cinco categorias
- [ ] Busca
- [ ] Artigo individual
- [ ] Pagina 404

Se `/sobre` ou `/contato` retornarem 404, verificar primeiro se as paginas existem no banco e se os slugs estao corretos. Se `/artigos` nao renderizar o acervo, verificar a configuracao de `Pagina de posts` em `Configuracoes -> Leitura`.

## 6. Validacao tecnica visual e HTML

- [ ] Confirmar que nao ha iframes ou embeds do Substack.
- [ ] Confirmar meta description unica por rota verificada.
- [ ] Confirmar um unico `<h1>` por pagina.
- [ ] Confirmar existencia de `main#conteudo`.
- [ ] Confirmar skip-link apontando para `#conteudo`.
- [ ] Validar home mobile em 360px sem estouro horizontal.
- [ ] Confirmar que as fontes reais `.woff2` existem em `assets/fonts/`.
- [ ] Confirmar que nao ha chamadas runtime para Google Fonts.

## 7. Formulario de contato e lancamento futuro

- [ ] Validar envio real do formulario de contato na Hostgator.
- [ ] Registrar se `wp_mail()` funciona no ambiente final.
- [ ] Configurar SMTP somente se a Hostgator exigir para entrega confiavel.
- [ ] Manter imagem editorial definitiva para marco posterior.
- [ ] Manter conteudo real para marco posterior.
- [ ] Nao inventar conteudo profissional, clientes, metricas, cargos, resultados, eventos ou ferramentas.
- [ ] Nao nomear empregadores na pagina Sobre.
