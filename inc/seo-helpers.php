<?php
/**
 * inc/seo-helpers.php - Helpers reutilizaveis de SEO do tema Codice.
 *
 * Reune a leitura dos campos customizados de SEO (post meta e term meta) e
 * resolve titulo, descricao, canonical, robots e imagens sociais com fallback
 * seguro. Consumido por inc/seo.php (saida publica) e inc/schema.php.
 *
 * Nenhuma tag e emitida aqui; este arquivo so resolve valores.
 *
 * @package codice
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Lista de chaves de meta de SEO compartilhadas entre post meta e term meta.
 *
 * @return array<int, string> Chaves de meta (com prefixo _codice_).
 */
function codice_seo_meta_keys() {
	return array(
		'_codice_seo_title',
		'_codice_seo_description',
		'_codice_canonical_url',
		'_codice_robots_index',
		'_codice_robots_follow',
		'_codice_og_title',
		'_codice_og_description',
		'_codice_og_image_id',
		'_codice_twitter_title',
		'_codice_twitter_description',
		'_codice_twitter_image_id',
		'_codice_seo_focus_keyword',
	);
}

/**
 * Allowlist de valores aceitos para a diretiva index.
 *
 * @return array<int, string> Valores validos.
 */
function codice_seo_index_choices() {
	return array( '', 'index', 'noindex' );
}

/**
 * Allowlist de valores aceitos para a diretiva follow.
 *
 * @return array<int, string> Valores validos.
 */
function codice_seo_follow_choices() {
	return array( '', 'follow', 'nofollow' );
}

/**
 * Retorna o valor bruto de um campo de SEO para o objeto consultado no front.
 *
 * Resolve automaticamente entre post meta (singular) e term meta (categoria
 * ou outra taxonomia consultavel). Retorna string vazia quando nao se aplica.
 *
 * @param string $key Chave de meta com prefixo _codice_.
 * @return string Valor bruto armazenado ou string vazia.
 */
function codice_seo_object_meta( $key ) {
	if ( is_home() && ! is_front_page() ) {
		$posts_page_id = (int) get_option( 'page_for_posts' );
		if ( $posts_page_id > 0 ) {
			$value = get_post_meta( $posts_page_id, $key, true );
			return is_string( $value ) ? $value : '';
		}
	}

	if ( is_front_page() && 'page' === get_option( 'show_on_front' ) ) {
		$front_page_id = (int) get_option( 'page_on_front' );
		if ( $front_page_id > 0 ) {
			$value = get_post_meta( $front_page_id, $key, true );
			return is_string( $value ) ? $value : '';
		}
	}

	if ( is_singular() ) {
		$value = get_post_meta( get_queried_object_id(), $key, true );
		return is_string( $value ) ? $value : '';
	}

	if ( is_category() || is_tag() || is_tax() ) {
		$value = get_term_meta( get_queried_object_id(), $key, true );
		return is_string( $value ) ? $value : '';
	}

	return '';
}

/**
 * Resolve a URL de uma imagem social a partir de um ID de anexo.
 *
 * @param string $key  Chave do campo que guarda o ID da imagem.
 * @param string $size Tamanho desejado da imagem.
 * @return string URL da imagem ou string vazia.
 */
function codice_seo_image_url_from_meta( $key, $size = 'large' ) {
	$image_id = absint( codice_seo_object_meta( $key ) );

	if ( ! $image_id ) {
		return '';
	}

	$url = wp_get_attachment_image_url( $image_id, $size );

	return $url ? $url : '';
}

/**
 * Retorna o SEO title customizado do objeto atual, se preenchido.
 *
 * @return string SEO title ou string vazia.
 */
function codice_seo_custom_title() {
	return trim( codice_seo_object_meta( '_codice_seo_title' ) );
}

/**
 * Retorna a meta description customizada do objeto atual, se preenchida.
 *
 * @return string Descricao ou string vazia.
 */
function codice_seo_custom_description() {
	return trim( codice_seo_object_meta( '_codice_seo_description' ) );
}

/**
 * Retorna a canonical customizada do objeto atual, se preenchida.
 *
 * @return string URL canonica ou string vazia.
 */
function codice_seo_custom_canonical() {
	return esc_url_raw( trim( codice_seo_object_meta( '_codice_canonical_url' ) ) );
}

/**
 * Retorna o titulo Open Graph resolvido (override ou fallback informado).
 *
 * @param string $fallback Titulo de fallback.
 * @return string Titulo Open Graph.
 */
function codice_seo_og_title( $fallback ) {
	$value = trim( codice_seo_object_meta( '_codice_og_title' ) );

	return '' !== $value ? $value : $fallback;
}

/**
 * Retorna a descricao Open Graph resolvida (override ou fallback informado).
 *
 * @param string $fallback Descricao de fallback.
 * @return string Descricao Open Graph.
 */
function codice_seo_og_description( $fallback ) {
	$value = trim( codice_seo_object_meta( '_codice_og_description' ) );

	return '' !== $value ? $value : $fallback;
}

/**
 * Retorna a imagem Open Graph resolvida (override ou fallback informado).
 *
 * @param string $fallback URL de fallback.
 * @return string URL da imagem Open Graph.
 */
function codice_seo_og_image( $fallback ) {
	$value = codice_seo_image_url_from_meta( '_codice_og_image_id' );

	return '' !== $value ? $value : $fallback;
}

/**
 * Retorna o titulo do Twitter/X resolvido (override ou fallback informado).
 *
 * @param string $fallback Titulo de fallback.
 * @return string Titulo Twitter/X.
 */
function codice_seo_twitter_title( $fallback ) {
	$value = trim( codice_seo_object_meta( '_codice_twitter_title' ) );

	return '' !== $value ? $value : $fallback;
}

/**
 * Retorna a descricao do Twitter/X resolvida (override ou fallback informado).
 *
 * @param string $fallback Descricao de fallback.
 * @return string Descricao Twitter/X.
 */
function codice_seo_twitter_description( $fallback ) {
	$value = trim( codice_seo_object_meta( '_codice_twitter_description' ) );

	return '' !== $value ? $value : $fallback;
}

/**
 * Retorna a imagem do Twitter/X resolvida (override ou fallback informado).
 *
 * @param string $fallback URL de fallback.
 * @return string URL da imagem Twitter/X.
 */
function codice_seo_twitter_image( $fallback ) {
	$value = codice_seo_image_url_from_meta( '_codice_twitter_image_id' );

	return '' !== $value ? $value : $fallback;
}

/**
 * Sanitiza um valor de SEO conforme a chave informada.
 *
 * Usada tanto no salvamento de post meta quanto de term meta para manter
 * regras unicas de sanitizacao.
 *
 * @param string $key   Chave de meta com prefixo _codice_.
 * @param mixed  $value Valor bruto recebido.
 * @return string Valor sanitizado.
 */
function codice_seo_sanitize_value( $key, $value ) {
	switch ( $key ) {
		case '_codice_seo_description':
		case '_codice_og_description':
		case '_codice_twitter_description':
			return sanitize_textarea_field( wp_unslash( (string) $value ) );

		case '_codice_canonical_url':
			return esc_url_raw( wp_unslash( (string) $value ) );

		case '_codice_robots_index':
			$value = sanitize_text_field( wp_unslash( (string) $value ) );
			return in_array( $value, codice_seo_index_choices(), true ) ? $value : '';

		case '_codice_robots_follow':
			$value = sanitize_text_field( wp_unslash( (string) $value ) );
			return in_array( $value, codice_seo_follow_choices(), true ) ? $value : '';

		case '_codice_og_image_id':
		case '_codice_twitter_image_id':
			return (string) absint( $value );

		default:
			return sanitize_text_field( wp_unslash( (string) $value ) );
	}
}
