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

	if ( function_exists( 'codice_is_maintenance_request' ) && codice_is_maintenance_request() ) {
		return;
	}

	$schema = array();
	$site_url = home_url( '/' );
	$site_name = get_bloginfo( 'name' );
	$linkedin_url = function_exists( 'codice_get_linkedin_url' ) ? codice_get_linkedin_url() : '';

	$person = array(
		'@context' => 'https://schema.org',
		'@type'    => 'Person',
		'@id'      => $site_url . '#person',
		'name'     => $site_name,
		'url'      => $site_url,
	);

	if ( $linkedin_url ) {
		$person['sameAs'] = array( $linkedin_url );
	}

	$website_description = wp_strip_all_tags( get_bloginfo( 'description' ) );
	if ( empty( $website_description ) ) {
		$website_description = esc_html__( 'Publicação editorial autoral sobre conteúdo, comunicação, eventos, IA e ecossistema editorial.', 'codice' );
	}

	$schema[] = array(
		'@context'        => 'https://schema.org',
		'@type'           => 'WebSite',
		'@id'             => $site_url . '#website',
		'name'            => $site_name,
		'url'             => $site_url,
		'description'     => $website_description,
		'publisher'       => array(
			'@id' => $site_url . '#person',
		),
		'potentialAction' => array(
			'@type'       => 'SearchAction',
			'target'      => home_url( '/?s={search_term_string}' ),
			'query-input' => 'required name=search_term_string',
		),
	);
	$schema[] = $person;

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
			'@id'              => get_permalink() . '#article',
			'headline'         => get_the_title(),
			'description'      => $description,
			'datePublished'    => get_the_date( 'c' ),
			'dateModified'     => get_the_modified_date( 'c' ),
			'author'           => array(
				'@id' => $site_url . '#person',
			),
			'publisher'        => array(
				'@id' => $site_url . '#person',
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

	$breadcrumbs = array(
		array(
			'name' => esc_html__( 'Início', 'codice' ),
			'url'  => $site_url,
		),
	);

	if ( is_single() ) {
		$posts_url = function_exists( 'codice_get_posts_index_url' ) ? codice_get_posts_index_url() : home_url( '/artigos/' );
		$breadcrumbs[] = array(
			'name' => esc_html__( 'Artigos', 'codice' ),
			'url'  => $posts_url,
		);
		$breadcrumbs[] = array(
			'name' => get_the_title(),
			'url'  => get_permalink(),
		);
	} elseif ( is_category() ) {
		$breadcrumbs[] = array(
			'name' => single_cat_title( '', false ),
			'url'  => get_category_link( get_queried_object_id() ),
		);
	} elseif ( is_search() ) {
		$breadcrumbs[] = array(
			'name' => esc_html__( 'Busca', 'codice' ),
			'url'  => add_query_arg( 's', rawurlencode( get_search_query() ), $site_url ),
		);
	}

	if ( count( $breadcrumbs ) > 1 ) {
		$schema[] = array(
			'@context'        => 'https://schema.org',
			'@type'           => 'BreadcrumbList',
			'itemListElement' => array_map(
				static function ( $item, $index ) {
					return array(
						'@type'    => 'ListItem',
						'position' => $index + 1,
						'name'     => $item['name'],
						'item'     => $item['url'],
					);
				},
				$breadcrumbs,
				array_keys( $breadcrumbs )
			),
		);
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
