<?php
/**
 * front-page.php — Home editorial do site
 *
 * Usado pelo WordPress quando "Página inicial estática" está configurada
 * nas configurações de leitura. Renderiza os 7 blocos da home na ordem
 * definida pela arquitetura (docs/00-arquitetura.md, seção 7):
 *
 *  1. Abertura editorial curta
 *  2. Imagem editorial de abertura
 *  3. Artigo em destaque (sticky → fallback para mais recente)
 *  4. Artigos recentes (sem duplicar o destaque)
 *  5. Eixos editoriais (5 categorias fixas de inc/categories.php)
 *  6. Bloco curto sobre o autor (author-block.php)
 *  7. Captação Substack nativa e chamada discreta para contato
 *
 * Regras Códice:
 * - Um único <h1> por página (bloco 1).
 * - Toda saída escapada; strings de UI traduzíveis (text domain 'codice').
 * - Sem lógica pesada aqui: extraída para helpers e template-parts.
 * - Placeholder-first: nunca quebra por falta de posts.
 * - Categorias inexistentes no banco não geram links falsos.
 * - Captação Substack nativa, sem iframe ou script externo.
 *
 * @package codice
 */

get_header();

// ── Dados auxiliares usados em múltiplos blocos ─────────────────────────────

// Sticky mais recente (se houver).
$sticky_ids = get_option( 'sticky_posts' );
$featured_id = 0;

if ( ! empty( $sticky_ids ) ) {
	$sticky_q = new WP_Query(
		array(
			'post__in'            => $sticky_ids,
			'posts_per_page'      => 1,
			'orderby'             => 'date',
			'order'               => 'DESC',
			'no_found_rows'       => true,
			'ignore_sticky_posts' => true,
		)
	);

	if ( $sticky_q->have_posts() ) {
		$sticky_q->the_post();
		$featured_id = get_the_ID();
		wp_reset_postdata();
	}
}

// Fallback: post mais recente quando não há sticky.
if ( ! $featured_id ) {
	$fallback_q = new WP_Query(
		array(
			'posts_per_page'      => 1,
			'orderby'             => 'date',
			'order'               => 'DESC',
			'no_found_rows'       => true,
			'ignore_sticky_posts' => true,
		)
	);

	if ( $fallback_q->have_posts() ) {
		$fallback_q->the_post();
		$featured_id = get_the_ID();
		wp_reset_postdata();
	}
}

// URL da página de contato (slug 'contato') para fallback seguro.
$contato_page = get_page_by_path( 'contato' );
$contato_url  = $contato_page ? get_permalink( $contato_page->ID ) : home_url( '/contato/' );

?>

<main id="conteudo" class="site-main home-main" role="main" tabindex="-1">


	<!-- ════════════════════════════════════════════════════════════════════
	     Bloco 1 — Abertura editorial curta
	     H1 único da página. Texto placeholder neutro e editorial.
	     ════════════════════════════════════════════════════════════════════ -->

	<section class="home-section home-opening" aria-labelledby="home-title">
		<div class="container">

			<header class="home-opening__header">
				<h1 id="home-title" class="home-opening__title">
					<?php esc_html_e( 'Uma publicação editorial autoral', 'codice' ); ?>
				</h1>
				<p class="home-opening__text lede">
					<?php
					esc_html_e(
						'Ensaios, análises e textos de método sobre conteúdo, comunicação, '
						. 'eventos, IA e ecossistema editorial. '
						. 'Escrito por Bruno Anastassakis.',
						'codice'
					);
					?>
				</p>
			</header>

		</div><!-- .container -->
	</section><!-- .home-opening -->


	<!-- ════════════════════════════════════════════════════════════════════
	     Bloco 2 — Imagem editorial de abertura
	     Área visual sóbria. Sem imagem externa, sem stock, sem texto dentro
	     da imagem. Fallback visual CSS quando não há imagem configurada.
	     ════════════════════════════════════════════════════════════════════ -->

	<section class="home-section home-hero" aria-hidden="true">
		<div class="container">
			<div class="home-hero__frame">
				<!-- Placeholder visual sóbrio — nenhum asset real necessário.
				     Quando houver imagem retroprint definitiva, ela entra aqui
				     via imagem destacada da página home ou via opção de tema. -->
				<div class="home-hero__placeholder" role="presentation"></div>
			</div>
		</div><!-- .container -->
	</section><!-- .home-hero -->


	<!-- ════════════════════════════════════════════════════════════════════
	     Bloco 3 — Artigo em destaque
	     Sticky mais recente ou fallback para post mais recente.
	     ════════════════════════════════════════════════════════════════════ -->

	<section class="home-section home-featured" aria-labelledby="featured-heading">
		<div class="container">

			<span class="section-label"><?php esc_html_e( 'Em destaque', 'codice' ); ?></span>

			<?php if ( $featured_id ) : ?>

				<?php
				$featured_post = get_post( $featured_id );
				if ( $featured_post ) :
					setup_postdata( $featured_post );

					// ── Dados do post em destaque ──────────────────────────
					$feat_permalink   = get_permalink();
					$feat_title       = get_the_title();
					$feat_date_iso    = get_the_date( 'Y-m-d' );
					$feat_date_display = get_the_date();
					$feat_categories  = get_the_category();
					$feat_cat_name    = '';
					$feat_cat_link    = '';
					if ( ! empty( $feat_categories ) ) {
						$feat_cat_name = $feat_categories[0]->name;
						$feat_cat_link = get_category_link( $feat_categories[0]->term_id );
					}
					?>

					<article class="home-featured__article" id="destaque-<?php echo esc_attr( $featured_id ); ?>">

						<?php if ( has_post_thumbnail() ) : ?>
							<div class="home-featured__thumbnail">
								<a href="<?php echo esc_url( $feat_permalink ); ?>" tabindex="-1" aria-hidden="true">
									<?php
									the_post_thumbnail(
										'large',
										array(
											'class'   => 'home-featured__img',
											'loading' => 'lazy',
											'sizes'   => '(max-width: 980px) calc(100vw - 2rem), 480px',
										)
									);
									?>
								</a>
							</div>
						<?php endif; ?>

						<div class="home-featured__body">

							<p class="home-featured__meta">
								<?php if ( $feat_cat_name ) : ?>
									<a
										class="home-featured__cat"
										href="<?php echo esc_url( $feat_cat_link ); ?>"
									><?php echo esc_html( $feat_cat_name ); ?></a>
									<span class="home-featured__meta-sep" aria-hidden="true"> &middot; </span>
								<?php endif; ?>
								<time
									class="home-featured__date"
									datetime="<?php echo esc_attr( $feat_date_iso ); ?>"
								><?php echo esc_html( $feat_date_display ); ?></time>
							</p>

							<h2 id="featured-heading" class="home-featured__title">
								<a href="<?php echo esc_url( $feat_permalink ); ?>">
									<?php echo esc_html( $feat_title ); ?>
								</a>
							</h2>

							<?php if ( has_excerpt() ) : ?>
								<p class="home-featured__excerpt">
									<?php echo esc_html( get_the_excerpt() ); ?>
								</p>
							<?php endif; ?>

							<a
								class="home-featured__read-more"
								href="<?php echo esc_url( $feat_permalink ); ?>"
								aria-label="<?php echo esc_attr( sprintf(
									/* translators: %s: título do artigo em destaque */
									__( 'Ler artigo em destaque: %s', 'codice' ),
									$feat_title
								) ); ?>"
							>
								<?php esc_html_e( 'Ler artigo', 'codice' ); ?> &rarr;
							</a>

						</div><!-- .home-featured__body -->

					</article><!-- .home-featured__article -->

					<?php
					wp_reset_postdata();
				endif;
				?>

			<?php else : ?>

				<!-- Estado vazio: sem posts ainda -->
				<div class="empty-state" role="status">
					<p class="empty-state__title">
						<?php esc_html_e( 'Nenhum artigo publicado ainda.', 'codice' ); ?>
					</p>
					<p class="empty-state__text">
						<?php esc_html_e( 'O primeiro artigo em destaque aparecerá aqui.', 'codice' ); ?>
					</p>
				</div><!-- .empty-state -->

			<?php endif; ?>

		</div><!-- .container -->
	</section><!-- .home-featured -->


	<!-- ════════════════════════════════════════════════════════════════════
	     Bloco 4 — Artigos recentes
	     Exibe até 4 posts; exclui o destaque quando possível.
	     ════════════════════════════════════════════════════════════════════ -->

	<section class="home-section home-recent" aria-labelledby="recent-heading">
		<div class="container">

			<hr class="section-divider" aria-hidden="true">

			<header class="home-recent__header">
				<span class="section-label"><?php esc_html_e( 'Artigos recentes', 'codice' ); ?></span>
			</header>

			<?php
			$exclude_recent = $featured_id ? array( $featured_id ) : array();

			$recent_query = new WP_Query(
				array(
					'post_type'           => 'post',
					'post_status'         => 'publish',
					'posts_per_page'      => 4,
					'post__not_in'        => $exclude_recent,
					'orderby'             => 'date',
					'order'               => 'DESC',
					'no_found_rows'       => true,
					'ignore_sticky_posts' => true,
				)
			);

			if ( $recent_query->have_posts() ) :
				?>
				<ul class="posts-list home-recent__list" role="list">
					<?php
					while ( $recent_query->have_posts() ) :
						$recent_query->the_post();
						?>
						<li class="posts-list__item">
							<?php get_template_part( 'template-parts/card-article' ); ?>
						</li>
					<?php endwhile; ?>
				</ul><!-- .home-recent__list -->

				<?php
				wp_reset_postdata();

				// Link para o acervo completo.
				$artigos_url = function_exists( 'codice_get_posts_index_url' ) ? codice_get_posts_index_url() : home_url( '/artigos/' );
				?>

				<p class="home-recent__archive-link">
					<a
						class="home-recent__archive-link-anchor"
						href="<?php echo esc_url( $artigos_url ); ?>"
					>
						<?php esc_html_e( 'Ver todos os artigos', 'codice' ); ?> &rarr;
					</a>
				</p>

			<?php else : ?>

				<?php wp_reset_postdata(); ?>
				<div class="empty-state" role="status">
					<p class="empty-state__title">
						<?php esc_html_e( 'Nenhum artigo publicado ainda.', 'codice' ); ?>
					</p>
				</div><!-- .empty-state -->

			<?php endif; ?>

		</div><!-- .container -->
	</section><!-- .home-recent -->


	<!-- ════════════════════════════════════════════════════════════════════
	     Bloco 5 — Eixos editoriais
	     As cinco categorias fixas de inc/categories.php.
	     Categorias inexistentes no banco: renderiza sem link.
	     Não trata como serviços; não cria variação visual por categoria.
	     ════════════════════════════════════════════════════════════════════ -->

	<section class="home-section home-axes" aria-labelledby="axes-heading">
		<div class="container">

			<hr class="section-divider" aria-hidden="true">

			<header class="home-axes__header">
				<span class="section-label"><?php esc_html_e( 'Eixos editoriais', 'codice' ); ?></span>
				<h2 id="axes-heading" class="home-axes__title">
					<?php esc_html_e( 'Os campos em que esta publicação pensa', 'codice' ); ?>
				</h2>
				<p class="home-axes__intro">
					<?php esc_html_e( 'Ecossistema organiza a leitura integrada. Conteúdo, comunicação, eventos e IA aparecem como camadas específicas dessa operação editorial mais ampla.', 'codice' ); ?>
				</p>
			</header>

			<ul class="home-axes__list" role="list">
				<?php
				$editorial_categories = codice_get_editorial_categories();
				usort(
					$editorial_categories,
					static function ( $a, $b ) {
						return (int) ! empty( $b['is_integrator'] ) <=> (int) ! empty( $a['is_integrator'] );
					}
				);

				foreach ( $editorial_categories as $editorial_cat ) :
					$term = codice_get_wp_term_by_slug( $editorial_cat['slug'] );
					$is_integrator = ! empty( $editorial_cat['is_integrator'] );
					?>
					<li class="home-axes__item<?php echo $is_integrator ? ' home-axes__item--integrator' : ''; ?>">
						<div class="home-axes__card<?php echo $is_integrator ? ' home-axes__card--integrator' : ''; ?>">

							<p class="home-axes__role">
								<?php echo esc_html( $is_integrator ? __( 'Eixo integrador', 'codice' ) : __( 'Camada do ecossistema', 'codice' ) ); ?>
							</p>

							<?php if ( $term ) : ?>
								<h3 class="home-axes__name">
									<a
										class="home-axes__link"
										href="<?php echo esc_url( get_category_link( $term->term_id ) ); ?>"
									><?php echo esc_html( $editorial_cat['name'] ); ?></a>
								</h3>
							<?php else : ?>
								<h3 class="home-axes__name home-axes__name--no-link">
									<?php echo esc_html( $editorial_cat['name'] ); ?>
								</h3>
							<?php endif; ?>

							<p class="home-axes__subtitle">
								<?php echo esc_html( $editorial_cat['subtitle'] ); ?>
							</p>

							<?php if ( ! empty( $editorial_cat['relation'] ) ) : ?>
								<p class="home-axes__relation">
									<?php echo esc_html( $editorial_cat['relation'] ); ?>
								</p>
							<?php endif; ?>

						</div><!-- .home-axes__card -->
					</li>
				<?php endforeach; ?>
			</ul><!-- .home-axes__list -->

		</div><!-- .container -->
	</section><!-- .home-axes -->


	<!-- ════════════════════════════════════════════════════════════════════
	     Bloco 6 — Bloco curto sobre o autor
	     Chamado via template-part (author-block.php).
	     ════════════════════════════════════════════════════════════════════ -->

	<section class="home-section home-author" aria-labelledby="author-heading">
		<div class="container">

			<hr class="section-divider" aria-hidden="true">

			<h2 id="author-heading" class="sr-only">
				<?php esc_html_e( 'Sobre o autor', 'codice' ); ?>
			</h2>

			<?php get_template_part( 'template-parts/author-block' ); ?>

		</div><!-- .container -->
	</section><!-- .home-author -->


	<!-- ════════════════════════════════════════════════════════════════════
	     Bloco 7 — Newsletter e chamada discreta para contato
	     Captação Substack nativa, sem iframe/embed.
	     ════════════════════════════════════════════════════════════════════ -->

	<section class="home-section home-contact-cta" aria-labelledby="cta-heading">
		<div class="container">

			<hr class="section-divider" aria-hidden="true">

			<?php
			get_template_part(
				'template-parts/newsletter-substack',
				null,
				array(
					'context' => 'home',
					'form_id' => 'codice-newsletter-home',
				)
			);
			?>

			<div class="home-cta">

				<span class="section-label"><?php esc_html_e( 'Contato', 'codice' ); ?></span>

				<h2 id="cta-heading" class="home-cta__title">
					<?php esc_html_e( 'Quer conversar sobre algum tema?', 'codice' ); ?>
				</h2>

				<p class="home-cta__text">
					<?php
					esc_html_e(
						'Se algum artigo levantou uma questão, abriu um ângulo novo ou simplesmente gerou '
						. 'vontade de trocar ideia, a conversa é bem-vinda.',
						'codice'
					);
					?>
				</p>

				<?php if ( $contato_url ) : ?>
					<a
						class="btn"
						href="<?php echo esc_url( $contato_url ); ?>"
					>
						<?php esc_html_e( 'Entrar em contato', 'codice' ); ?>
					</a>
				<?php else : ?>
					<p class="home-cta__note">
						<?php
						/* translators: endereço de e-mail de contato (placeholder) */
						esc_html_e( 'Em breve, formulário de contato disponível.', 'codice' );
						?>
					</p>
				<?php endif; ?>

			</div><!-- .home-cta -->

		</div><!-- .container -->
	</section><!-- .home-contact-cta -->


</main>

<?php
get_footer();
