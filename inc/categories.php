<?php
/**
 * inc/categories.php — Categorias editoriais fixas da v1
 *
 * Define as cinco categorias canônicas do Códice (nome, slug, subtítulo
 * e descrição editorial) e expõe helpers para consultar esses dados em
 * qualquer template.
 *
 * Regras:
 * - Não cria categorias no banco; o tema funciona com ou sem elas cadastradas.
 * - Subtítulo vem primeiro desta fonte; se a categoria existir no WP com
 *   descrição preenchida, o template pode preferir a descrição do WP.
 * - Prefixo codice_ em todas as funções.
 *
 * @package codice
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Retorna o array das cinco categorias editoriais fixas da v1.
 *
 * Cada item tem: 'name', 'slug', 'subtitle', 'description',
 * 'is_integrator' e 'relation'.
 *
 * @return array<int, array{name: string, slug: string, subtitle: string, description: string, is_integrator: bool, relation: string}>
 */
function codice_get_editorial_categories() {
	return array(
		array(
			'name'          => __( 'Conteúdo', 'codice' ),
			'slug'          => 'conteudo',
			'subtitle'      => __( 'Produto, estrutura e governança editorial.', 'codice' ),
			'description'   => __( 'Conteúdo tratado como produto editorial: estrutura, acervo, governança, experiência, ciclo de vida e organização do conhecimento.', 'codice' ),
			'is_integrator' => false,
			'relation'      => __( 'Camada de produto, estrutura e governança dentro do ecossistema editorial.', 'codice' ),
		),
		array(
			'name'          => __( 'Comunicação', 'codice' ),
			'slug'          => 'comunicacao',
			'subtitle'      => __( 'Linguagem, posicionamento e construção de autoridade.', 'codice' ),
			'description'   => __( 'Comunicação como linguagem pública: posicionamento, reputação, autoridade, marca, canais e clareza em mercados complexos.', 'codice' ),
			'is_integrator' => false,
			'relation'      => __( 'Camada de linguagem, canais e percepção dentro do ecossistema editorial.', 'codice' ),
		),
		array(
			'name'          => __( 'Eventos', 'codice' ),
			'slug'          => 'eventos',
			'subtitle'      => __( 'Curadoria, formatos e circulação de ideias.', 'codice' ),
			'description'   => __( 'Eventos como formatos editoriais e comunicacionais: curadoria, experiência, pauta, audiência e circulação de ideias.', 'codice' ),
			'is_integrator' => false,
			'relation'      => __( 'Camada de presença, curadoria e circulação dentro do ecossistema editorial.', 'codice' ),
		),
		array(
			'name'          => __( 'IA', 'codice' ),
			'slug'          => 'ia',
			'subtitle'      => __( 'Automação, inteligência operacional e fluxos de conteúdo.', 'codice' ),
			'description'   => __( 'IA como camada operacional: automação, pesquisa, organização, produção, análise, documentação e reaproveitamento de conteúdo.', 'codice' ),
			'is_integrator' => false,
			'relation'      => __( 'Camada de automação, pesquisa e inteligência operacional dentro do ecossistema editorial.', 'codice' ),
		),
		array(
			'name'          => __( 'Ecossistema', 'codice' ),
			'slug'          => 'ecossistema',
			'subtitle'      => __( 'Integração entre conteúdo, comunicação, canais, eventos, IA e operação.', 'codice' ),
			'description'   => __( 'Categoria-síntese sobre a integração entre conteúdo, comunicação, eventos, IA, canais, pessoas, decisões, fluxos e operação editorial.', 'codice' ),
			'is_integrator' => true,
			'relation'      => __( 'Eixo integrador que observa como as demais camadas funcionam em conjunto.', 'codice' ),
		),
	);
}

/**
 * Retorna o subtítulo de uma categoria pelo slug.
 *
 * @param string $slug Slug da categoria.
 * @return string Subtítulo ou string vazia se não encontrado.
 */
function codice_get_category_subtitle( $slug ) {
	$category = codice_get_category_by_slug( $slug );
	return ( $category ) ? $category['subtitle'] : '';
}

/**
 * Retorna a definição completa de uma categoria pelo slug.
 *
 * @param string $slug Slug da categoria.
 * @return array{name: string, slug: string, subtitle: string, description: string, is_integrator: bool, relation: string}|null Definição ou null.
 */
function codice_get_category_by_slug( $slug ) {
	foreach ( codice_get_editorial_categories() as $cat ) {
		if ( $cat['slug'] === $slug ) {
			return $cat;
		}
	}
	return null;
}

/**
 * Retorna as quatro categorias que funcionam como camadas do ecossistema.
 *
 * A relação é editorial, visual e estrutural na interface; as categorias
 * permanecem top-level no WordPress.
 *
 * @return array<int, array{name: string, slug: string, subtitle: string, description: string, is_integrator: bool, relation: string}>
 */
function codice_get_ecosystem_layer_categories() {
	return array_values(
		array_filter(
			codice_get_editorial_categories(),
			static function ( $cat ) {
				return empty( $cat['is_integrator'] );
			}
		)
	);
}

/**
 * Busca o termo WP correspondente ao slug, sem criar nada.
 *
 * Retorna o WP_Term se a categoria existir no banco, ou false caso
 * ainda não tenha sido cadastrada. O tema funciona em ambos os casos.
 *
 * @param string $slug Slug da categoria.
 * @return WP_Term|false
 */
function codice_get_wp_term_by_slug( $slug ) {
	$term = get_term_by( 'slug', $slug, 'category' );
	return ( $term instanceof WP_Term ) ? $term : false;
}
