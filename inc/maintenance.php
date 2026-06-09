<?php
/**
 * inc/maintenance.php — Theme-controlled public maintenance route.
 *
 * To enable maintenance before launch, set the constant below to true.
 * To disable it after launch, set it back to false.
 *
 * The /manutencao route remains available either way and does not require a
 * WordPress page to exist in the admin.
 *
 * @package codice
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! defined( 'CODICE_MAINTENANCE_MODE' ) ) {
	define( 'CODICE_MAINTENANCE_MODE', false );
}

/**
 * Registers the public /manutencao route.
 */
function codice_register_maintenance_route() {
	add_rewrite_rule( '^manutencao/?$', 'index.php?codice_maintenance=1', 'top' );

	$rewrite_version = '1';
	if ( get_option( 'codice_maintenance_rewrite_version' ) !== $rewrite_version ) {
		flush_rewrite_rules( false );
		update_option( 'codice_maintenance_rewrite_version', $rewrite_version, false );
	}
}
add_action( 'init', 'codice_register_maintenance_route' );

/**
 * Adds the maintenance query var.
 *
 * @param array $vars Public query vars.
 * @return array Filtered query vars.
 */
function codice_add_maintenance_query_var( $vars ) {
	$vars[] = 'codice_maintenance';

	return $vars;
}
add_filter( 'query_vars', 'codice_add_maintenance_query_var' );

/**
 * Checks whether the requested public path is /manutencao.
 *
 * @return bool True when the current URI path matches the maintenance slug.
 */
function codice_request_path_is_maintenance() {
	$request_uri = isset( $_SERVER['REQUEST_URI'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REQUEST_URI'] ) ) : '';
	$request_path = wp_parse_url( $request_uri, PHP_URL_PATH );

	if ( ! is_string( $request_path ) ) {
		return false;
	}

	$home_path = wp_parse_url( home_url( '/' ), PHP_URL_PATH );
	if ( is_string( $home_path ) && '/' !== $home_path && 0 === strpos( $request_path, $home_path ) ) {
		$request_path = substr( $request_path, strlen( $home_path ) );
	}

	return 'manutencao' === trim( $request_path, '/' );
}

/**
 * Checks whether the current request is the public maintenance screen.
 *
 * @return bool True for /manutencao or the maintenance query var.
 */
function codice_is_maintenance_request() {
	if ( codice_request_path_is_maintenance() ) {
		return true;
	}

	if ( '1' === get_query_var( 'codice_maintenance' ) ) {
		return true;
	}

	return is_page( 'manutencao' );
}

/**
 * Checks whether the current request must bypass maintenance mode.
 *
 * @return bool True when the request should not be redirected.
 */
function codice_should_bypass_maintenance() {
	if ( is_admin() || wp_doing_ajax() || wp_doing_cron() ) {
		return true;
	}

	if ( is_user_logged_in() && current_user_can( 'manage_options' ) ) {
		return true;
	}

	if ( defined( 'REST_REQUEST' ) && REST_REQUEST ) {
		return true;
	}

	$request_uri  = isset( $_SERVER['REQUEST_URI'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REQUEST_URI'] ) ) : '';
	$request_path = wp_parse_url( $request_uri, PHP_URL_PATH );

	if ( ! is_string( $request_path ) ) {
		return false;
	}

	$allowed_prefixes = array(
		'/wp-admin',
		'/wp-login.php',
		'/wp-json',
		'/wp-cron.php',
		'/wp-content',
		'/wp-includes',
	);

	foreach ( $allowed_prefixes as $prefix ) {
		if ( 0 === strpos( $request_path, $prefix ) ) {
			return true;
		}
	}

	return false;
}

/**
 * Redirects public visitors to the maintenance screen when active.
 */
function codice_redirect_to_maintenance() {
	if ( ! CODICE_MAINTENANCE_MODE ) {
		return;
	}

	if ( codice_should_bypass_maintenance() || codice_is_maintenance_request() ) {
		return;
	}

	wp_safe_redirect( home_url( '/manutencao/' ), 302 );
	exit;
}
add_action( 'template_redirect', 'codice_redirect_to_maintenance', 0 );

/**
 * Loads the maintenance template for the theme-owned route.
 *
 * @param string $template Current resolved template.
 * @return string Template path.
 */
function codice_load_maintenance_template( $template ) {
	if ( ! codice_is_maintenance_request() ) {
		return $template;
	}

	$maintenance_template = locate_template( 'page-manutencao.php' );
	if ( $maintenance_template ) {
		return $maintenance_template;
	}

	return $template;
}
add_filter( 'template_include', 'codice_load_maintenance_template', 0 );
