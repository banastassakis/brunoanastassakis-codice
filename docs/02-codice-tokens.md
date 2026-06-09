# Tokens do Códice v6 — referência visual

Extração dos tokens do Design System Códice v6 para uso direto no tema. Fonte: `docs/codice-designsystem-v6.html`. Estes valores são a lei visual do projeto: nenhuma cor, fonte ou espaçamento fora daqui.

## Paleta de interface (UI)

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
| Borda suave / margem | Margin | `#DDD8CC` |
| Detalhe decorativo (só filetes) | Brass | `#A88A4E` |
| Filete fino | rule | `#191A1F18` |
| Filete forte | rule-strong | `#191A1F30` |

**Regra de cor:** Petroleum é o único acento funcional (link → hover Abyss → ênfase). Brass aparece só em filetes e detalhes de baixa frequência — nunca botão, nunca fundo. Cor não organiza informação sozinha; hierarquia vem de tipografia.

## Tipografia

| Família | Stack | Papel |
| --- | --- | --- |
| Serifa | Newsreader (fallback: Source Serif Pro, Georgia, Times New Roman) | Títulos, ledes, nomes, citações, aberturas de seção |
| Sans | IBM Plex Sans (fallback: system-ui, -apple-system, Segoe UI, Helvetica, Arial) | Corpo, listas, descrições, navegação, componentes |
| Mono | IBM Plex Mono (fallback: ui-monospace, SF Mono, Menlo, Consolas) | Datas, captions, seções, códigos, metadados (uppercase) |

Self-hosted (`.woff2`, `font-display:swap`). Carregar só os pesos usados.

## Layout

- Raio de canto: `6px`.
- Espaçamento de seção: `clamp(76px, 10vw, 124px)`.
- Sem estouro horizontal: grids `minmax(0, 1fr)`; filhos de grid/flex `min-width: 0`; mídia `max-width: 100%`.
- Breakpoints: 1280px (leitura ampla), 980px (reduz grades de 3–4 colunas), 880px (empilha cabeçalhos/componentes), 560–360px (uma coluna, sem scroll horizontal no body).

## Acessibilidade

- Foco: outline Petroleum 2px com offset visível.
- Links de texto distinguíveis por sublinhado.
- Contraste preservado entre Graphite/Slate/Pencil sobre Bone/Vellum.
- `prefers-reduced-motion` respeitado.
- `alt` obrigatório em imagem informativa, descrevendo tema + tratamento retroprint.

## Paleta de imagem retroprint (somente para geração de imagem)

Não competem com os tokens de UI; orientam o tratamento das imagens retroprint na fase de conteúdo. Tinta opaca sobre papel.

| Tinta | Hex |
| --- | --- |
| Papel | `#E8DFC8` |
| Papel envelhecido | `#D8CBAE` |
| Preto de impressão | `#0A0A0A` |
| Cinza-jornal | `#4E4D48` |
| Vermelho impresso | `#D33B2E` |
| Laranja queimado | `#D96727` |
| Mostarda | `#D8A51D` |
| Teal | `#008B95` |
| Cyan dessaturado | `#2B9DB6` |

Tendência cromática por categoria (modulação, não regra): Conteúdo → cinza-jornal/preto/creme; Comunicação → vermelho + papel envelhecido; Eventos → laranja/mostarda; IA → teal/cyan; Ecossistema → preto sobre creme.

## CSS pronto (custom properties)

```css
:root{
  --bone:#FBFAF6; --vellum:#F4F2EB; --dustcover:#ECEADF;
  --graphite:#191A1F; --slate:#3D3F46; --pencil:#7A7B82;
  --petroleum:#0F4346; --abyss:#093033; --margin:#DDD8CC; --brass:#A88A4E;
  --rule:#191A1F18; --rule-strong:#191A1F30;
  --serif:"Newsreader","Source Serif Pro",Georgia,"Times New Roman",serif;
  --sans:"IBM Plex Sans",ui-sans-serif,system-ui,-apple-system,"Segoe UI",Helvetica,Arial,sans-serif;
  --mono:"IBM Plex Mono",ui-monospace,"SF Mono",Menlo,Consolas,monospace;
  --radius:6px; --space-section:clamp(76px,10vw,124px); --focus-ring:var(--petroleum);
}
```
