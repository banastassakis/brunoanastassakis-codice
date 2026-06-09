<?php
/**
 * inc/setup.php — Suportes e registros do tema Códice
 *
 * Registra os theme supports, menus e define o fallback de navegação.
 * Chamado via functions.php.
 *
 * @package codice
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Registra suportes e menus do tema.
 */
function codice_setup() {

	/*
	 * Deixa o WordPress gerenciar o título da página (<title>).
	 * Não gerar o <title> manualmente nos templates.
	 */
	add_theme_support( 'title-tag' );

	/*
	 * Suporte a imagem destacada (post thumbnail) nos posts.
	 */
	add_theme_support( 'post-thumbnails' );

	/*
	 * HTML5 para os elementos gerados pelo WordPress.
	 */
	add_theme_support(
		'html5',
		array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
			'style',
			'script',
		)
	);

	/*
	 * Suporte a excerpts em páginas (além de posts).
	 */
	add_post_type_support( 'page', 'excerpt' );

	/*
	 * Registra o menu principal.
	 * Localização 'primary' é exibida no header.php.
	 */
	register_nav_menus(
		array(
			'primary' => esc_html__( 'Menu principal', 'codice' ),
		)
	);

	/*
	 * Suporte a wp_body_open() para compatibilidade com plugins.
	 */
	add_theme_support( 'wp-block-styles' );
}
add_action( 'after_setup_theme', 'codice_setup' );

/**
 * Fallback de navegação quando o menu 'primary' ainda não estiver configurado.
 *
 * Exibe uma lista simples com os quatro destinos do site.
 * Será substituída pelo menu real configurado no WP Admin.
 *
 * @return void
 */
function codice_nav_fallback() {
	?>
	<ul class="site-nav__list site-nav__list--fallback">
		<li><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Início', 'codice' ); ?></a></li>
		<li><a href="<?php echo esc_url( function_exists( 'codice_get_posts_index_url' ) ? codice_get_posts_index_url() : home_url( '/artigos/' ) ); ?>"><?php esc_html_e( 'Artigos', 'codice' ); ?></a></li>
		<li><a href="<?php echo esc_url( home_url( '/sobre' ) ); ?>"><?php esc_html_e( 'Sobre', 'codice' ); ?></a></li>
		<li><a href="<?php echo esc_url( home_url( '/contato' ) ); ?>"><?php esc_html_e( 'Contato', 'codice' ); ?></a></li>
	</ul>
	<?php
}
