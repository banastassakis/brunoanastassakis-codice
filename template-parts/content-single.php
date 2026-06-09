<?php
/**
 * template-parts/content-single.php — Corpo do artigo individual
 *
 * Chamado por single.php dentro do loop principal.
 * Renderiza the_content() com suporte a blocos nativos do WordPress.
 *
 * Requisitos Códice:
 * - Largura editorial confortável para leitura longa.
 * - Links sublinhados em texto corrido (definidos em main.css .entry-content).
 * - Imagens responsivas (max-width: 100%).
 * - Captions discretas via figcaption (estilizadas em main.css).
 * - Hierarquia de headings preservada do conteúdo.
 * - Sem lógica de SEO/schema nesta etapa.
 * - Sem estilo agressivo de landing page.
 *
 * Não usa <article> pois o pai (single.php) já provê o elemento semântico.
 *
 * @package codice
 */

// Segurança: não executar diretamente.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<div class="entry-body">
	<div class="entry-content">
		<?php
		// Renderiza o conteúdo do post com suporte completo a blocos nativos.
		// Filtros como the_content filtram shortcodes e embeds automaticamente.
		the_content();

		// Navegação entre posts (anterior / próximo) — discreta, sem destaque.
		wp_link_pages(
			array(
				'before'      => '<nav class="entry-pages" aria-label="' . esc_attr__( 'Páginas do artigo', 'codice' ) . '"><p class="entry-pages__label">' . esc_html__( 'Páginas:', 'codice' ) . '</p>',
				'after'       => '</nav>',
				'link_before' => '<span class="entry-pages__link">',
				'link_after'  => '</span>',
			)
		);
		?>
	</div><!-- .entry-content -->
</div><!-- .entry-body -->
