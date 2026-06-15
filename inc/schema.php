<?php
/**
 * inc/schema.php - Schema.org JSON-LD do tema Codice.
 *
 * Emite um unico @graph por pagina, com @id estaveis e sem tipos
 * comerciais fora do escopo editorial da v1.
 *
 * @package codice
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Retorna a entidade Person usada como autor/publisher.
 *
 * @param string $person_id ID estavel da entidade Person.
 * @param string $site_url  URL canonica do site.
 * @return array<string, mixed> Entidade Person.
 */
function codice_schema_get_person( $person_id, $site_url ) {
	$linkedin_url = function_exists( 'codice_get_linkedin_url' ) ? codice_get_linkedin_url() : '';

	$person = array(
		'@type' => 'Person',
		'@id'   => $person_id,
		'name'  => function_exists( 'codice_get_person_name' ) ? codice_get_person_name() : get_bloginfo( 'name' ),
		'url'   => $site_url,
	);

	if ( $linkedin_url ) {
		$person['sameAs'] = array( $linkedin_url );
	}

	return $person;
}

/**
 * Retorna a entidade WebSite para a home.
 *
 * @param string $website_id ID estavel do WebSite.
 * @param string $person_id  ID estavel da Person.
 * @param string $site_url   URL canonica do site.
 * @return array<string, mixed> Entidade WebSite.
 */
function codice_schema_get_website( $website_id, $person_id, $site_url ) {
	return array(
		'@type'           => 'WebSite',
		'@id'             => $website_id,
		'name'            => get_bloginfo( 'name' ),
		'url'             => $site_url,
		'inLanguage'      => 'pt-BR',
		'publisher'       => array(
			'@id' => $person_id,
		),
		'author'          => array(
			'@id' => $person_id,
		),
		'potentialAction' => array(
			'@type'       => 'SearchAction',
			'target'      => array(
				'@type'       => 'EntryPoint',
				'urlTemplate' => home_url( '/?s={search_term_string}' ),
			),
			'query-input' => 'required name=search_term_string',
		),
	);
}

/**
 * Retorna a entidade de pagina para o contexto atual.
 *
 * @param string $page_id    ID estavel da pagina.
 * @param string $person_id  ID estavel da Person.
 * @param string $website_id ID estavel do WebSite.
 * @return array<string, mixed>|null Entidade de pagina ou null.
 */
function codice_schema_get_webpage( $page_id, $person_id, $website_id ) {
	if ( is_404() || ( function_exists( 'codice_is_maintenance_request' ) && codice_is_maintenance_request() ) ) {
		return null;
	}

	$description = function_exists( 'codice_get_meta_description' ) ? codice_get_meta_description() : get_bloginfo( 'description' );
	$url         = function_exists( 'codice_get_canonical_url' ) ? codice_get_canonical_url() : home_url( '/' );
	$title       = wp_get_document_title();
	$type        = 'WebPage';

	if ( is_home() ) {
		$type  = array( 'CollectionPage', 'Blog' );
		$title = esc_html__( 'Artigos', 'codice' );
	} elseif ( is_category() ) {
		$type  = 'CollectionPage';
		$title = single_cat_title( '', false );
	} elseif ( is_search() ) {
		$type  = 'SearchResultsPage';
		$title = esc_html__( 'Busca', 'codice' );
	} elseif ( is_front_page() ) {
		$type = 'WebPage';
	}

	$webpage = array(
		'@type'       => $type,
		'@id'         => $page_id,
		'url'         => $url,
		'name'        => $title,
		'description' => $description,
		'inLanguage'  => 'pt-BR',
		'isPartOf'    => array(
			'@id' => $website_id,
		),
		'author'      => array(
			'@id' => $person_id,
		),
	);

	if ( is_single() ) {
		$webpage['mainEntity'] = array(
			'@id' => untrailingslashit( get_permalink() ) . '#article',
		);
	}

	return $webpage;
}

/**
 * Retorna BlogPosting para posts individuais.
 *
 * @param string $person_id ID estavel da Person.
 * @return array<string, mixed>|null Entidade BlogPosting ou null.
 */
function codice_schema_get_blog_posting( $person_id ) {
	if ( ! is_single() ) {
		return null;
	}

	$description = function_exists( 'codice_get_meta_description' ) ? codice_get_meta_description() : '';
	$permalink   = get_permalink();
	$permalink_id = untrailingslashit( $permalink );
	$headline    = get_the_title();

	if ( function_exists( 'codice_seo_custom_title' ) ) {
		$seo_headline = codice_seo_custom_title();
		if ( '' !== $seo_headline ) {
			$headline = $seo_headline;
		}
	}

	$article = array(
		'@type'            => 'BlogPosting',
		'@id'              => $permalink_id . '#article',
		'headline'         => $headline,
		'description'      => $description,
		'datePublished'    => get_the_date( 'c' ),
		'dateModified'     => get_the_modified_date( 'c' ),
		'author'           => array(
			'@id' => $person_id,
		),
		'publisher'        => array(
			'@id' => $person_id,
		),
		'mainEntityOfPage' => array(
			'@id' => $permalink_id . '#webpage',
		),
		'inLanguage'       => 'pt-BR',
	);

	// Imagem coerente com SEO: imagem destacada e, na ausencia dela,
	// o override de imagem Open Graph. Sem chave image quando nao houver imagem.
	$article_image = '';
	if ( has_post_thumbnail() ) {
		$article_image = get_the_post_thumbnail_url( get_the_ID(), 'full' );
	} elseif ( function_exists( 'codice_seo_image_url_from_meta' ) ) {
		$article_image = codice_seo_image_url_from_meta( '_codice_og_image_id', 'full' );
	}

	if ( $article_image ) {
		$article['image'] = $article_image;
	}

	$categories = get_the_category();
	if ( ! empty( $categories ) ) {
		$article['articleSection'] = $categories[0]->name;
	}

	return $article;
}

/**
 * Retorna BreadcrumbList quando houver breadcrumbs logicos/visiveis.
 *
 * @return array<string, mixed>|null Entidade BreadcrumbList ou null.
 */
function codice_schema_get_breadcrumb_list() {
	if ( ! function_exists( 'codice_get_breadcrumb_items' ) ) {
		return null;
	}

	$items = codice_get_breadcrumb_items();
	if ( count( $items ) < 2 || is_404() ) {
		return null;
	}

	return array(
		'@type'           => 'BreadcrumbList',
		'@id'             => ( function_exists( 'codice_get_canonical_url' ) ? codice_get_canonical_url() : home_url( '/' ) ) . '#breadcrumb',
		'itemListElement' => array_map(
			static function ( $item, $index ) {
				$list_item = array(
					'@type'    => 'ListItem',
					'position' => $index + 1,
					'name'     => $item['label'],
				);

				if ( ! empty( $item['url'] ) ) {
					$list_item['item'] = $item['url'];
				}

				return $list_item;
			},
			$items,
			array_keys( $items )
		),
	);
}

/**
 * Adiciona Schema JSON-LD no head.
 */
function codice_add_schema_json_ld() {
	if ( function_exists( 'codice_is_seo_plugin_active' ) && codice_is_seo_plugin_active() ) {
		return;
	}

	if ( is_404() || ( function_exists( 'codice_is_maintenance_request' ) && codice_is_maintenance_request() ) ) {
		return;
	}

	$site_url   = home_url( '/' );
	$person_id = $site_url . '#person';
	$website_id = $site_url . '#website';
	$page_url   = function_exists( 'codice_get_canonical_url' ) ? codice_get_canonical_url() : home_url( '/' );
	$page_id    = untrailingslashit( $page_url ) . '#webpage';

	$graph = array();
	$graph[] = codice_schema_get_person( $person_id, $site_url );
	$graph[] = codice_schema_get_website( $website_id, $person_id, $site_url );

	$webpage = codice_schema_get_webpage( $page_id, $person_id, $website_id );
	if ( $webpage ) {
		$graph[] = $webpage;
	}

	$article = codice_schema_get_blog_posting( $person_id );
	if ( $article ) {
		$graph[] = $article;
	}

	$breadcrumbs = codice_schema_get_breadcrumb_list();
	if ( $breadcrumbs ) {
		$graph[] = $breadcrumbs;
	}

	if ( empty( $graph ) ) {
		return;
	}

	$schema = array(
		'@context' => 'https://schema.org',
		'@graph'   => $graph,
	);

	echo "\n<!-- Schema JSON-LD Codice -->\n";
	echo '<script type="application/ld+json">' . wp_json_encode( $schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) . '</script>' . "\n";
	echo "<!-- /Schema JSON-LD Codice -->\n\n";
}
add_action( 'wp_head', 'codice_add_schema_json_ld' );
