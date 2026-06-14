<?php
/**
 * inc/site-config.php — Small configurable theme values.
 *
 * Keeps reusable URLs and contact details out of templates. Production can
 * override these values with filters without editing template files.
 *
 * @package codice
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Returns the public contact email.
 *
 * @return string Contact email.
 */
function codice_get_contact_email() {
	$email = apply_filters( 'codice_contact_email', 'contato@brunoanastassakis.com' );
	$email = sanitize_email( $email );

	return is_email( $email ) ? $email : get_option( 'admin_email' );
}

/**
 * Returns the public LinkedIn URL.
 *
 * @return string LinkedIn URL.
 */
function codice_get_linkedin_url() {
	$url = apply_filters( 'codice_linkedin_url', 'https://www.linkedin.com/in/brunoanastassakis/' );

	return esc_url_raw( $url );
}

/**
 * Returns the public WhatsApp URL used on the maintenance screen.
 *
 * @return string WhatsApp URL, or empty when disabled.
 */
function codice_get_whatsapp_url() {
	$url = apply_filters( 'codice_whatsapp_url', '' );

	return esc_url_raw( $url );
}

/**
 * Returns the Articles page URL with safe fallbacks.
 *
 * @return string Articles URL.
 */
function codice_get_posts_index_url() {
	$posts_page_id = (int) get_option( 'page_for_posts' );

	if ( $posts_page_id > 0 ) {
		$url = get_permalink( $posts_page_id );
		if ( $url ) {
			return $url;
		}
	}

	return home_url( '/artigos/' );
}
