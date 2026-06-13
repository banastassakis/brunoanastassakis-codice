<?php
/**
 * home.php — Acervo de artigos / página Artigos
 *
 * Usado pelo WordPress quando "Página de posts" está configurada nas
 * configurações de leitura. Exibe listagem cronológica reversa com
 * filtro por categoria, busca simples e post fixado opcional no topo.
 *
 * Estrutura:
 * 1. Cabeçalho da página (h1 único + intro editorial)
 * 2. Barra de ferramentas: filtro por categoria + busca simples
 * 3. Post fixado opcional (seção discreta, não duplicado na lista)
 * 4. Listagem principal com card-article.php
 * 5. Paginação numerada
 * 6. Estado vazio elegante
 *
 * Sticky:
 * - Quando há sticky, usa WP_Query próprio para o loop (com post__not_in).
 * - Quando não há sticky, usa a query global do WordPress diretamente.
 * - Em ambos os casos a paginação é construída com paginate_links().
 *
 * Regras Códice:
 * - Sem scroll infinito; sem filtro por tag, data ou popularidade.
 * - Categorias: apenas as cinco fixas; link real se o termo existir.
 * - Busca: formulário GET nativo; sem AJAX.
 * - Um único <h1> por página.
 *
 * @package codice
 */

get_header();

// ── Post fixado: busca o sticky mais recente (se houver) ─────────────────────

$sticky_ids = get_option( 'sticky_posts' );
$sticky_id  = 0;

if ( ! empty( $sticky_ids ) ) {
	$sticky_q = new WP_Query(
		array(
			'post__in'            => $sticky_ids,
			'posts_per_page'      => 1,
			'orderby'             => 'date',
			'order'               => 'DESC',
			'ignore_sticky_posts' => true,
			'no_found_rows'       => true,
		)
	);
	if ( $sticky_q->have_posts() ) {
		$sticky_q->the_post();
		$sticky_id = get_the_ID();
		wp_reset_postdata();
	}
}

// ── Loop principal ────────────────────────────────────────────────────────────
//
// Quando há sticky, montamos uma WP_Query própria (post__not_in + paginação).
// Quando não há sticky, reusamos a query global do WordPress.

$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;

if ( $sticky_id ) {
	$loop = new WP_Query(
		array(
			'post_type'      => 'post',
			'post_status'    => 'publish',
			'posts_per_page' => get_option( 'posts_per_page' ),
			'paged'          => $paged,
			'post__not_in'   => array( $sticky_id ),
			'orderby'        => 'date',
			'order'          => 'DESC',
		)
	);
} else {
	global $wp_query;
	$loop = $wp_query;
}

?>
<main id="conteudo" class="site-main" role="main" tabindex="-1">
	<div class="container">
		<?php
		if ( function_exists( 'codice_render_breadcrumbs' ) ) {
			codice_render_breadcrumbs();
		}
		?>

		<!-- ── Cabeçalho da página ─────────────────────────────────────────── -->

		<header class="archive-header">
			<h1 class="archive-header__title">
				<?php esc_html_e( 'Artigos', 'codice' ); ?>
			</h1>
			<p class="archive-header__intro">
				<?php esc_html_e( 'Ensaios, análises e textos de método sobre conteúdo, comunicação, eventos, IA e ecossistema editorial.', 'codice' ); ?>
			</p>
		</header>

		<hr class="section-divider" aria-hidden="true">

		<!-- ── Barra: filtro por categoria + busca ─────────────────────────── -->

		<div class="archive-toolbar">

			<nav class="cat-filter" aria-label="<?php esc_attr_e( 'Filtrar por categoria', 'codice' ); ?>">
				<ul class="cat-filter__list list-bare" role="list">

					<?php
					// URL da página de artigos (posts page).
					$posts_page_id  = get_option( 'page_for_posts' );
					$posts_page_url = $posts_page_id
						? get_permalink( $posts_page_id )
						: home_url( '/' );
					?>

					<li class="cat-filter__item">
						<a
							class="cat-filter__link<?php echo ( ! is_category() ) ? ' cat-filter__link--active' : ''; ?>"
							href="<?php echo esc_url( $posts_page_url ); ?>"
						><?php esc_html_e( 'Todos', 'codice' ); ?></a>
					</li>

					<?php
					foreach ( codice_get_editorial_categories() as $editorial_cat ) :
						$term      = codice_get_wp_term_by_slug( $editorial_cat['slug'] );
						$is_active = ( $term && is_category( $term->term_id ) );
						?>
						<li class="cat-filter__item">
							<?php if ( $term ) : ?>
								<a
									class="cat-filter__link<?php echo $is_active ? ' cat-filter__link--active' : ''; ?>"
									href="<?php echo esc_url( get_category_link( $term->term_id ) ); ?>"
									<?php if ( $is_active ) : ?>aria-current="page"<?php endif; ?>
								><?php echo esc_html( $editorial_cat['name'] ); ?></a>
							<?php else : ?>
								<span
									class="cat-filter__link cat-filter__link--inactive"
									title="<?php esc_attr_e( 'Nenhum artigo nesta categoria ainda.', 'codice' ); ?>"
								><?php echo esc_html( $editorial_cat['name'] ); ?></span>
							<?php endif; ?>
						</li>
					<?php endforeach; ?>

				</ul>
			</nav><!-- .cat-filter -->

			<form
				class="search-form-inline"
				role="search"
				method="get"
				action="<?php echo esc_url( home_url( '/' ) ); ?>"
			>
				<label for="s-artigos" class="sr-only">
					<?php esc_html_e( 'Buscar artigos', 'codice' ); ?>
				</label>
				<input
					id="s-artigos"
					type="search"
					name="s"
					value="<?php echo esc_attr( get_search_query() ); ?>"
					placeholder="<?php esc_attr_e( 'Buscar…', 'codice' ); ?>"
					autocomplete="off"
				>
				<button type="submit" class="btn"><?php esc_html_e( 'Buscar', 'codice' ); ?></button>
			</form><!-- .search-form-inline -->

		</div><!-- .archive-toolbar -->

		<?php
		// ── Post fixado (opcional) ────────────────────────────────────────────

		if ( $sticky_id ) :
			$sticky_obj = get_post( $sticky_id );
			if ( $sticky_obj ) :
				setup_postdata( $sticky_obj );
				?>
				<section class="sticky-post" aria-label="<?php esc_attr_e( 'Artigo em destaque', 'codice' ); ?>">
					<p class="section-label"><?php esc_html_e( 'Destaque', 'codice' ); ?></p>
					<div class="sticky-post__card">
						<?php get_template_part( 'template-parts/card-article' ); ?>
					</div>
				</section>

				<hr class="section-divider" aria-hidden="true">

				<?php
				wp_reset_postdata();
			endif;
		endif;

		// ── Loop principal ────────────────────────────────────────────────────

		if ( $loop->have_posts() ) :
			?>
			<ul class="posts-list" role="list">
				<?php
				while ( $loop->have_posts() ) :
					$loop->the_post();
					?>
					<li class="posts-list__item">
						<?php get_template_part( 'template-parts/card-article' ); ?>
					</li>
				<?php endwhile; ?>
			</ul><!-- .posts-list -->

			<?php
			wp_reset_postdata();

			// Paginação numerada via paginate_links() — compatível com WP_Query próprio.
			$big = 999999999; // número improvável para substituição.
			echo '<nav class="pagination" aria-label="' . esc_attr__( 'Navegação de páginas', 'codice' ) . '">';
			echo '<div class="nav-links">';
			// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			echo paginate_links(
				array(
					'base'      => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
					'format'    => '?paged=%#%',
					'current'   => max( 1, $paged ),
					'total'     => $loop->max_num_pages,
					'mid_size'  => 2,
					'prev_text' => esc_html__( '← Anterior', 'codice' ),
					'next_text' => esc_html__( 'Próximo →', 'codice' ),
				)
			);
			echo '</div></nav>';

		else :
			// ── Estado vazio ──────────────────────────────────────────────────
			?>
			<div class="empty-state" role="status">
				<span class="empty-state__label"><?php esc_html_e( 'Acervo', 'codice' ); ?></span>
				<p class="empty-state__title">
					<?php esc_html_e( 'Nenhum artigo publicado ainda.', 'codice' ); ?>
				</p>
				<p class="empty-state__text">
					<?php esc_html_e( 'Os artigos aparecerão aqui assim que forem publicados.', 'codice' ); ?>
				</p>
			</div><!-- .empty-state -->
		<?php endif; ?>

	</div><!-- .container -->
</main>

<?php
get_footer();
