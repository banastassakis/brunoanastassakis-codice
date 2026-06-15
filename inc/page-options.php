<?php
/**
 * inc/page-options.php — Page-specific editorial options.
 *
 * @package codice
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Registers the page options metabox.
 */
function codice_register_page_options_metabox() {
	add_meta_box(
		'codice-page-options',
		esc_html__( 'Opções da página', 'codice' ),
		'codice_render_page_options_metabox',
		'page',
		'side',
		'default'
	);
}
add_action( 'add_meta_boxes_page', 'codice_register_page_options_metabox' );

/**
 * Renders the page options metabox.
 *
 * @param WP_Post $post Current post object.
 */
function codice_render_page_options_metabox( $post ) {
	$hide_title = get_post_meta( $post->ID, '_codice_hide_page_title', true );

	wp_nonce_field( 'codice_save_page_options', 'codice_page_options_nonce' );
	?>
	<p>
		<label for="codice-hide-page-title">
			<input
				type="checkbox"
				id="codice-hide-page-title"
				name="codice_hide_page_title"
				value="1"
				<?php checked( '1', $hide_title ); ?>
			>
			<?php esc_html_e( 'Ocultar título no layout público', 'codice' ); ?>
		</label>
	</p>
	<p class="description">
		<?php esc_html_e( 'Mantém o título do documento e preserva um H1 acessível para leitores de tela.', 'codice' ); ?>
	</p>
	<?php
}

/**
 * Saves the page options metadata.
 *
 * @param int     $post_id Current post ID.
 * @param WP_Post $post    Current post object.
 */
function codice_save_page_options( $post_id, $post ) {
	if ( ! isset( $_POST['codice_page_options_nonce'] ) ) {
		return;
	}

	$nonce = sanitize_text_field( wp_unslash( $_POST['codice_page_options_nonce'] ) );
	if ( ! wp_verify_nonce( $nonce, 'codice_save_page_options' ) ) {
		return;
	}

	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

	if ( wp_is_post_autosave( $post_id ) || wp_is_post_revision( $post_id ) ) {
		return;
	}

	if ( 'page' !== $post->post_type ) {
		return;
	}

	if ( ! current_user_can( 'edit_page', $post_id ) ) {
		return;
	}

	$hide_title = isset( $_POST['codice_hide_page_title'] ) ? '1' : '0';

	update_post_meta( $post_id, '_codice_hide_page_title', $hide_title );
}
add_action( 'save_post_page', 'codice_save_page_options', 10, 2 );

/**
 * Checks whether the visual page title should be hidden.
 *
 * This applies only to WordPress pages. It does not affect posts, archives,
 * search, home, front page, SEO title, schema, or the document title tag.
 *
 * @param int|null $post_id Optional page ID. Defaults to the queried object.
 * @return bool True when the page title should be visually hidden.
 */
function codice_should_hide_page_title( $post_id = null ) {
	if ( null === $post_id ) {
		$post_id = get_queried_object_id();
	}

	$post_id = absint( $post_id );
	if ( ! $post_id || 'page' !== get_post_type( $post_id ) ) {
		return false;
	}

	return '1' === get_post_meta( $post_id, '_codice_hide_page_title', true );
}
