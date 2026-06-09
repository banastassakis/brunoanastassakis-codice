<?php
/**
 * single.php — Artigo individual
 *
 * Template do WordPress para posts individuais. Exibe:
 * categoria + data (metadados mono); título (h1 único);
 * subtítulo/resumo quando houver excerpt manual; imagem destacada opcional;
 * corpo via content-single.php; leitura relacionada via related-posts.php;
 * captação Substack nativa ao final.
 *
 * Regras Códice:
 * - Um único <h1> por página.
 * - Imagem destacada só renderiza se existir; sem placeholder.
 * - Toda saída escapada; strings de UI traduzíveis (text domain 'codice').
 * - Sem lógica pesada aqui: extraída para template-parts.
 *
 * @package codice
 */

get_header();
?>

<main id="conteudo" class="site-main" role="main" tabindex="-1">
	<div class="container">

		<?php
		if ( have_posts() ) :
			the_post();
			?>

			<article id="post-<?php echo esc_attr( get_the_ID() ); ?>" <?php post_class( 'single-article' ); ?>>

				<!-- ── Cabeçalho do artigo ──────────────────────────────── -->

				<header class="entry-header">

					<?php
					// ── Metadados: categoria · data ──────────────────────────
					$categories = get_the_category();
					$cat_name   = '';
					$cat_link   = '';
					if ( ! empty( $categories ) ) {
						$cat_name = $categories[0]->name;
						$cat_link = get_category_link( $categories[0]->term_id );
					}
					?>

					<p class="entry-header__meta">
						<?php if ( $cat_name ) : ?>
							<a
								class="entry-header__cat"
								href="<?php echo esc_url( $cat_link ); ?>"
							><?php echo esc_html( $cat_name ); ?></a>
							<span class="entry-header__meta-sep" aria-hidden="true"> &middot; </span>
						<?php endif; ?>
						<time
							class="entry-header__date"
							datetime="<?php echo esc_attr( get_the_date( 'Y-m-d' ) ); ?>"
						><?php echo esc_html( get_the_date() ); ?></time>
					</p>

					<!-- ── Título: único h1 da página ─────────────────────── -->
					<h1 class="entry-header__title"><?php echo esc_html( get_the_title() ); ?></h1>

					<?php
					// ── Subtítulo / resumo: apenas quando há excerpt manual ──
					// has_excerpt() é true só se o excerpt for digitado manualmente.
					if ( has_excerpt() ) :
						?>
						<p class="entry-header__lede"><?php echo esc_html( get_the_excerpt() ); ?></p>
					<?php endif; ?>

				</header><!-- .entry-header -->

				<?php
				// ── Imagem destacada (opcional) ──────────────────────────────
				// Só renderiza se existir; sem placeholder falso.
				// loading="eager" na imagem de abertura (acima da dobra).
				if ( has_post_thumbnail() ) :
					$thumbnail_id  = get_post_thumbnail_id();
					$thumbnail_alt = get_post_meta( $thumbnail_id, '_wp_attachment_image_alt', true );
					if ( empty( $thumbnail_alt ) ) {
						$thumbnail_alt = sprintf(
							/* translators: %s: título do artigo */
							__( 'Imagem editorial retroprint do artigo: %s', 'codice' ),
							get_the_title()
						);
					}
					?>
					<div class="entry-thumbnail">
						<?php
						the_post_thumbnail(
							'large',
							array(
								'class'    => 'entry-thumbnail__img',
								'loading'  => 'eager',
								'decoding' => 'async',
								'alt'      => $thumbnail_alt,
							)
						);
						?>
					</div><!-- .entry-thumbnail -->
				<?php endif; ?>

				<!-- ── Corpo do artigo ──────────────────────────────────── -->

				<?php get_template_part( 'template-parts/content-single' ); ?>

			</article><!-- .single-article -->

			<!-- ── Leitura relacionada ────────────────────────────────── -->

			<?php get_template_part( 'template-parts/related-posts' ); ?>

			<?php
			get_template_part(
				'template-parts/newsletter-substack',
				null,
				array(
					'context' => 'inline',
					'form_id' => 'codice-newsletter-single',
				)
			);

		else :
			// ── Fallback: post não encontrado ───────────────────────────────
			?>
			<div class="empty-state" role="status">
				<span class="empty-state__label"><?php esc_html_e( 'Artigo', 'codice' ); ?></span>
				<p class="empty-state__title">
					<?php esc_html_e( 'Artigo não encontrado.', 'codice' ); ?>
				</p>
				<p class="empty-state__text">
					<?php esc_html_e( 'O artigo que você procura não existe ou foi removido.', 'codice' ); ?>
				</p>
				<p class="empty-state__action">
					<a class="btn" href="<?php echo esc_url( home_url( '/' ) ); ?>">
						<?php esc_html_e( 'Voltar ao início', 'codice' ); ?>
					</a>
				</p>
			</div><!-- .empty-state -->
		<?php endif; ?>

	</div><!-- .container -->
</main>

<?php
get_footer();
