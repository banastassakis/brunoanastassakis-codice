<?php
/**
 * inc/schema.php — Schema JSON-LD do tema
 *
 * Implementa schema para WebSite, Person e Article/BlogPosting,
 * sem duplicação ou inventar dados comerciais.
 *
 * @package codice
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Adiciona Schema JSON-LD no <head>.
 */
function codice_add_schema_json_ld() {
	if ( function_exists( 'codice_is_seo_plugin_active' ) && codice_is_seo_plugin_active() ) {
		return;
	}

	$schema = array();

	$person = array(
		'@context' => 'https://schema.org',
		'@type'    => 'Person',
		'name'     => 'Bruno Anastassakis',
		'url'      => home_url( '/' ),
		'sameAs'   => array( 'https://linkedin.com/in/brunoanastassakis' ),
	);

	if ( is_front_page() || is_home() ) {
		$website = array(
			'@context'        => 'https://schema.org',
			'@type'           => 'WebSite',
			'name'            => get_bloginfo( 'name' ),
			'url'             => home_url( '/' ),
			'description'     => esc_html__( 'Publicação editorial autoral sobre comunicação, tecnologia e produto.', 'codice' ),
			'potentialAction' => array(
				'@type'       => 'SearchAction',
				'target'      => home_url( '/?s={search_term_string}' ),
				'query-input' => 'required name=search_term_string',
			),
		);
		$schema[] = $website;
		$schema[] = $person;
	}

	if ( is_single() ) {
		global $post;

		$description = '';
		if ( has_excerpt() ) {
			$description = wp_strip_all_tags( get_the_excerpt() );
		} else {
			$description = wp_trim_words( wp_strip_all_tags( $post->post_content ), 30, '…' );
		}

		$article = array(
			'@context'         => 'https://schema.org',
			'@type'            => 'BlogPosting',
			'headline'         => get_the_title(),
			'description'      => $description,
			'datePublished'    => get_the_date( 'c' ),
			'dateModified'     => get_the_modified_date( 'c' ),
			'author'           => array(
				'@type' => 'Person',
				'name'  => get_the_author(),
				'url'   => home_url( '/sobre' ),
			),
			'mainEntityOfPage' => array(
				'@type' => 'WebPage',
				'@id'   => get_permalink(),
			),
		);

		if ( has_post_thumbnail() ) {
			$article['image'] = get_the_post_thumbnail_url( get_the_ID(), 'full' );
		}

		$categories = get_the_category();
		if ( ! empty( $categories ) ) {
			$article['articleSection'] = $categories[0]->name;
		}

		$schema[] = $article;
	}

	if ( ! empty( $schema ) ) {
		echo "\n<!-- Schema JSON-LD Códice -->\n";
		foreach ( $schema as $s ) {
			echo '<script type="application/ld+json">' . wp_json_encode( $s, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) . '</script>' . "\n";
		}
		echo "<!-- /Schema JSON-LD -->\n\n";
	}
}
add_action( 'wp_head', 'codice_add_schema_json_ld' );
