<?php
/**
 * 404.php — Página de erro 404 (Não Encontrado)
 *
 * Exibe uma mensagem sóbria, objetiva e sem humor forçado,
 * oferecendo links rápidos para reorientar a navegação do usuário.
 *
 * @package codice
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>

<main id="conteudo" class="site-main flex-center" role="main" tabindex="-1">
	<div class="container-reading text-center">
		<article class="error-404 not-found">
			<header class="page-header mb-md">
				<span class="meta error-404__label"><?php esc_html_e( 'Erro 404', 'codice' ); ?></span>
				<h1 class="page-title mb-sm"><?php esc_html_e( 'Página não encontrada', 'codice' ); ?></h1>
				<p class="lede">
					<?php
					esc_html_e(
						'O endereço solicitado não existe ou foi removido desta publicação.',
						'codice'
					);
					?>
				</p>
			</header>

			<div class="page-content">
				<p class="text-slate font-sans mb-lg">
					<?php
					esc_html_e(
						'Recomendamos iniciar pela página inicial para compreender o escopo dos temas discutidos ou navegar pelo acervo completo de artigos.',
						'codice'
					);
					?>
				</p>

				<div class="error-404__actions flex flex-center gap-md">
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="btn">
						<?php esc_html_e( 'Página Inicial', 'codice' ); ?>
					</a>
					<a href="<?php echo esc_url( function_exists( 'codice_get_posts_index_url' ) ? codice_get_posts_index_url() : home_url( '/artigos/' ) ); ?>" class="btn btn-ghost">
						<?php esc_html_e( 'Todos os Artigos', 'codice' ); ?>
					</a>
				</div>
			</div>
		</article>
	</div>
</main>

<?php
get_footer();
