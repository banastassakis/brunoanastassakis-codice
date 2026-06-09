<?php
/**
 * inc/seo.php — SEO básico do tema
 *
 * Implementa meta description, canonical, Open Graph e Twitter Cards
 * direto no tema, caso não haja plugin de SEO ativo.
 *
 * @package codice
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Verifica se existe algum plugin de SEO ativo para evitar duplicação.
 *
 * @return bool True se houver plugin conhecido ativo.
 */
function codice_is_seo_plugin_active() {
	return defined( 'WPSEO_VERSION' ) ||
		class_exists( 'RankMath' ) ||
		defined( 'AIOSEO_VERSION' ) ||
		class_exists( 'The_SEO_Framework\Bootstrap' );
}

/**
 * Filtra as partes do título (<title>) gerenciado pelo WordPress (title-tag).
 *
 * @param array $title O array de partes do título (title, page, tagline, site).
 * @return array As partes modificadas.
 */
function codice_filter_document_title_parts( $title ) {
	if ( codice_is_seo_plugin_active() ) {
		return $title;
	}

	// Remove a tagline (descrição do site) de todas as páginas para evitar duplicações/poluição no título
	if ( isset( $title['tagline'] ) ) {
		unset( $title['tagline'] );
	}

	if ( function_exists( 'codice_is_maintenance_request' ) && codice_is_maintenance_request() ) {
		$title['title'] = esc_html__( 'Manutenção', 'codice' );
		$title['site']  = get_bloginfo( 'name' );
	} elseif ( is_search() ) {
		$title['title'] = esc_html__( 'Busca', 'codice' );
	} elseif ( is_404() ) {
		$title['title'] = esc_html__( 'Página não encontrada', 'codice' );
	}

	return $title;
}
add_filter( 'document_title_parts', 'codice_filter_document_title_parts' );

/**
 * Altera o caractere separador das partes do título do documento.
 *
 * @param string $sep Separador padrão.
 * @return string Novo separador.
 */
function codice_document_title_separator( $sep ) {
	return '—';
}
add_filter( 'document_title_separator', 'codice_document_title_separator' );

/**
 * Adiciona as meta tags básicas no <head>.
 */
function codice_add_seo_meta_tags() {
	if ( codice_is_seo_plugin_active() ) {
		return; // O tema cede a autoridade para o plugin.
	}

	$description = '';
	$url         = '';
	$og_type     = 'website';
	$image       = '';
	$title       = wp_get_document_title();
	$site_name   = get_bloginfo( 'name' );

	if ( function_exists( 'codice_is_maintenance_request' ) && codice_is_maintenance_request() ) {
		$url         = home_url( '/manutencao/' );
		$description = esc_html__( 'Este espaço está sendo preparado. Enquanto isso, os canais diretos seguem disponíveis.', 'codice' );
	} elseif ( is_singular() ) {
		global $post;
		$url = get_permalink();

		if ( has_excerpt() ) {
			$description = wp_strip_all_tags( get_the_excerpt() );
		} elseif ( ! empty( $post->post_content ) ) {
			$description = wp_trim_words( wp_strip_all_tags( $post->post_content ), 30, '…' );
		}

		// Fallback para a descrição do blog se o conteúdo e o resumo estiverem vazios
		if ( empty( $description ) ) {
			$description = wp_strip_all_tags( get_bloginfo( 'description' ) );
		}

		// Segundo fallback com a descrição padrão fixa do tema
		if ( empty( $description ) ) {
			$description = esc_html__( 'Ensaios, análises e reflexões sobre conteúdo, comunicação, eventos, IA e ecossistema editorial.', 'codice' );
		}

		if ( is_single() ) {
			$og_type = 'article';
		}

		if ( has_post_thumbnail() ) {
			$image = get_the_post_thumbnail_url( get_the_ID(), 'large' );
		}
	} elseif ( is_front_page() ) {
		$url         = home_url( '/' );
		$description = wp_strip_all_tags( get_bloginfo( 'description' ) );
		if ( empty( $description ) ) {
			$description = esc_html__( 'Ensaios, análises e reflexões sobre conteúdo, comunicação, eventos, IA e ecossistema editorial.', 'codice' );
		}
	} elseif ( is_home() ) {
		$url         = function_exists( 'codice_get_posts_index_url' ) ? codice_get_posts_index_url() : home_url( '/artigos/' );
		$description = esc_html__( 'Acervo de artigos sobre conteúdo, comunicação, eventos, IA e ecossistema editorial.', 'codice' );
	} elseif ( is_category() ) {
		$url         = get_category_link( get_queried_object_id() );
		$description = wp_strip_all_tags( category_description() );
		if ( empty( $description ) ) {
			/* translators: %s: nome da categoria */
			$description = sprintf( esc_html__( 'Artigos na categoria %s.', 'codice' ), single_cat_title( '', false ) );
		}
	} elseif ( is_search() ) {
		$url         = add_query_arg( 's', rawurlencode( get_search_query() ), home_url( '/' ) );
		$description = esc_html__( 'Resultados de busca na publicação.', 'codice' );
	} elseif ( is_404() ) {
		$url         = home_url( '/' );
		$description = esc_html__( 'Página não encontrada.', 'codice' );
	} else {
		$url         = home_url( '/' );
		$description = esc_html__( 'Publicação editorial autoral.', 'codice' );
	}

	$description = str_replace( array( "\r", "\n" ), ' ', $description );

	echo "\n<!-- SEO Básico Códice -->\n";
	
	if ( ! empty( $description ) ) {
		echo '<meta name="description" content="' . esc_attr( $description ) . '">' . "\n";
	}

	if ( ! empty( $url ) ) {
		echo '<link rel="canonical" href="' . esc_url( $url ) . '">' . "\n";
	}

	echo '<meta property="og:type" content="' . esc_attr( $og_type ) . '">' . "\n";
	echo '<meta property="og:title" content="' . esc_attr( $title ) . '">' . "\n";
	
	if ( ! empty( $description ) ) {
		echo '<meta property="og:description" content="' . esc_attr( $description ) . '">' . "\n";
	}
	
	if ( ! empty( $url ) ) {
		echo '<meta property="og:url" content="' . esc_url( $url ) . '">' . "\n";
	}
	
	echo '<meta property="og:site_name" content="' . esc_attr( $site_name ) . '">' . "\n";
	
	if ( ! empty( $image ) ) {
		echo '<meta property="og:image" content="' . esc_url( $image ) . '">' . "\n";
	}

	$twitter_card = ! empty( $image ) ? 'summary_large_image' : 'summary';
	echo '<meta name="twitter:card" content="' . esc_attr( $twitter_card ) . '">' . "\n";
	echo '<meta name="twitter:title" content="' . esc_attr( $title ) . '">' . "\n";
	
	if ( ! empty( $description ) ) {
		echo '<meta name="twitter:description" content="' . esc_attr( $description ) . '">' . "\n";
	}
	
	if ( ! empty( $image ) ) {
		echo '<meta name="twitter:image" content="' . esc_url( $image ) . '">' . "\n";
	}

	echo "<!-- /SEO Básico -->\n\n";
}
add_action( 'wp_head', 'codice_add_seo_meta_tags', 1 );
