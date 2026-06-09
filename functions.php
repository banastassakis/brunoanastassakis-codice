<?php
/**
 * functions.php — Bootstrap do tema Códice
 *
 * Este arquivo é o ponto de entrada do tema. Sua única responsabilidade
 * é incluir os arquivos de inc/ que registram os supports, menus e assets.
 * Nenhuma lógica de negócio aqui.
 *
 * @package codice
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Inclui os módulos de suporte e configuração do tema.
require_once get_template_directory() . '/inc/setup.php';
require_once get_template_directory() . '/inc/enqueue.php';
require_once get_template_directory() . '/inc/categories.php';
require_once get_template_directory() . '/inc/site-config.php';
require_once get_template_directory() . '/inc/contact-form.php';
require_once get_template_directory() . '/inc/page-options.php';
require_once get_template_directory() . '/inc/maintenance.php';
require_once get_template_directory() . '/inc/seo.php';
require_once get_template_directory() . '/inc/schema.php';
