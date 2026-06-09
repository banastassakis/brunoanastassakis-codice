<?php
/**
 * page.php — Generic page template.
 *
 * @package codice
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>

<main id="conteudo" class="site-main" role="main" tabindex="-1">
	<div class="container-reading">
		<?php
		while ( have_posts() ) :
			the_post();

			$hide_title = function_exists( 'codice_should_hide_page_title' ) && codice_should_hide_page_title( get_the_ID() );
			?>
			<article id="post-<?php the_ID(); ?>" <?php post_class( 'page-generic' ); ?>>
				<header class="entry-header">
					<h1 class="<?php echo esc_attr( $hide_title ? 'entry-header__title sr-only' : 'entry-header__title' ); ?>">
						<?php the_title(); ?>
					</h1>
				</header>

				<div class="entry-content">
					<?php the_content(); ?>
				</div>
			</article>
		<?php endwhile; ?>
	</div>
</main>

<?php
get_footer();
