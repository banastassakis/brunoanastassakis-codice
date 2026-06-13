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
			<?php
			if ( function_exists( 'codice_render_breadcrumbs' ) ) {
				codice_render_breadcrumbs();
			}
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
					$author_name     = get_the_author();
					$published_iso   = get_the_date( 'Y-m-d' );
					$published_label = get_the_date();
					$modified_iso    = get_the_modified_date( 'Y-m-d' );
					$modified_label  = get_the_modified_date();
					$show_modified   = $modified_iso && $modified_iso !== $published_iso;
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
							datetime="<?php echo esc_attr( $published_iso ); ?>"
						><?php echo esc_html( $published_label ); ?></time>
						<?php if ( $show_modified ) : ?>
							<span class="entry-header__meta-sep" aria-hidden="true"> &middot; </span>
							<span class="entry-header__modified">
								<?php esc_html_e( 'Atualizado em', 'codice' ); ?>
								<time datetime="<?php echo esc_attr( $modified_iso ); ?>">
									<?php echo esc_html( $modified_label ); ?>
								</time>
							</span>
						<?php endif; ?>
						<?php if ( $author_name ) : ?>
							<span class="entry-header__meta-sep" aria-hidden="true"> &middot; </span>
							<span class="entry-header__author">
								<?php
								printf(
									/* translators: %s: nome do autor. */
									esc_html__( 'Por %s', 'codice' ),
									esc_html( $author_name )
								);
								?>
							</span>
						<?php endif; ?>
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
								'class'         => 'entry-thumbnail__img',
								'loading'       => 'eager',
								'decoding'      => 'async',
								'fetchpriority' => 'high',
								'sizes'         => '(max-width: 880px) calc(100vw - 2rem), 800px',
								'alt'           => $thumbnail_alt,
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
