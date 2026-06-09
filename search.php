<?php
/**
 * search.php — Resultados de busca
 *
 * Exibe os resultados da busca nativa do WordPress com:
 * - Cabeçalho com o termo buscado (escapado)
 * - Formulário de nova busca
 * - Listagem de resultados com card-article.php
 * - Paginação numerada
 * - Estado vazio claro quando não há resultados
 *
 * Regras Códice:
 * - Um único <h1> por página.
 * - Sem filtros adicionais além da busca nativa.
 * - Toda saída escapada.
 *
 * @package codice
 */

get_header();

$search_query = get_search_query();

?>
<main id="conteudo" class="site-main" role="main" tabindex="-1">
	<div class="container">

		<!-- ── Cabeçalho dos resultados ────────────────────────────────────── -->

		<header class="archive-header">
			<span class="section-label archive-header__label">
				<?php esc_html_e( 'Busca', 'codice' ); ?>
			</span>
			<?php if ( have_posts() ) : ?>
				<h1 class="archive-header__title">
					<?php
					printf(
						/* translators: %s: termo buscado pelo usuário */
						esc_html__( 'Resultados para: %s', 'codice' ),
						'<em>' . esc_html( $search_query ) . '</em>'
					);
					?>
				</h1>
			<?php else : ?>
				<h1 class="archive-header__title">
					<?php esc_html_e( 'Nenhum resultado encontrado', 'codice' ); ?>
				</h1>
			<?php endif; ?>
		</header>

		<hr class="section-divider" aria-hidden="true">

		<!-- ── Formulário de nova busca ─────────────────────────────────────── -->

		<div class="search-refine">
			<form
				class="search-form-inline"
				role="search"
				method="get"
				action="<?php echo esc_url( home_url( '/' ) ); ?>"
			>
				<label for="s-nova-busca">
					<?php esc_html_e( 'Nova busca', 'codice' ); ?>
				</label>
				<input
					id="s-nova-busca"
					type="search"
					name="s"
					value="<?php echo esc_attr( $search_query ); ?>"
					placeholder="<?php esc_attr_e( 'Buscar artigos…', 'codice' ); ?>"
					autocomplete="off"
				>
				<button type="submit" class="btn"><?php esc_html_e( 'Buscar', 'codice' ); ?></button>
			</form>
		</div><!-- .search-refine -->

		<?php
		// ── Loop de resultados ────────────────────────────────────────────────

		if ( have_posts() ) :
			?>
			<ul class="posts-list" role="list">
				<?php
				while ( have_posts() ) :
					the_post();
					?>
					<li class="posts-list__item">
						<?php get_template_part( 'template-parts/card-article' ); ?>
					</li>
				<?php endwhile; ?>
			</ul><!-- .posts-list -->

			<?php
			// Paginação numerada.
			the_posts_pagination(
				array(
					'mid_size'  => 2,
					'prev_text' => esc_html__( '← Anterior', 'codice' ),
					'next_text' => esc_html__( 'Próximo →', 'codice' ),
					'class'     => 'pagination',
					'type'      => 'list',
				)
			);

		else :
			// ── Estado vazio ──────────────────────────────────────────────────
			?>
			<div class="empty-state" role="status">
				<span class="empty-state__label"><?php esc_html_e( 'Sem resultados', 'codice' ); ?></span>
				<p class="empty-state__title">
					<?php esc_html_e( 'Nenhum artigo corresponde à sua busca.', 'codice' ); ?>
				</p>
				<p class="empty-state__text">
					<?php esc_html_e( 'Tente palavras diferentes ou navegue pelo acervo completo.', 'codice' ); ?>
				</p>
				<div class="empty-state__action">
					<?php
					$posts_page_id  = get_option( 'page_for_posts' );
					$posts_page_url = $posts_page_id
						? get_permalink( $posts_page_id )
						: home_url( '/' );
					?>
					<a href="<?php echo esc_url( $posts_page_url ); ?>" class="btn-text">
						<?php esc_html_e( '← Ver todos os artigos', 'codice' ); ?>
					</a>
				</div>
			</div><!-- .empty-state -->
		<?php endif; ?>

	</div><!-- .container -->
</main>

<?php
get_footer();
