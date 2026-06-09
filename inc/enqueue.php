<?php
/**
 * inc/enqueue.php — Enfileiramento de assets do tema Códice
 *
 * Registra e enfileira CSS e JS do tema na ordem correta.
 * Chamado via functions.php.
 *
 * @package codice
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Enfileira estilos e scripts do tema.
 */
function codice_enqueue_assets() {

	$template_dir = get_template_directory();
	$template_uri = get_template_directory_uri();

	/*
	 * 1. Custom properties do Códice v6 (tokens).
	 * Deve ser carregado antes de main.css, pois este depende das variáveis.
	 */
	$tokens_path = $template_dir . '/assets/css/tokens.css';
	wp_enqueue_style(
		'codice-tokens',
		$template_uri . '/assets/css/tokens.css',
		array(),
		file_exists( $tokens_path ) ? filemtime( $tokens_path ) : '1.0.0'
	);

	/*
	 * 2. Estilos globais do tema.
	 * Depende de codice-tokens para usar as variáveis corretamente.
	 */
	$main_css_path = $template_dir . '/assets/css/main.css';
	wp_enqueue_style(
		'codice-main',
		$template_uri . '/assets/css/main.css',
		array( 'codice-tokens' ),
		file_exists( $main_css_path ) ? filemtime( $main_css_path ) : '1.0.0'
	);

	/*
	 * 3. style.css do tema (contém o reset mínimo).
	 * Carregado depois de main.css para não sobrescrever estilos.
	 */
	$style_css_path = $template_dir . '/style.css';
	wp_enqueue_style(
		'codice-style',
		get_stylesheet_uri(),
		array( 'codice-tokens' ),
		file_exists( $style_css_path ) ? filemtime( $style_css_path ) : '1.0.0'
	);

	/*
	 * 4. Script principal do tema.
	 * Carregado no rodapé (in_footer: true) e com atributo defer
	 * via filtro wp_script_attributes para não bloquear a renderização.
	 */
	$main_js_path = $template_dir . '/assets/js/main.js';
	wp_enqueue_script(
		'codice-main-js',
		$template_uri . '/assets/js/main.js',
		array(),
		file_exists( $main_js_path ) ? filemtime( $main_js_path ) : '1.0.0',
		array(
			'in_footer' => true,
			'strategy'  => 'defer',
		)
	);
}
add_action( 'wp_enqueue_scripts', 'codice_enqueue_assets' );
