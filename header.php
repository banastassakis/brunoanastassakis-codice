<?php
/**
 * header.php — Cabeçalho do tema Códice
 *
 * Abre o documento HTML, inclui wp_head() e renderiza o cabeçalho
 * com skip-link e navegação principal.
 *
 * @package codice
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php
	/*
	 * Preload da fonte Newsreader 600 — usada nos headings acima da dobra.
	 * Só ativo quando o arquivo .woff2 existir no servidor.
	 * Instrução restrita ao <head>; não altera estrutura do header.
	 */
	$newsreader_600 = get_template_directory() . '/assets/fonts/newsreader-600.woff2';
	if ( file_exists( $newsreader_600 ) ) :
		?>
	<link
		rel="preload"
		href="<?php echo esc_url( get_template_directory_uri() . '/assets/fonts/newsreader-600.woff2' ); ?>"
		as="font"
		type="font/woff2"
		crossorigin="anonymous"
	>
	<?php endif; ?>
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<a class="skip-link" href="#conteudo">
	<?php esc_html_e( 'Pular para o conteúdo', 'codice' ); ?>
</a>

<header class="site-header" role="banner">
	<div class="site-header__inner">

		<p class="site-header__name">
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>">
				<?php bloginfo( 'name' ); ?>
			</a>
		</p>

		<nav class="site-nav" role="navigation" aria-label="<?php esc_attr_e( 'Navegação principal', 'codice' ); ?>">
			<?php
			wp_nav_menu(
				array(
					'theme_location' => 'primary',
					'container'      => false,
					'menu_class'     => 'site-nav__list',
					'depth'          => 1,
					'fallback_cb'    => 'codice_nav_fallback',
				)
			);
			?>
		</nav>

	</div>
</header>
