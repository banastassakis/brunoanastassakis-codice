/**
 * assets/js/seo-admin.js - Interacoes minimas do SEO admin do tema Codice.
 *
 * Sem jQuery e sem bibliotecas externas. Cobre o media uploader nativo
 * (wp.media) para os campos de imagem e contadores simples de caracteres.
 *
 * @package codice
 */

( function () {
	'use strict';

	var settings = window.codiceSeoAdmin || {};

	/**
	 * Inicializa um campo de imagem com o media uploader nativo.
	 *
	 * @param {HTMLElement} wrap Elemento [data-codice-seo-image].
	 */
	function initImageField( wrap ) {
		var input   = wrap.querySelector( '[data-codice-seo-image-input]' );
		var preview = wrap.querySelector( '[data-codice-seo-image-preview]' );
		var select  = wrap.querySelector( '[data-codice-seo-image-select]' );
		var remove  = wrap.querySelector( '[data-codice-seo-image-remove]' );

		if ( ! input || ! select || ! preview ) {
			return;
		}

		var frame = null;

		select.addEventListener( 'click', function ( event ) {
			event.preventDefault();

			if ( ! window.wp || ! window.wp.media ) {
				return;
			}

			if ( frame ) {
				frame.open();
				return;
			}

			frame = window.wp.media( {
				title: settings.frameTitle || 'Selecionar imagem',
				button: { text: settings.frameButton || 'Usar esta imagem' },
				library: { type: 'image' },
				multiple: false
			} );

			frame.on( 'select', function () {
				var attachment = frame.state().get( 'selection' ).first().toJSON();
				var url = attachment.url;

				if ( attachment.sizes && attachment.sizes.medium ) {
					url = attachment.sizes.medium.url;
				}

				input.value = attachment.id;
				preview.innerHTML = '';

				var img = document.createElement( 'img' );
				img.src = url;
				img.alt = '';
				preview.appendChild( img );

				if ( remove ) {
					remove.removeAttribute( 'hidden' );
					remove.classList.remove( 'hidden' );
				}
			} );

			frame.open();
		} );

		if ( remove ) {
			remove.addEventListener( 'click', function ( event ) {
				event.preventDefault();
				input.value = '';
				preview.innerHTML = '';
				remove.setAttribute( 'hidden', 'hidden' );
				remove.classList.add( 'hidden' );
			} );
		}
	}

	/**
	 * Inicializa um contador simples de caracteres.
	 *
	 * @param {HTMLElement} field Input ou textarea com data-codice-counter.
	 */
	function initCounter( field ) {
		var max = parseInt( field.getAttribute( 'data-codice-counter' ), 10 );
		var counter = field.parentNode
			? field.parentNode.querySelector( '.codice-seo-field__counter' )
			: null;

		if ( ! counter ) {
			return;
		}

		function update() {
			var length = field.value.length;
			counter.textContent = length + ( max ? ' / ' + max : '' );
			if ( max && length > max ) {
				counter.classList.add( 'is-over' );
			} else {
				counter.classList.remove( 'is-over' );
			}
		}

		field.addEventListener( 'input', update );
		update();
	}

	/**
	 * Ponto de entrada.
	 */
	function init() {
		var images = document.querySelectorAll( '[data-codice-seo-image]' );
		var i;
		for ( i = 0; i < images.length; i++ ) {
			initImageField( images[ i ] );
		}

		var counters = document.querySelectorAll( '[data-codice-counter]' );
		for ( i = 0; i < counters.length; i++ ) {
			initCounter( counters[ i ] );
		}
	}

	if ( document.readyState === 'loading' ) {
		document.addEventListener( 'DOMContentLoaded', init );
	} else {
		init();
	}
}() );
