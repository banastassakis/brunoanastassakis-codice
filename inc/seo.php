<?php
/**
 * inc/seo.php - SEO tecnico do tema Codice.
 *
 * Centraliza title parts, meta description, canonical, Open Graph,
 * Twitter Card, robots meta e breadcrumbs visiveis.
 *
 * @package codice
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Verifica se existe plugin de SEO ativo para evitar duplicacao.
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
 * Retorna o nome publico da pessoa autora.
 *
 * @return string Nome da pessoa autora.
 */
function codice_get_person_name() {
	$name = apply_filters( 'codice_person_name', 'Bruno Anastassakis' );
	$name = sanitize_text_field( $name );

	return $name ? $name : get_bloginfo( 'name' );
}

/**
 * Retorna a descricao padrao, curta e neutra, da publicacao.
 *
 * @return string Descricao padrao.
 */
function codice_get_default_meta_description() {
	return esc_html__( 'Publicação editorial autoral sobre conteúdo, comunicação, eventos, IA e ecossistema editorial.', 'codice' );
}

/**
 * Normaliza descricoes para uso em meta tags.
 *
 * @param string $description Descricao bruta.
 * @return string Descricao limpa.
 */
function codice_normalize_meta_description( $description ) {
	$description = wp_strip_all_tags( (string) $description );
	$description = preg_replace( '/\s+/', ' ', $description );
	$description = trim( $description );

	if ( '' === $description ) {
		return '';
	}

	return wp_html_excerpt( $description, 160, '&hellip;' );
}

/**
 * Retorna a meta description adequada ao contexto atual.
 *
 * @return string Meta description.
 */
function codice_get_meta_description() {
	if ( function_exists( 'codice_is_maintenance_request' ) && codice_is_maintenance_request() ) {
		return esc_html__( 'Este espaço está sendo preparado. Enquanto isso, os canais diretos seguem disponíveis.', 'codice' );
	}

	if ( is_singular() ) {
		if ( has_excerpt() ) {
			return codice_normalize_meta_description( get_the_excerpt() );
		}

		if ( is_page( 'sobre' ) ) {
			return esc_html__( 'Página sobre o autor e o escopo editorial desta publicação.', 'codice' );
		}

		if ( is_page( 'contato' ) ) {
			return esc_html__( 'Canal de contato para conversas relacionadas aos temas da publicação.', 'codice' );
		}

		if ( is_page( 'manutencao' ) ) {
			return esc_html__( 'Este espaço está sendo preparado. Enquanto isso, os canais diretos seguem disponíveis.', 'codice' );
		}

		return codice_get_default_meta_description();
	}

	if ( is_front_page() ) {
		$blog_description = codice_normalize_meta_description( get_bloginfo( 'description' ) );
		return $blog_description ? $blog_description : codice_get_default_meta_description();
	}

	if ( is_home() ) {
		return esc_html__( 'Acervo de artigos sobre conteúdo, comunicação, eventos, IA e ecossistema editorial.', 'codice' );
	}

	if ( is_category() ) {
		$description = codice_normalize_meta_description( category_description() );
		if ( $description ) {
			return $description;
		}

		/* translators: %s: nome da categoria. */
		return sprintf( esc_html__( 'Artigos na categoria %s.', 'codice' ), single_cat_title( '', false ) );
	}

	if ( is_search() ) {
		return esc_html__( 'Resultados da busca interna da publicação.', 'codice' );
	}

	if ( is_404() ) {
		return esc_html__( 'Página não encontrada nesta publicação.', 'codice' );
	}

	return codice_get_default_meta_description();
}

/**
 * Retorna a URL canonica adequada ao contexto atual.
 *
 * @return string URL canonica.
 */
function codice_get_canonical_url() {
	if ( function_exists( 'codice_is_maintenance_request' ) && codice_is_maintenance_request() ) {
		return home_url( '/manutencao/' );
	}

	if ( is_singular() ) {
		return get_permalink();
	}

	if ( is_front_page() ) {
		return home_url( '/' );
	}

	if ( is_home() ) {
		if ( is_paged() ) {
			return get_pagenum_link( max( 1, (int) get_query_var( 'paged' ) ) );
		}

		return function_exists( 'codice_get_posts_index_url' ) ? codice_get_posts_index_url() : home_url( '/artigos/' );
	}

	if ( is_category() ) {
		if ( is_paged() ) {
			return get_pagenum_link( max( 1, (int) get_query_var( 'paged' ) ) );
		}

		return get_category_link( get_queried_object_id() );
	}

	if ( is_search() ) {
		return add_query_arg( 's', get_search_query( false ), home_url( '/' ) );
	}

	if ( is_404() ) {
		return home_url( '/' );
	}

	return home_url( '/' );
}

/**
 * Retorna uma imagem social padrao caso tenha sido configurada por filtro.
 *
 * @return string URL da imagem ou string vazia.
 */
function codice_get_default_social_image_url() {
	$image = apply_filters( 'codice_default_social_image_url', '' );
	$image = esc_url_raw( $image );

	return $image ? $image : '';
}

/**
 * Retorna a imagem social adequada ao contexto atual.
 *
 * @return string URL da imagem ou string vazia.
 */
function codice_get_social_image_url() {
	if ( is_singular() && has_post_thumbnail() ) {
		$image = get_the_post_thumbnail_url( get_the_ID(), 'large' );
		return $image ? $image : '';
	}

	return codice_get_default_social_image_url();
}

/**
 * Filtra as partes do title tag gerenciado pelo WordPress.
 *
 * @param array $title Partes do titulo.
 * @return array Partes filtradas.
 */
function codice_filter_document_title_parts( $title ) {
	if ( codice_is_seo_plugin_active() ) {
		return $title;
	}

	unset( $title['tagline'] );

	if ( function_exists( 'codice_is_maintenance_request' ) && codice_is_maintenance_request() ) {
		$title['title'] = esc_html__( 'Manutenção', 'codice' );
		$title['site']  = get_bloginfo( 'name' );
		return $title;
	}

	if ( is_front_page() ) {
		$title['title'] = get_bloginfo( 'name' );
		unset( $title['site'] );
	} elseif ( is_home() ) {
		$title['title'] = esc_html__( 'Artigos', 'codice' );
	} elseif ( is_search() ) {
		$search_query = get_search_query( false );
		$title['title'] = $search_query
			? sprintf(
				/* translators: %s: termo buscado. */
				esc_html__( 'Busca por %s', 'codice' ),
				$search_query
			)
			: esc_html__( 'Busca', 'codice' );
	} elseif ( is_404() ) {
		$title['title'] = esc_html__( 'Página não encontrada', 'codice' );
	}

	return $title;
}
add_filter( 'document_title_parts', 'codice_filter_document_title_parts' );

/**
 * Altera o separador do title tag.
 *
 * @return string Separador.
 */
function codice_document_title_separator( $sep ) {
	unset( $sep );

	return '-';
}
add_filter( 'document_title_separator', 'codice_document_title_separator' );

/**
 * Ajusta robots meta para contextos que nao devem ser indexados.
 *
 * @param array $robots Diretivas do WordPress.
 * @return array Diretivas filtradas.
 */
function codice_filter_robots_meta( $robots ) {
	if ( ( function_exists( 'codice_is_maintenance_request' ) && codice_is_maintenance_request() ) || is_search() ) {
		unset( $robots['index'] );
		$robots['noindex'] = true;
		$robots['follow']  = true;
	}

	return $robots;
}
add_filter( 'wp_robots', 'codice_filter_robots_meta' );

/**
 * Adiciona as meta tags basicas no head.
 */
function codice_add_seo_meta_tags() {
	if ( codice_is_seo_plugin_active() ) {
		return;
	}

	$description = codice_get_meta_description();
	$url         = codice_get_canonical_url();
	$image       = codice_get_social_image_url();
	$title       = wp_get_document_title();
	$site_name   = get_bloginfo( 'name' );
	$og_type     = is_single() ? 'article' : 'website';

	echo "\n<!-- SEO Codice -->\n";

	if ( $description ) {
		echo '<meta name="description" content="' . esc_attr( $description ) . '">' . "\n";
	}

	if ( $url ) {
		echo '<link rel="canonical" href="' . esc_url( $url ) . '">' . "\n";
	}

	echo '<meta property="og:type" content="' . esc_attr( $og_type ) . '">' . "\n";
	echo '<meta property="og:title" content="' . esc_attr( $title ) . '">' . "\n";

	if ( $description ) {
		echo '<meta property="og:description" content="' . esc_attr( $description ) . '">' . "\n";
	}

	if ( $url ) {
		echo '<meta property="og:url" content="' . esc_url( $url ) . '">' . "\n";
	}

	echo '<meta property="og:site_name" content="' . esc_attr( $site_name ) . '">' . "\n";

	if ( $image ) {
		echo '<meta property="og:image" content="' . esc_url( $image ) . '">' . "\n";
	}

	echo '<meta name="twitter:card" content="' . esc_attr( $image ? 'summary_large_image' : 'summary' ) . '">' . "\n";
	echo '<meta name="twitter:title" content="' . esc_attr( $title ) . '">' . "\n";

	if ( $description ) {
		echo '<meta name="twitter:description" content="' . esc_attr( $description ) . '">' . "\n";
	}

	if ( $image ) {
		echo '<meta name="twitter:image" content="' . esc_url( $image ) . '">' . "\n";
	}

	echo "<!-- /SEO Codice -->\n\n";
}
add_action( 'wp_head', 'codice_add_seo_meta_tags', 1 );

/**
 * Retorna os itens de breadcrumb para o contexto atual.
 *
 * @return array<int, array{label:string,url:string,current:bool}>
 */
function codice_get_breadcrumb_items() {
	$items = array(
		array(
			'label'   => esc_html__( 'Início', 'codice' ),
			'url'     => home_url( '/' ),
			'current' => false,
		),
	);

	if ( is_front_page() ) {
		return array();
	}

	if ( is_home() ) {
		$items[] = array(
			'label'   => esc_html__( 'Artigos', 'codice' ),
			'url'     => function_exists( 'codice_get_posts_index_url' ) ? codice_get_posts_index_url() : home_url( '/artigos/' ),
			'current' => true,
		);
		return $items;
	}

	if ( is_single() ) {
		$items[] = array(
			'label'   => esc_html__( 'Artigos', 'codice' ),
			'url'     => function_exists( 'codice_get_posts_index_url' ) ? codice_get_posts_index_url() : home_url( '/artigos/' ),
			'current' => false,
		);

		$categories = get_the_category();
		if ( ! empty( $categories ) ) {
			$items[] = array(
				'label'   => $categories[0]->name,
				'url'     => get_category_link( $categories[0]->term_id ),
				'current' => false,
			);
		}

		$items[] = array(
			'label'   => get_the_title(),
			'url'     => get_permalink(),
			'current' => true,
		);
		return $items;
	}

	if ( is_category() ) {
		$items[] = array(
			'label'   => esc_html__( 'Artigos', 'codice' ),
			'url'     => function_exists( 'codice_get_posts_index_url' ) ? codice_get_posts_index_url() : home_url( '/artigos/' ),
			'current' => false,
		);
		$items[] = array(
			'label'   => single_cat_title( '', false ),
			'url'     => get_category_link( get_queried_object_id() ),
			'current' => true,
		);
		return $items;
	}

	if ( is_search() ) {
		$items[] = array(
			'label'   => esc_html__( 'Busca', 'codice' ),
			'url'     => codice_get_canonical_url(),
			'current' => true,
		);
		return $items;
	}

	if ( is_page() ) {
		$items[] = array(
			'label'   => get_the_title(),
			'url'     => get_permalink(),
			'current' => true,
		);
		return $items;
	}

	if ( is_404() ) {
		$items[] = array(
			'label'   => esc_html__( 'Página não encontrada', 'codice' ),
			'url'     => '',
			'current' => true,
		);
		return $items;
	}

	return array();
}

/**
 * Renderiza breadcrumbs discretos e acessiveis.
 */
function codice_render_breadcrumbs() {
	$items = codice_get_breadcrumb_items();

	if ( count( $items ) < 2 ) {
		return;
	}
	?>
	<nav class="breadcrumbs" aria-label="<?php esc_attr_e( 'Caminho de navegação', 'codice' ); ?>">
		<ol class="breadcrumbs__list" role="list">
			<?php foreach ( $items as $item ) : ?>
				<li class="breadcrumbs__item">
					<?php if ( empty( $item['current'] ) && ! empty( $item['url'] ) ) : ?>
						<a class="breadcrumbs__link" href="<?php echo esc_url( $item['url'] ); ?>">
							<?php echo esc_html( $item['label'] ); ?>
						</a>
					<?php else : ?>
						<span class="breadcrumbs__current" aria-current="page">
							<?php echo esc_html( $item['label'] ); ?>
						</span>
					<?php endif; ?>
				</li>
			<?php endforeach; ?>
		</ol>
	</nav>
	<?php
}
