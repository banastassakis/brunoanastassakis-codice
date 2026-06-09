<?php
/**
 * template-parts/related-posts.php — Leitura relacionada
 *
 * Exibe até 3 artigos relacionados ao final do artigo individual.
 * Lógica de seleção:
 *  1. Posts da mesma categoria do artigo atual (ordem: mais recente).
 *  2. Fallback: completa com posts recentes de outras categorias.
 * O post atual é sempre excluído.
 * Se não houver nenhum post relacionado, o bloco não é renderizado.
 *
 * Usa card-article.php para consistência visual com as listagens.
 * Reseta postdata corretamente após queries secundárias.
 *
 * Regras Códice:
 * - Heading não compete com o H1 (usa h2).
 * - Sem slider, carrossel, JS ou animações.
 * - Toda saída escapada; strings de UI traduzíveis (text domain 'codice').
 * - Sem lógica pesada: usa WP_Query.
 *
 * @package codice
 */

// Segurança: não executar diretamente.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// ── Dados do post atual ─────────────────────────────────────────────────────

$current_id = get_the_ID();

if ( ! $current_id ) {
	return;
}

// ── 1ª tentativa: posts da mesma categoria ──────────────────────────────────

$categories = get_the_category( $current_id );
$related_posts = array();

if ( ! empty( $categories ) ) {
	$cat_ids = wp_list_pluck( $categories, 'term_id' );

	$same_cat_query = new WP_Query(
		array(
			'post_type'           => 'post',
			'post_status'         => 'publish',
			'posts_per_page'      => 3,
			'post__not_in'        => array( $current_id ),
			'category__in'        => $cat_ids,
			'orderby'             => 'date',
			'order'               => 'DESC',
			'no_found_rows'       => true,
			'ignore_sticky_posts' => true,
		)
	);

	if ( $same_cat_query->have_posts() ) {
		$related_posts = $same_cat_query->posts;
	}

	wp_reset_postdata();
}

// ── 2ª tentativa: completa com posts recentes de outras categorias ──────────

$needed = 3 - count( $related_posts );

if ( $needed > 0 ) {
	$exclude_ids = array_merge(
		array( $current_id ),
		wp_list_pluck( $related_posts, 'ID' )
	);

	$fallback_query = new WP_Query(
		array(
			'post_type'           => 'post',
			'post_status'         => 'publish',
			'posts_per_page'      => $needed,
			'post__not_in'        => $exclude_ids,
			'orderby'             => 'date',
			'order'               => 'DESC',
			'no_found_rows'       => true,
			'ignore_sticky_posts' => true,
		)
	);

	if ( $fallback_query->have_posts() ) {
		$related_posts = array_merge( $related_posts, $fallback_query->posts );
	}

	wp_reset_postdata();
}

// ── Sem relacionados: não renderiza o bloco ─────────────────────────────────

if ( empty( $related_posts ) ) {
	return;
}

?>

<aside class="related-posts" aria-label="<?php esc_attr_e( 'Leitura relacionada', 'codice' ); ?>">

	<hr class="section-divider" aria-hidden="true">

	<header class="related-posts__header">
		<span class="section-label"><?php esc_html_e( 'Leitura relacionada', 'codice' ); ?></span>
	</header>

	<ul class="related-posts__list posts-list" role="list">
		<?php
		foreach ( $related_posts as $related_post ) :
			setup_postdata( $related_post );
			?>
			<li class="posts-list__item related-posts__item">
				<?php get_template_part( 'template-parts/card-article' ); ?>
			</li>
			<?php
		endforeach;
		wp_reset_postdata();
		?>
	</ul><!-- .related-posts__list -->

</aside><!-- .related-posts -->
