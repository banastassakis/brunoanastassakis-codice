# Arquitetura Editorial e Estrutural — Site Pessoal Bruno Anastassakis

*Documento de arquitetura. Insumo direto para a fase seguinte: design e programação do tema WordPress. Não contém wireframes, calendário editorial nem textos finais de artigos.*

**Domínio:** brunoanastassakis.com · **Plataforma:** WordPress · **Hospedagem:** Hostgator · **Tema:** próprio, criado do zero · **Versão da arquitetura:** final

---

## 1. Título do documento

**Arquitetura Editorial e Estrutural — Site Pessoal Bruno Anastassakis**

Consolidação estratégica, editorial, estrutural, funcional e visual-direcional do site. É a base sobre a qual o tema WordPress será desenhado e programado. Não é design final nem produção de conteúdo: define o que o site é, o que ele contém, como se estrutura e que regras visuais e editoriais o governam.

---

## 2. Premissa estratégica

O site é uma **publicação editorial autoral**. Não é portfólio, currículo online, landing page comercial ou página de serviços.

Sua função é publicar ideias sobre os domínios em que Bruno trabalha e pensa: conteúdo como produto editorial, comunicação como linguagem pública, eventos como circulação de ideias, IA como camada operacional e ecossistemas editoriais que integram canais, pessoas, decisões, fluxos e operação.

A autoridade vem das **ideias publicadas**. Trajetória, marcas atuais e entregas aparecem apenas como lastro de credibilidade — nunca como produto principal do site.

Frase interna de orientação (não para publicação):

> O site é a prova de que eu penso esses temas, não a vitrine de que eu os executo.

Princípio de relacionamento: o leitor deve virar **interlocutor antes de virar lead**. Toda conversão é discreta e consultiva.

---

## 3. Decisões centrais

Decisões já tomadas e inegociáveis nesta v1.

**Editoriais e de estrutura**

1. O site é uma publicação editorial autoral, orientada a texto.
2. Menu principal com **quatro itens apenas**: Início, Artigos, Sobre, Contato.
3. Cinco categorias editoriais fixas: Conteúdo, Comunicação, Eventos, IA, Ecossistema.
4. Newsletter **fora do menu**: aparece só como bloco discreto (home, rodapé e fim dos artigos).
5. Conversão discreta e consultiva — sem funil, sem CTA agressivo, sem linguagem comercial.
6. Imagem como **linguagem editorial e conceitual**, guiada pelo Códice — nunca decoração nem estética de landing page. Estilo de imagem único (retroprint) para toda a publicação (ver seção 11).
7. Estética proibida: startup, corporativo genérico, banco de imagem de escritório, pessoas em reunião, mockups de laptop, dashboards genéricos, ícones SaaS, visual de LinkedIn, excesso de cards coloridos, qualquer aparência de "consultor digital com blog".

**Técnicas**

8. **Tema próprio, criado do zero** para este projeto — não tema comercial nem starter genérico. O design vem dos tokens do Códice v6 (seção 11).
9. **SEO e schema.org no próprio código do tema**, seguindo as melhores práticas. Plugin de SEO é opcional, complementar e não pode duplicar o que o código entrega (seção 15).
10. **Newsletter via Substack**, com captação nativa estilizada no padrão Códice — sem o iframe/embed padrão do Substack (seção 7 e 14).
11. **Construção placeholder-first**: o tema é programado e validado com texto e imagens de placeholder. O conteúdo real entra depois, vindo de projetos separados (calendário editorial e produção). O lançamento público só ocorre com acervo real (seção 16 e 20).

---

## 4. Mapa do site

```
Site (publicação editorial autoral) — brunoanastassakis.com
│
├── Início (front-page)
│     ├── Abertura editorial curta
│     ├── Imagem editorial de abertura
│     ├── Artigo em destaque
│     ├── Artigos recentes
│     ├── Eixos editoriais (as 5 categorias)
│     ├── Bloco curto sobre o autor → Sobre
│     └── Chamada discreta (Substack / contato)
│
├── Artigos (acervo navegável)
│     ├── Listagem cronológica reversa
│     ├── Filtro por categoria
│     ├── Busca simples
│     └── Paginação numerada
│
├── Sobre (narrativa em primeira pessoa)
│
├── Contato (conversa consultiva discreta)
│
├── [Suporte] Arquivos por categoria (category)
│     ├── Conteúdo
│     ├── Comunicação
│     ├── Eventos
│     ├── IA
│     └── Ecossistema
│
├── [Suporte] Resultados de busca (search)
├── [Suporte] Artigo individual (single)
└── [Suporte] Página de erro (404)
```

Blocos funcionais fora do menu: captação Substack (home, rodapé, fim de artigo) e bloco "sobre o autor" na home. Nenhum deles é página própria na v1.

---

## 5. Páginas incluídas

| Página | Função | Acesso |
| --- | --- | --- |
| Início | Porta de entrada editorial e curadoria | Menu |
| Artigos | Acervo navegável da publicação | Menu |
| Sobre | Narrativa autoral e lastro de credibilidade | Menu |
| Contato | Abrir conversa consultiva | Menu |
| Arquivos por categoria | Navegação por eixo editorial | Link interno |
| Artigo individual | Leitura do conteúdo | Link interno |
| Busca | Recuperação de conteúdo | Interface |
| 404 | Erro / página inexistente | Sistema |

---

## 6. Páginas excluídas ou adiadas

**Excluídas da v1 (não construir agora):**
Portfólio, Serviços, Clientes, Cases, Currículo online, Consultoria, Projetos, Landing page comercial, Página de newsletter dedicada, Tags WordPress, Calendário editorial.

**Adiadas para fase 2 (avaliar depois, com volume real):**
Refinamento da leitura relacionada; séries editoriais como seção pública. *Landing de newsletter dedicada está descartada na v1* — com Substack e captação nativa, ela não se justifica.

Critério: nada que transforme o site em vitrine de serviço, currículo ou página de vendas entra na v1.

---

## 7. Estrutura da home

Blocos na ordem definida:

1. **Abertura editorial curta** — uma ou duas frases sobre o que é a publicação e por quem é escrita. *Evitar:* slogan, frase de efeito, manifesto, linguagem de campanha.
2. **Imagem editorial de abertura** — estabelece atmosfera visual e autoral, seguindo o Códice. *Evitar:* aparência de banner comercial.
3. **Artigo em destaque** — ponto de entrada curado; não precisa ser o mais recente. *Evitar:* destaque que nunca muda.
4. **Artigos recentes** — mostra atividade e profundidade.
5. **Eixos editoriais** — apresenta as cinco categorias como campos de pensamento, não como áreas de serviço.
6. **Bloco curto sobre o autor** — contexto e credibilidade em duas ou três linhas, com link para Sobre. *Evitar:* repetir o LinkedIn ou transformar marcas atuais em branding pessoal.
7. **Captação Substack discreta** — um campo de e-mail estilizado no padrão Códice para assinar a newsletter, mais um link de contato. *Evitar:* pop-up, CTA agressivo, promessa comercial, linguagem de funil, iframe/embed do Substack.

---

## 8. Estrutura da página Artigos

A página Artigos é o acervo navegável da publicação.

**Incluir:**
- listagem cronológica reversa;
- cartão por artigo com título, categoria, data e resumo curto;
- filtro apenas por categoria;
- busca simples e visível;
- artigo fixado opcional no topo;
- paginação clássica numerada.

**Não incluir:**
- scroll infinito;
- filtro por tag ou por data;
- ordenação por popularidade;
- excesso de metadados;
- layout com cara de portal de notícias genérico.

---

## 9. Estrutura do artigo individual

Estrutura funcional do single post, na ordem de leitura:

- título;
- subtítulo ou resumo curto (se necessário);
- categoria;
- data;
- imagem editorial de abertura (com função editorial, não capa decorativa);
- corpo do texto com tipografia confortável;
- headings bem hierarquizados;
- bloco discreto de leitura relacionada ao final;
- captação Substack inline discreta (mesmo componente nativo da home);
- links internos quando fizer sentido.

**Leitura relacionada:** 3 sugestões ao final, preferencialmente por categoria e recência.

---

## 10. Categorias editoriais finais

Cinco categorias principais, fixas. **Não criar sexta categoria**, tags públicas ou taxonomias novas na v1.

> *Nota: os subtítulos, slugs e funções editoriais abaixo são a decisão oficial da arquitetura. A "direção visual" descreve repertório temático de cada eixo — não um estilo próprio por categoria. O tratamento visual é único para as cinco: colagem editorial retroprint (seção 11).*

### 1. Conteúdo

- **Slug:** `conteudo`
- **Subtítulo (WordPress):** Produto, estrutura e governança editorial.
- **Função editorial:** conteúdo tratado como produto editorial: estrutura, acervo, governança, experiência, ciclo de vida e organização do conhecimento.
- **Tipo de artigo:** ensaios, análises e textos de método sobre como conteúdos, acervos, produtos editoriais e sistemas de conhecimento são pensados, estruturados e mantidos.
- **Risco de uso inadequado:** virar relato de bastidor de empregador ou texto operacional demais. Manter o foco em ideia, método e leitura crítica do trabalho.
- **Direção visual:** sistemas, mapas, grids, fluxos, arquitetura de informação, governança, arquivo, camadas editoriais e ciclo de vida do conteúdo.

### 2. Comunicação

- **Slug:** `comunicacao`
- **Subtítulo (WordPress):** Linguagem, posicionamento e construção de autoridade.
- **Função editorial:** comunicação como linguagem pública: posicionamento, reputação, autoridade, marca, canais e clareza em mercados complexos.
- **Tipo de artigo:** ensaios e análises sobre linguagem, percepção, posicionamento, reputação, autoridade e comunicação institucional.
- **Risco de uso inadequado:** virar dica tática, listicle de "como fazer" ou tom de agência. Manter leitura conceitual e crítica.
- **Direção visual:** marca, percepção, linguagem institucional, sinais de mercado, posicionamento, clareza e comunicação.

### 3. Eventos

- **Slug:** `eventos`
- **Subtítulo (WordPress):** Curadoria, formatos e circulação de ideias.
- **Função editorial:** eventos como formatos editoriais e comunicacionais: curadoria, experiência, pauta, audiência e circulação de ideias.
- **Tipo de artigo:** ensaios sobre curadoria, formato, presença, experiência e economia de atenção dos eventos; leitura crítica do que dá valor a um encontro.
- **Risco de uso inadequado:** virar relato de bastidor de evento de empregador ou case promocional. Manter foco em método e leitura crítica.
- **Direção visual:** palco, audiência, presença, encontro, curadoria, experiência, pauta e circulação de ideias.

### 4. IA

- **Slug:** `ia`
- **Subtítulo (WordPress):** Automação, inteligência operacional e fluxos de conteúdo.
- **Função editorial:** IA como camada operacional: automação, pesquisa, organização, produção, análise, documentação e reaproveitamento de conteúdo.
- **Tipo de artigo:** ensaios e análises sobre IA aplicada a rotinas editoriais, automação de processos, pesquisa, organização e documentação; leitura sóbria, sem hype.
- **Risco de uso inadequado:** virar tutorial de ferramenta, hype de IA ou promessa de produtividade. Manter ceticismo informado e foco em método.
- **Direção visual:** máquina, arquivo, repetição, inteligência operacional, interface, automação, análise e fluxos de conteúdo.

### 5. Ecossistema

- **Slug:** `ecossistema`
- **Subtítulo (WordPress):** Integração entre conteúdo, comunicação, canais, eventos, IA e operação.
- **Função editorial:** integração entre conteúdo, comunicação, eventos, IA, canais, pessoas, decisões, fluxos e operação editorial.
- **Tipo de artigo:** notas, diagramas comentados e ensaios curtos sobre integração, coordenação, decisões, canais, fluxos e operação editorial.
- **Risco de uso inadequado:** virar diário pessoal, autoajuda corporativa ou relato de empregador. Manter o foco em método transferível.
- **Direção visual:** redes, sistemas conectados, canais, decisões, fluxos, coordenação, operação editorial e organização do conhecimento.

---

## 11. Papel das imagens

**Princípio:** o site é uma publicação editorial de texto com linguagem visual própria. As imagens funcionam como camada editorial e conceitual — pensamento visual, atmosfera e assinatura autoral — nunca como decoração ou recurso promocional. A presença visual é guiada pelo Design System Códice, com composições autorais, sóbrias e consistentes com os temas publicados.

**Uso recomendado por contexto:**

| Contexto | Uso da imagem |
| --- | --- |
| Home | Imagem editorial forte, sem parecer hero comercial |
| Artigo em destaque | Imagem obrigatória |
| Artigos recentes | Miniatura discreta, quando houver imagem |
| Categorias | Mesma linguagem retroprint; o que muda é o repertório temático do eixo |
| Artigo individual | Imagem de abertura com função editorial |
| Sobre | Imagem pessoal ou composição editorial com presença humana discreta |
| Contato | Página mais limpa, sem imagem dominante |

### 11.1 Linguagem de imagem — colagem editorial retroprint (Códice v6)

O Códice v6 define **um único estilo de imagem** para toda a publicação: colagem editorial retroprint. Não há famílias paralelas — nem imagem documental, geométrica ou fotográfica como categoria independente. O tema define **o que** é representado; o sistema define **como** é tratado.

A imagem deve parecer peça impressa real — pôster cultural, página editorial autoral ou material de acervo —, com: textura de papel, acabamento matte, grão fino, halftone, recortes sobrepostos, massas de tinta opaca, contraste forte, leve desgaste gráfico e leve desalinhamento de impressão. Cada imagem é original e coerente com o texto que ilustra; referências calibram a linguagem, nunca a composição.

**Proibido como direção de imagem:** estética SaaS, dashboard, corporate memphis, stock corporativo, 3D glossy, neon, IA futurista clichê, fotografia crua, ilustração vetorial genérica, line art isolado, CSS art, SVG geométrico, cartaz Bauhaus abstrato e composição puramente geométrica. **Nunca** incluir texto legível, letras, números, logotipos ou marcas dentro da imagem. Para o eixo IA, evitar especificamente robôs, circuitos neon, hologramas e cérebros digitais — tratar IA como método, análise e cultura técnica.

**Estilo único, não variação por categoria.** Todas as imagens usam o mesmo tratamento retroprint. Variar o estilo por categoria quebraria a unidade ("o acervo é uma só obra") e empurraria o site para a aparência de portal/agregador. A reconhecibilidade visual é o ativo da publicação.

**A variação mora no conteúdo, não no tratamento.** O risco de monotonia não vem do estilo único; vem de repetir composição, enquadramento, paleta e motivo. Dentro do retroprint, varie por: paleta de tinta (as nove tintas de imagem), densidade de colagem (cheia vs. campo aberto com respiro), escala (close de fragmento vs. cena ampla), figuração (cena simbólica, objeto isolado, textura dominante, silhueta) e grau de halftone/desgaste. O Códice proíbe repetir motivo recorrente como maneirismo.

**Tendência cromática por categoria (modulação, não regra rígida).** Para dar identidade a cada eixo sem sair do retroprint, cada categoria tem uma inclinação de tinta. Todas permanecem retroprint; muda só a temperatura.

| Categoria | Tendência de tinta |
| --- | --- |
| Conteúdo | Cinza-jornal, preto, creme — sóbrio, estrutural |
| Comunicação | Vermelho impresso + papel envelhecido |
| Eventos | Laranja queimado, mostarda — quente |
| IA | Teal, cyan dessaturado — frio, técnico |
| Ecossistema | Preto sobre creme, mínimo — quase diagrama |

### 11.2 Tokens visuais para a fase de design (Códice v6)

Tokens concretos do Códice v6 que entram diretamente no tema. Cores que não orientam a interface (paleta de imagem retroprint) servem só para guiar o tratamento das imagens e **não** competem com os tokens de UI.

**Paleta de interface:**

| Função | Token | Hex |
| --- | --- | --- |
| Fundo principal (papel) | Bone | `#FBFAF6` |
| Fundo secundário | Vellum | `#F4F2EB` |
| Fundo terciário | Dustcover | `#ECEADF` |
| Texto principal | Graphite | `#191A1F` |
| Texto secundário | Slate | `#3D3F46` |
| Texto terciário / metadados | Pencil | `#7A7B82` |
| Acento (link, estado, ênfase) | Petroleum | `#0F4346` |
| Acento hover | Abyss | `#093033` |
| Margem / borda suave | Margin | `#DDD8CC` |
| Detalhe decorativo (filetes) | Brass | `#A88A4E` |

**Regra de cor:** Petroleum é o único acento funcional (link, hover→Abyss, ênfase). Brass fica **restrito a filetes e detalhes de baixa frequência** — nunca botão, nunca fundo extenso. Cor não organiza informação sozinha; hierarquia vem de tipo, peso, família e ritmo. Filetes finos usam `#191A1F18`; filete forte, `#191A1F30`.

**Tipografia — três famílias, papéis fixos:**

| Família | Stack | Papel |
| --- | --- | --- |
| Serifa | Newsreader (fallback: Source Serif Pro, Georgia) | Títulos, ledes, nomes, citações, aberturas de seção |
| Sans | IBM Plex Sans | Corpo, listas, descrições, navegação, componentes |
| Mono | IBM Plex Mono | Datas, captions, seções, códigos, metadados (uppercase) |

**Layout e estrutura:**

- Grid editorial com margens largas; raio de canto `6px`; espaçamento de seção `clamp(76px, 10vw, 124px)`.
- Sem estouro horizontal: grids sujeitos a overflow usam `minmax(0, 1fr)`; filhos de grid/flex recebem `min-width: 0`; cards, prompts e previews respeitam `max-width: 100%`.
- Breakpoints de referência: 1280px (leitura ampla), 980px (reduz grades de 3–4 colunas), 880px (empilha cabeçalhos e componentes), 560–360px (uma coluna, sem scroll horizontal no body).

**Acessibilidade (regra de composição, não camada posterior):** foco com outline Petroleum de 2px e offset visível; links em texto corrido distinguíveis por sublinhado; contraste preservado entre Graphite, Slate, Pencil, Bone e Vellum; movimento respeita `prefers-reduced-motion`; alt text obrigatório em imagem informativa, descrevendo tema e tratamento retroprint; caption explica a função editorial sem repetir o título da página.

> O arquivo de referência (`codice-designsystem.html`) traz imagens embutidas em base64. Pelo próprio Códice, elas são **diagnóstico do que não reutilizar** — não são assets, paleta obrigatória nem base compositiva. As imagens do site devem ser geradas de novo, por descrição operacional, dentro da linguagem retroprint. O Códice também traz um prompt-base editável e um checklist de geração; use-os como insumo direto ao produzir imagens.

---

## 12. Página Sobre

Narrativa em primeira pessoa, sem virar CV cronológico. Estrutura:

1. **Bio editorial** — quem sou como autor, o que escrevo aqui e de que lugar penso.
2. **Tese de posicionamento** — a interseção entre conteúdo, comunicação, eventos, IA e ecossistema editorial como ponto de vista editorial.
3. **Trajetória resumida** — narrativa curta sobre cerca de 15 anos entre Portugal e Brasil, comunicação, conteúdo, produto, marketing e eventos. Sem lista de cargos com datas.
4. **Áreas de atuação** — os domínios de interesse e trabalho, alinhados às cinco categorias editoriais.
5. **Prova profissional discreta** — contexto profissional atual apenas como lastro, em tom baixo e texto corrido, **descrito por escopo de atuação, sem nomear empregadores**. O detalhe nominal de cargos e marcas fica no LinkedIn e no CV, linkados a partir daqui. Sem logos. Sem transformar marcas atuais em branding pessoal. Sem seção de currículo.
6. **Link para LinkedIn** — para quem quiser o detalhe profissional completo.
7. **Link para contato** — chamada curta e direta para conversa.

**Decisão:** o texto usa apenas o escopo de atuação; não nomeia empregadores. O detalhe nominal vive no LinkedIn e no CV.

---

## 13. Página Contato

Objetivo: permitir que leitores, pares, parceiros e potenciais interlocutores iniciem uma conversa consultiva discreta.

**Campos recomendados:**
1. Nome
2. E-mail
3. Contexto/assunto
4. Mensagem

Abaixo do formulário: link discreto para LinkedIn e e-mail direto.

**Evitar:** "agende uma call", "solicite orçamento", formulário comercial, níveis de serviço, preços, prazos, promessa de retorno, linguagem de funil de vendas.

---

## 14. Templates WordPress e base técnica v1

**Base técnica (decisão):**
- **Hospedagem:** Hostgator. **Domínio:** brunoanastassakis.com.
- **Tema:** próprio, criado do zero, a partir dos tokens do Códice v6 (seção 11). Sem tema comercial nem starter genérico.
- **SEO/schema:** marcados no próprio código do tema (seção 15). Plugin de SEO opcional e complementar.
- **Newsletter:** componente de captação Substack nativo (ver abaixo).

**Templates recomendados:**

| Arquivo | Função |
| --- | --- |
| `front-page.php` | Home editorial |
| `home.php` | Acervo de artigos / índice de posts |
| `single.php` | Artigo individual |
| `category.php` | Arquivos por categoria |
| `page-sobre.php` | Página Sobre |
| `page-contato.php` | Página Contato |
| `search.php` | Resultados de busca |
| `404.php` | Página de erro |
| `index.php` | Fallback obrigatório |

**Arquivos e suportes mínimos:**
`functions.php`; `style.css`; suporte a menu; title tag; excerpts; imagem destacada opcional; paginação; breadcrumbs discretos; schema Article/BlogPosting marcado no próprio código; template part reutilizável para a captação Substack.

**Captação Substack (decisão de arquitetura):**
Usar um **campo de e-mail nativo**, estilizado no padrão Códice, que envia o inscrito ao Substack — **não** usar o iframe/embed padrão do Substack. Justificativa: o embed carrega a identidade visual e o peso de terceiro do Substack, o que quebra a unidade do Códice e prejudica a performance; um campo nativo preserva a estética editorial e a leveza, e mantém o controle visual no tema. O mecanismo exato de envio (form action para o endpoint de inscrição do Substack vs. redirecionamento para a página de inscrição com o e-mail pré-preenchido) é validado na fase de programação, já que o comportamento do Substack pode variar.

**Não criar templates para:** portfólio, serviços, cases, clientes, landing page, projetos.

---

## 15. SEO editorial básico

- categorias claras e estáveis;
- slugs limpos;
- title específico por página/artigo;
- meta description específica;
- hierarquia correta de headings, com um único H1 por página;
- alt text em imagens;
- links internos;
- breadcrumbs discretos;
- schema Article/BlogPosting;
- boa performance mobile; páginas rápidas e legíveis.

**Implementação:** SEO e schema.org são marcados no código do tema custom, não delegados a plugin. Plugin, se usado, é complementar e não pode duplicar o que o tema entrega.

**Não fazer:** SEO agressivo, keyword stuffing ou estrutura artificial para ranqueamento.

---

## 16. Ordem de implementação

A construção é **placeholder-first**: o tema é programado e validado com conteúdo de placeholder; o conteúdo real entra depois, vindo dos projetos de calendário e produção (seção 20). São três marcos distintos.

**Marco 1 — Programar o tema (agora, com placeholder)**
Tema base a partir dos tokens do Códice; templates essenciais (`front-page`, `home`, `single`, `category`, `search`, `404`, `index`, páginas Sobre e Contato); cinco categorias com nome, slug e subtítulo; componente de captação Substack; SEO/schema no código; navegação, paginação, busca e responsividade. Popular com texto e imagens de placeholder para validar layout, ritmo e responsividade.

**Marco 2 — Popular com conteúdo real**
Substituir placeholders pelos artigos produzidos no projeto de conteúdo; definir o artigo-âncora e o destaque da home; configurar imagens retroprint definitivas; revisar SEO por página.

**Marco 3 — Lançar publicamente**
Só divulgar com pelo menos 5 artigos publicados (idealmente um por categoria) e home/acervo não vazios. Rodar o checklist editorial (seção 18.2) antes de divulgar.

**Fase 2 (depois do lançamento):**
refinamento da leitura relacionada; avaliação de séries editoriais apenas com volume real.

**Não fazer em nenhum marco da v1:**
serviços, clientes, cases, portfólio, currículo online, tags, landing comercial; expor métricas, resultados ou bastidores confidenciais.

---

## 17. Séries editoriais

Não criar séries nem temas como seções públicas na v1. Séries podem existir apenas como **organização interna**.

**Regra:** uma série só vira seção pública quando tiver tese durável e pelo menos 5 a 6 artigos publicados e coerentes. Não converter tema em página automaticamente.

---

## 18. Checklists

### 18.1 Antes de subir o tema (Marco 1 — técnico)

- Todos os templates renderizam sem erro e aplicam o template correto a cada tipo de página.
- Tokens do Códice aplicados: paleta, tipografia (Newsreader / IBM Plex Sans / IBM Plex Mono), espaçamento, raio, filetes.
- Sem estouro horizontal em nenhum breakpoint (1280 / 980 / 880 / 560–360px).
- Acessibilidade: foco visível, links sublinhados, contraste preservado, `prefers-reduced-motion`, alt text em imagens.
- SEO/schema no código: title, meta description, H1 único, slugs limpos, Article/BlogPosting.
- Componente de captação Substack funcional e estilizado no padrão Códice (sem embed).
- Performance mobile: página leve e rápida.

### 18.2 Antes de publicar cada artigo / lançar (Marcos 2 e 3 — editorial)

**1. Clareza editorial** — A publicação deixa claro sobre o que é e por quem é escrita? O texto entrega ideia e método, não relato operacional?
**2. Confidencialidade** — Nenhuma métrica, cliente, resultado ou bastidor confidencial exposto? Marcas atuais só como lastro, sem virar branding pessoal?
**3. SEO por página** — Title, meta description, slug e headings corretos e específicos? Um único H1, alt text presente, links internos no lugar?
**4. Consistência de posicionamento** — O artigo se encaixa em uma das cinco categorias sem ambiguidade? O tom mantém a tese: prova de pensamento, não vitrine de execução?
**5. Risco de autopromoção** — O texto evita autoelogio, case promocional e linguagem comercial? A conversão é discreta e consultiva?
**6. Manutenção WordPress** — Categoria, data, destaque e leitura relacionada configurados? Template correto aplicado?
**7. Qualidade visual** — A imagem tem função editorial, segue o Códice, evita aparência corporativa genérica, não compete com o texto e preserva silêncio, hierarquia e leitura?
**8. Performance mobile** — Legível e rápida no celular? O visual parece publicação editorial autoral, não landing page?

---

## 19. Decisões e pendências

**Resolvidas:**
- **Hospedagem e domínio:** Hostgator, brunoanastassakis.com.
- **Tema:** próprio, do zero, a partir dos tokens do Códice v6; SEO/schema no código; plugin opcional e complementar.
- **Newsletter:** Substack, com captação nativa estilizada no padrão Códice (sem embed). Landing de newsletter descartada na v1.
- **Página Sobre:** sem nomear empregadores; apenas escopo de atuação, com detalhe nominal no LinkedIn e no CV.
- **Estilo de imagem:** único (retroprint); variação por conteúdo, paleta e composição, com tendência cromática por eixo.
- **Estratégia de construção:** placeholder-first; conteúdo real e lançamento em marcos posteriores.

**Pendências não bloqueantes (resolver na implementação):**
- **[PENDENTE] Mecanismo exato da captação Substack:** form action vs. redirect com e-mail pré-preenchido — validar na programação.
- **[PENDENTE] E-mail de contato:** definir endereço do domínio (ex.: contato@brunoanastassakis.com) ou usar o e-mail pessoal já existente.
- **[RECOMENDAÇÃO] Gatilho de lançamento público:** só divulgar com 5 artigos publicados (idealmente um por categoria) e home/acervo não vazios.

---

## 20. Próximas fases (nota final)

Este documento encerra a **arquitetura**. O passo imediato é a programação do tema (Marco 1, com placeholder). As demais fases são projetos separados, cada um com fluxo próprio:

1. **Design e programação do tema WordPress** — passo imediato. Recebe este documento como insumo direto; os tokens do Códice v6 estão consolidados na seção 11, e o arquivo `codice-designsystem.html` permanece como referência completa de componentes e exemplos.
2. **Calendário editorial** — projeto de IA à parte, com fluxo próprio, para planejar e gerir os artigos necessários.
3. **Produção de conteúdo** — projeto à parte, para escrever as pautas planejadas.
4. **Popular o site e lançar** — substituir placeholders pelo conteúdo real e divulgar quando o gatilho de lançamento for atingido.

Nada além de arquitetura foi desenvolvido aqui: sem layout final, sem wireframes, sem calendário, sem artigos, sem copy final.
