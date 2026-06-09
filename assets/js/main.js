/**
 * assets/js/main.js — Script principal do tema Códice
 *
 * Mínimo e adiado (defer). Nenhuma biblioteca externa.
 * Respeita prefers-reduced-motion.
 *
 * Etapa 1: estrutura segura sem comportamento pesado.
 * O comportamento de menu mobile e demais interações
 * serão adicionados na Etapa 2 — Sistema visual.
 *
 * @package codice
 */

( function () {
	'use strict';

	/**
	 * Verifica preferência de movimento do sistema operacional.
	 *
	 * @type {boolean}
	 */
	var prefersReducedMotion =
		window.matchMedia( '(prefers-reduced-motion: reduce)' ).matches;

	/**
	 * Ponto de entrada após o DOM estar pronto.
	 */
	function init() {
		// Placeholder: menu mobile toggle será implementado na Etapa 2.
		// Placeholder: busca acessível será implementada na Etapa 3.
	}

	// Aguarda o DOM estar completamente carregado.
	if ( document.readyState === 'loading' ) {
		document.addEventListener( 'DOMContentLoaded', init );
	} else {
		init();
	}
}() );
