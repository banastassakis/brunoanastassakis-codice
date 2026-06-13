<?php
/**
 * template-parts/card-article.php — Cartão reutilizável de artigo
 *
 * Usado em home.php, category.php e search.php dentro do loop principal.
 * Deve ser chamado com get_template_part() após have_posts() / the_post().
 *
 * Regras Códice:
 * - Toda saída escapada.
 * - Strings de UI traduzíveis com text domain 'codice'.
 * - Sem autor, tempo de leitura, comentários, tags ou métricas.
 * - Imagem destacada opcional; sem placeholder se ausente.
 * - Categoria: primeira disponível com link real; sem tags.
 * - Visual editorial sóbrio — não depende de conteúdo real.
 *
 * @package codice
 */

// Segurança: não executar diretamente.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// ── Dados do post ────────────────────────────────────────────────────────────

$post_id       = get_the_ID();
$permalink     = get_permalink();
$title         = get_the_title();
$date_iso      = get_the_date( 'Y-m-d' );
$date_display  = get_the_date();
$has_thumbnail = has_post_thumbnail();
$excerpt        = get_the_excerpt();
$has_excerpt   = has_excerpt() || $excerpt;

// Categoria principal: a primeira da lista de categorias do post.
$categories  = get_the_category();
$cat_name    = '';
$cat_link    = '';
if ( ! empty( $categories ) ) {
	$cat_name = $categories[0]->name;
	$cat_link = get_category_link( $categories[0]->term_id );
}

?>
<article id="post-<?php echo esc_attr( $post_id ); ?>" <?php post_class( $has_thumbnail ? 'card-article card-article--has-thumbnail' : 'card-article' ); ?>>

	<?php if ( $has_thumbnail ) : ?>
		<div class="card-article__thumbnail" aria-hidden="true">
			<a href="<?php echo esc_url( $permalink ); ?>" tabindex="-1">
				<?php
				the_post_thumbnail(
					'medium_large',
					array(
						'class'   => 'card-article__img',
						'loading' => 'lazy',
						'sizes'   => '(max-width: 880px) calc(100vw - 2rem), 320px',
						'alt'     => the_title_attribute( array( 'echo' => false ) ),
					)
				);
				?>
			</a>
		</div>
	<?php endif; ?>

	<div class="card-article__body">

		<p class="card-article__meta">
			<?php if ( $cat_name ) : ?>
				<a
					class="card-article__cat"
					href="<?php echo esc_url( $cat_link ); ?>"
				><?php echo esc_html( $cat_name ); ?></a>
				<span class="card-article__meta-sep" aria-hidden="true"> &middot; </span>
			<?php endif; ?>
			<time class="card-article__date" datetime="<?php echo esc_attr( $date_iso ); ?>">
				<?php echo esc_html( $date_display ); ?>
			</time>
		</p>

		<h2 class="card-article__title">
			<a href="<?php echo esc_url( $permalink ); ?>">
				<?php echo esc_html( $title ); ?>
			</a>
		</h2>

		<?php if ( $has_excerpt ) : ?>
			<div class="card-article__excerpt">
				<?php echo wp_kses_post( wpautop( $excerpt ) ); ?>
			</div>
		<?php endif; ?>

		<a
			class="card-article__read-more"
			href="<?php echo esc_url( $permalink ); ?>"
			aria-label="<?php echo esc_attr(
				sprintf(
					/* translators: %s: título do artigo */
					__( 'Ler: %s', 'codice' ),
					$title
				)
			); ?>"
		><?php esc_html_e( 'Ler artigo', 'codice' ); ?> &rarr;</a>

	</div><!-- .card-article__body -->

</article>
