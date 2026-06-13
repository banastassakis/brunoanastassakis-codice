<?php
/**
 * category.php — Arquivo por categoria
 *
 * Exibe a listagem de posts de uma categoria específica com:
 * - Cabeçalho com nome e subtítulo da categoria
 * - Subtítulo vindo da descrição do WP quando existir;
 *   fallback para inc/categories.php quando o slug corresponder
 *   a uma das cinco categorias fixas.
 * - Listagem com card-article.php
 * - Paginação numerada
 * - Estado vazio elegante
 *
 * Regras Códice:
 * - Um único <h1> por página.
 * - Sem variação visual por categoria.
 * - Toda saída escapada.
 *
 * @package codice
 */

get_header();

// ── Dados da categoria atual ──────────────────────────────────────────────────

$current_cat  = get_queried_object();
$cat_name     = ( $current_cat instanceof WP_Term ) ? $current_cat->name : '';
$cat_slug     = ( $current_cat instanceof WP_Term ) ? $current_cat->slug : '';

// Subtítulo: prefere a descrição do WP se preenchida;
// caso contrário, usa o subtítulo das categorias fixas.
$cat_desc    = ( $current_cat instanceof WP_Term ) ? category_description() : '';
$cat_desc    = wp_strip_all_tags( $cat_desc ); // remove tags HTML da descrição do WP.
if ( ! $cat_desc ) {
	$cat_desc = codice_get_category_subtitle( $cat_slug );
}

?>
<main id="conteudo" class="site-main" role="main" tabindex="-1">
	<div class="container">
		<?php
		if ( function_exists( 'codice_render_breadcrumbs' ) ) {
			codice_render_breadcrumbs();
		}
		?>

		<!-- ── Cabeçalho da categoria ──────────────────────────────────────── -->

		<header class="archive-header">
			<span class="section-label archive-header__label">
				<?php esc_html_e( 'Categoria', 'codice' ); ?>
			</span>
			<h1 class="archive-header__title">
				<?php echo esc_html( $cat_name ); ?>
			</h1>
			<?php if ( $cat_desc ) : ?>
				<p class="archive-header__subtitle">
					<?php echo esc_html( $cat_desc ); ?>
				</p>
			<?php endif; ?>
		</header>

		<hr class="section-divider" aria-hidden="true">

		<?php
		// ── Loop ─────────────────────────────────────────────────────────────

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
				<span class="empty-state__label"><?php esc_html_e( 'Categoria', 'codice' ); ?></span>
				<p class="empty-state__title">
					<?php esc_html_e( 'Nenhum artigo publicado nesta categoria.', 'codice' ); ?>
				</p>
				<p class="empty-state__text">
					<?php esc_html_e( 'Os artigos aparecerão aqui assim que forem publicados.', 'codice' ); ?>
				</p>
				<div class="empty-state__action">
					<a href="<?php echo esc_url( function_exists( 'codice_get_posts_index_url' ) ? codice_get_posts_index_url() : home_url( '/artigos/' ) ); ?>" class="btn-text">
						<?php esc_html_e( '← Ver todos os artigos', 'codice' ); ?>
					</a>
				</div>
			</div><!-- .empty-state -->
		<?php endif; ?>

	</div><!-- .container -->
</main>

<?php
get_footer();
