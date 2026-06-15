<?php
/**
 * inc/seo-admin.php - Campos nativos de SEO no admin do tema Codice.
 *
 * Adiciona controles de SEO (basico, indexacao, Open Graph e Twitter/X) em:
 *  - posts e paginas (metabox com post meta);
 *  - categorias (campos de term meta);
 *  - anexos/midia (campos na tela de edicao do anexo).
 *
 * Nenhuma dependencia externa. Sem plugin. A saida publica fica em inc/seo.php
 * e inc/schema.php, que consomem os helpers de inc/seo-helpers.php.
 *
 * @package codice
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Define os grupos e campos de SEO exibidos no admin.
 *
 * Centraliza rotulos e textos de ajuda em pt-BR para reuso entre posts,
 * paginas e categorias.
 *
 * @return array<string, array{label:string, fields:array<string, array{type:string, label:string, help:string}>}>
 */
function codice_seo_field_groups() {
	$canonical_help = esc_html__( 'URL preferencial desta página para mecanismos de busca. Deixe em branco para usar a URL atual.', 'codice' );

	return array(
		'basico'    => array(
			'label'  => esc_html__( 'SEO básico', 'codice' ),
			'fields' => array(
				'_codice_seo_title'       => array(
					'type'  => 'text',
					'label' => esc_html__( 'Título SEO', 'codice' ),
					'help'  => esc_html__( 'Título completo para buscadores e aba do navegador. Em branco, usa o título padrão.', 'codice' ),
				),
				'_codice_seo_description' => array(
					'type'  => 'textarea',
					'label' => esc_html__( 'Meta description', 'codice' ),
					'help'  => esc_html__( 'Resumo de até cerca de 160 caracteres. Em branco, usa um resumo automático.', 'codice' ),
				),
				'_codice_canonical_url'   => array(
					'type'  => 'url',
					'label' => esc_html__( 'Canonical', 'codice' ),
					'help'  => $canonical_help,
				),
			),
		),
		'indexacao' => array(
			'label'  => esc_html__( 'Indexação', 'codice' ),
			'fields' => array(
				'_codice_robots_index'  => array(
					'type'  => 'select-index',
					'label' => esc_html__( 'Indexação (robots)', 'codice' ),
					'help'  => esc_html__( 'Padrão: indexar. Use noindex apenas quando esta página não deve aparecer em buscadores.', 'codice' ),
				),
				'_codice_robots_follow' => array(
					'type'  => 'select-follow',
					'label' => esc_html__( 'Links (robots)', 'codice' ),
					'help'  => esc_html__( 'Padrão: seguir links. Use nofollow apenas em casos específicos.', 'codice' ),
				),
			),
		),
		'social'    => array(
			'label'  => esc_html__( 'Social / Open Graph', 'codice' ),
			'fields' => array(
				'_codice_og_title'       => array(
					'type'  => 'text',
					'label' => esc_html__( 'Título Open Graph', 'codice' ),
					'help'  => esc_html__( 'Título ao compartilhar em redes. Em branco, usa o título SEO.', 'codice' ),
				),
				'_codice_og_description' => array(
					'type'  => 'textarea',
					'label' => esc_html__( 'Descrição Open Graph', 'codice' ),
					'help'  => esc_html__( 'Descrição ao compartilhar. Em branco, usa a meta description.', 'codice' ),
				),
				'_codice_og_image_id'    => array(
					'type'  => 'image',
					'label' => esc_html__( 'Imagem Open Graph', 'codice' ),
					'help'  => esc_html__( 'Override da imagem de compartilhamento. Em branco, usa a imagem destacada ou a padrão.', 'codice' ),
				),
			),
		),
		'twitter'   => array(
			'label'  => esc_html__( 'Twitter/X', 'codice' ),
			'fields' => array(
				'_codice_twitter_title'       => array(
					'type'  => 'text',
					'label' => esc_html__( 'Título Twitter/X', 'codice' ),
					'help'  => esc_html__( 'Em branco, usa o título Open Graph.', 'codice' ),
				),
				'_codice_twitter_description' => array(
					'type'  => 'textarea',
					'label' => esc_html__( 'Descrição Twitter/X', 'codice' ),
					'help'  => esc_html__( 'Em branco, usa a descrição Open Graph.', 'codice' ),
				),
				'_codice_twitter_image_id'    => array(
					'type'  => 'image',
					'label' => esc_html__( 'Imagem Twitter/X', 'codice' ),
					'help'  => esc_html__( 'Em branco, usa a imagem Open Graph.', 'codice' ),
				),
			),
		),
	);
}

/**
 * Renderiza o controle de um campo, sem wrapper (somente input + ajuda).
 *
 * @param string $key   Chave de meta.
 * @param array  $def   Definicao do campo.
 * @param string $value Valor atual.
 * @param string $id    ID base do input.
 * @return void
 */
function codice_seo_render_control( $key, $def, $value, $id ) {
	$name = esc_attr( $key );
	$id   = esc_attr( $id );

	switch ( $def['type'] ) {
		case 'textarea':
			printf(
				'<textarea id="%1$s" name="%2$s" rows="3" class="codice-seo-field__input" data-codice-counter="160">%3$s</textarea>',
				$id,
				$name,
				esc_textarea( $value )
			);
			echo '<span class="codice-seo-field__counter" aria-hidden="true"></span>';
			break;

		case 'url':
			printf(
				'<input type="url" id="%1$s" name="%2$s" value="%3$s" class="codice-seo-field__input" inputmode="url" placeholder="https://">',
				$id,
				$name,
				esc_attr( $value )
			);
			break;

		case 'select-index':
			$options = array(
				''        => esc_html__( 'Padrão (indexar)', 'codice' ),
				'index'   => esc_html__( 'Indexar', 'codice' ),
				'noindex' => esc_html__( 'Não indexar (noindex)', 'codice' ),
			);
			codice_seo_render_select( $id, $name, $options, $value );
			break;

		case 'select-follow':
			$options = array(
				''         => esc_html__( 'Padrão (seguir)', 'codice' ),
				'follow'   => esc_html__( 'Seguir links', 'codice' ),
				'nofollow' => esc_html__( 'Não seguir (nofollow)', 'codice' ),
			);
			codice_seo_render_select( $id, $name, $options, $value );
			break;

		case 'image':
			codice_seo_render_image_control( $id, $name, $value );
			break;

		case 'text':
		default:
			printf(
				'<input type="text" id="%1$s" name="%2$s" value="%3$s" class="codice-seo-field__input" data-codice-counter="60">',
				$id,
				$name,
				esc_attr( $value )
			);
			echo '<span class="codice-seo-field__counter" aria-hidden="true"></span>';
			break;
	}

	if ( ! empty( $def['help'] ) ) {
		printf( '<span class="codice-seo-field__help">%s</span>', esc_html( $def['help'] ) );
	}
}

/**
 * Renderiza um select simples a partir de um array de opcoes.
 *
 * @param string $id      ID do select.
 * @param string $name    Name do select.
 * @param array  $options Mapa value => label.
 * @param string $value   Valor atual.
 * @return void
 */
function codice_seo_render_select( $id, $name, $options, $value ) {
	printf( '<select id="%1$s" name="%2$s" class="codice-seo-field__input">', esc_attr( $id ), esc_attr( $name ) );
	foreach ( $options as $option_value => $option_label ) {
		printf(
			'<option value="%1$s" %2$s>%3$s</option>',
			esc_attr( $option_value ),
			selected( $value, $option_value, false ),
			esc_html( $option_label )
		);
	}
	echo '</select>';
}

/**
 * Renderiza o controle de selecao de imagem (media uploader nativo).
 *
 * @param string $id    ID base.
 * @param string $name  Name do input que guarda o ID.
 * @param string $value ID atual do anexo.
 * @return void
 */
function codice_seo_render_image_control( $id, $name, $value ) {
	$image_id  = absint( $value );
	$image_url = $image_id ? wp_get_attachment_image_url( $image_id, 'medium' ) : '';
	?>
	<span class="codice-seo-image" data-codice-seo-image>
		<input type="hidden" id="<?php echo esc_attr( $id ); ?>" name="<?php echo esc_attr( $name ); ?>" value="<?php echo esc_attr( $image_id ? $image_id : '' ); ?>" data-codice-seo-image-input>
		<span class="codice-seo-image__preview" data-codice-seo-image-preview>
			<?php if ( $image_url ) : ?>
				<img src="<?php echo esc_url( $image_url ); ?>" alt="">
			<?php endif; ?>
		</span>
		<span class="codice-seo-image__actions">
			<button type="button" class="button codice-seo-image__select" data-codice-seo-image-select>
				<?php esc_html_e( 'Selecionar imagem', 'codice' ); ?>
			</button>
			<button type="button" class="button-link codice-seo-image__remove" data-codice-seo-image-remove <?php echo $image_id ? '' : 'hidden'; ?>>
				<?php esc_html_e( 'Remover', 'codice' ); ?>
			</button>
		</span>
	</span>
	<?php
}

/* ============================================================================
 * Posts e paginas - metabox
 * ========================================================================== */

/**
 * Registra o metabox de SEO para posts e paginas.
 *
 * @return void
 */
function codice_seo_register_metabox() {
	foreach ( array( 'post', 'page' ) as $screen ) {
		add_meta_box(
			'codice-seo',
			esc_html__( 'SEO (Códice)', 'codice' ),
			'codice_seo_render_metabox',
			$screen,
			'normal',
			'default'
		);
	}
}
add_action( 'add_meta_boxes', 'codice_seo_register_metabox' );

/**
 * Renderiza o metabox de SEO para posts e paginas.
 *
 * @param WP_Post $post Post atual.
 * @return void
 */
function codice_seo_render_metabox( $post ) {
	wp_nonce_field( 'codice_seo_save_post', 'codice_seo_nonce' );

	echo '<div class="codice-seo">';
	foreach ( codice_seo_field_groups() as $group_key => $group ) {
		printf( '<fieldset class="codice-seo__group codice-seo__group--%s">', esc_attr( $group_key ) );
		printf( '<legend class="codice-seo__legend">%s</legend>', esc_html( $group['label'] ) );

		foreach ( $group['fields'] as $key => $def ) {
			$value = get_post_meta( $post->ID, $key, true );
			$id    = 'codice-seo-' . ltrim( $key, '_' );
			echo '<p class="codice-seo-field">';
			printf( '<label class="codice-seo-field__label" for="%1$s">%2$s</label>', esc_attr( $id ), esc_html( $def['label'] ) );
			codice_seo_render_control( $key, $def, is_string( $value ) ? $value : '', $id );
			echo '</p>';
		}

		echo '</fieldset>';
	}
	echo '</div>';
}

/**
 * Salva os campos de SEO de posts e paginas.
 *
 * @param int $post_id ID do post.
 * @return void
 */
function codice_seo_save_post( $post_id ) {
	if ( ! isset( $_POST['codice_seo_nonce'] ) ) {
		return;
	}

	$nonce = sanitize_text_field( wp_unslash( $_POST['codice_seo_nonce'] ) );
	if ( ! wp_verify_nonce( $nonce, 'codice_seo_save_post' ) ) {
		return;
	}

	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

	if ( wp_is_post_autosave( $post_id ) || wp_is_post_revision( $post_id ) ) {
		return;
	}

	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	$post_type = get_post_type( $post_id );
	if ( ! in_array( $post_type, array( 'post', 'page' ), true ) ) {
		return;
	}

	foreach ( codice_seo_meta_keys() as $key ) {
		$raw       = isset( $_POST[ $key ] ) ? $_POST[ $key ] : ''; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- sanitizado em codice_seo_sanitize_value().
		$sanitized = codice_seo_sanitize_value( $key, $raw );

		if ( '' === $sanitized || '0' === $sanitized ) {
			delete_post_meta( $post_id, $key );
			continue;
		}

		update_post_meta( $post_id, $key, $sanitized );
	}
}
add_action( 'save_post', 'codice_seo_save_post' );

/* ============================================================================
 * Categorias - term meta
 * ========================================================================== */

/**
 * Renderiza os campos de SEO no formulario de adicao de categoria.
 *
 * @return void
 */
function codice_seo_term_add_fields() {
	wp_nonce_field( 'codice_seo_save_term', 'codice_seo_term_nonce' );

	foreach ( codice_seo_field_groups() as $group ) {
		printf( '<h3 class="codice-seo-term__legend">%s</h3>', esc_html( $group['label'] ) );

		foreach ( $group['fields'] as $key => $def ) {
			$id = 'codice-seo-' . ltrim( $key, '_' );
			echo '<div class="form-field codice-seo-term__field">';
			printf( '<label for="%1$s">%2$s</label>', esc_attr( $id ), esc_html( $def['label'] ) );
			codice_seo_render_control( $key, $def, '', $id );
			echo '</div>';
		}
	}
}
add_action( 'category_add_form_fields', 'codice_seo_term_add_fields' );

/**
 * Renderiza os campos de SEO no formulario de edicao de categoria.
 *
 * @param WP_Term $term Termo atual.
 * @return void
 */
function codice_seo_term_edit_fields( $term ) {
	wp_nonce_field( 'codice_seo_save_term', 'codice_seo_term_nonce' );

	foreach ( codice_seo_field_groups() as $group ) {
		printf(
			'<tr class="form-field codice-seo-term__row"><th scope="row"><strong>%s</strong></th><td></td></tr>',
			esc_html( $group['label'] )
		);

		foreach ( $group['fields'] as $key => $def ) {
			$value = get_term_meta( $term->term_id, $key, true );
			$id    = 'codice-seo-' . ltrim( $key, '_' );
			echo '<tr class="form-field codice-seo-term__field">';
			printf( '<th scope="row"><label for="%1$s">%2$s</label></th>', esc_attr( $id ), esc_html( $def['label'] ) );
			echo '<td>';
			codice_seo_render_control( $key, $def, is_string( $value ) ? $value : '', $id );
			echo '</td></tr>';
		}
	}
}
add_action( 'category_edit_form_fields', 'codice_seo_term_edit_fields' );

/**
 * Salva os campos de SEO das categorias.
 *
 * @param int $term_id ID do termo.
 * @return void
 */
function codice_seo_save_term( $term_id ) {
	if ( ! isset( $_POST['codice_seo_term_nonce'] ) ) {
		return;
	}

	$nonce = sanitize_text_field( wp_unslash( $_POST['codice_seo_term_nonce'] ) );
	if ( ! wp_verify_nonce( $nonce, 'codice_seo_save_term' ) ) {
		return;
	}

	if ( ! current_user_can( 'manage_categories' ) ) {
		return;
	}

	foreach ( codice_seo_meta_keys() as $key ) {
		$raw       = isset( $_POST[ $key ] ) ? $_POST[ $key ] : ''; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- sanitizado em codice_seo_sanitize_value().
		$sanitized = codice_seo_sanitize_value( $key, $raw );

		if ( '' === $sanitized || '0' === $sanitized ) {
			delete_term_meta( $term_id, $key );
			continue;
		}

		update_term_meta( $term_id, $key, $sanitized );
	}
}
add_action( 'created_category', 'codice_seo_save_term' );
add_action( 'edited_category', 'codice_seo_save_term' );

/* ============================================================================
 * Anexos / midia
 * ========================================================================== */

/**
 * Define os campos editoriais de SEO para anexos.
 *
 * Nao substitui o campo nativo de texto alternativo (_wp_attachment_image_alt),
 * que continua sendo a fonte priorizada para o atributo alt.
 *
 * @return array<string, array{label:string, type:string, help:string}>
 */
function codice_seo_attachment_field_defs() {
	return array(
		'_codice_seo_title'       => array(
			'label' => esc_html__( 'Título SEO da mídia', 'codice' ),
			'type'  => 'text',
			'help'  => esc_html__( 'Override de título para uso editorial/social. Opcional.', 'codice' ),
		),
		'_codice_seo_description' => array(
			'label' => esc_html__( 'Meta description da mídia', 'codice' ),
			'type'  => 'textarea',
			'help'  => esc_html__( 'Descrição editorial curta da imagem. Opcional.', 'codice' ),
		),
		'_codice_alt_editorial'   => array(
			'label' => esc_html__( 'Texto alternativo recomendado (editorial)', 'codice' ),
			'type'  => 'text',
			'help'  => esc_html__( 'Sugestão editorial de alt. Não substitui o campo nativo de texto alternativo, que segue prioritário.', 'codice' ),
		),
		'_codice_og_title'        => array(
			'label' => esc_html__( 'Título Open Graph da imagem', 'codice' ),
			'type'  => 'text',
			'help'  => esc_html__( 'Título ao usar a imagem em compartilhamento. Opcional.', 'codice' ),
		),
		'_codice_og_description'  => array(
			'label' => esc_html__( 'Descrição Open Graph da imagem', 'codice' ),
			'type'  => 'textarea',
			'help'  => esc_html__( 'Descrição ao usar a imagem em compartilhamento. Opcional.', 'codice' ),
		),
	);
}

/**
 * Adiciona os campos de SEO na tela de edicao do anexo.
 *
 * @param array   $form_fields Campos atuais.
 * @param WP_Post $post        Anexo atual.
 * @return array Campos filtrados.
 */
function codice_seo_attachment_fields( $form_fields, $post ) {
	$form_fields['codice_seo_attachment_nonce'] = array(
		'label' => '',
		'input' => 'hidden',
		'value' => wp_create_nonce( 'codice_seo_save_attachment' ),
	);

	foreach ( codice_seo_attachment_field_defs() as $key => $def ) {
		$value = get_post_meta( $post->ID, $key, true );

		$field = array(
			'label' => $def['label'],
			'value' => is_string( $value ) ? $value : '',
			'helps' => $def['help'],
		);

		if ( 'textarea' === $def['type'] ) {
			$field['input'] = 'textarea';
		}

		$form_fields[ $key ] = $field;
	}

	return $form_fields;
}
add_filter( 'attachment_fields_to_edit', 'codice_seo_attachment_fields', 10, 2 );

/**
 * Salva os campos de SEO do anexo.
 *
 * @param array $post       Dados do anexo (array).
 * @param array $attachment Valores enviados.
 * @return array Dados do anexo inalterados.
 */
function codice_seo_attachment_save( $post, $attachment ) {
	$post_id = isset( $post['ID'] ) ? absint( $post['ID'] ) : 0;

	if ( ! $post_id || ! current_user_can( 'edit_post', $post_id ) ) {
		return $post;
	}

	if (
		! isset( $attachment['codice_seo_attachment_nonce'] ) ||
		! wp_verify_nonce(
			sanitize_text_field( wp_unslash( $attachment['codice_seo_attachment_nonce'] ) ),
			'codice_seo_save_attachment'
		)
	) {
		return $post;
	}

	foreach ( codice_seo_attachment_field_defs() as $key => $def ) {
		if ( ! isset( $attachment[ $key ] ) ) {
			continue;
		}

		if ( 'textarea' === $def['type'] ) {
			$sanitized = sanitize_textarea_field( wp_unslash( $attachment[ $key ] ) );
		} else {
			$sanitized = sanitize_text_field( wp_unslash( $attachment[ $key ] ) );
		}

		if ( '' === $sanitized ) {
			delete_post_meta( $post_id, $key );
			continue;
		}

		update_post_meta( $post_id, $key, $sanitized );
	}

	return $post;
}
add_filter( 'attachment_fields_to_save', 'codice_seo_attachment_save', 10, 2 );

/* ============================================================================
 * Assets do admin
 * ========================================================================== */

/**
 * Enfileira CSS/JS minimos do SEO admin apenas nas telas relevantes.
 *
 * @param string $hook Hook da tela atual.
 * @return void
 */
function codice_seo_admin_assets( $hook ) {
	$is_post_screen = in_array( $hook, array( 'post.php', 'post-new.php' ), true );
	$is_term_screen = in_array( $hook, array( 'edit-tags.php', 'term.php' ), true );

	if ( ! $is_post_screen && ! $is_term_screen ) {
		return;
	}

	$template_dir = get_template_directory();
	$template_uri = get_template_directory_uri();

	$tokens_path = $template_dir . '/assets/css/tokens.css';
	wp_enqueue_style(
		'codice-admin-tokens',
		$template_uri . '/assets/css/tokens.css',
		array(),
		file_exists( $tokens_path ) ? filemtime( $tokens_path ) : '1.0.0'
	);

	$css_path = $template_dir . '/assets/css/seo-admin.css';
	wp_enqueue_style(
		'codice-seo-admin',
		$template_uri . '/assets/css/seo-admin.css',
		array( 'codice-admin-tokens' ),
		file_exists( $css_path ) ? filemtime( $css_path ) : '1.0.0'
	);

	wp_enqueue_media();

	$js_path = $template_dir . '/assets/js/seo-admin.js';
	wp_enqueue_script(
		'codice-seo-admin',
		$template_uri . '/assets/js/seo-admin.js',
		array(),
		file_exists( $js_path ) ? filemtime( $js_path ) : '1.0.0',
		true
	);

	wp_localize_script(
		'codice-seo-admin',
		'codiceSeoAdmin',
		array(
			'frameTitle'  => esc_html__( 'Selecionar imagem', 'codice' ),
			'frameButton' => esc_html__( 'Usar esta imagem', 'codice' ),
		)
	);
}
add_action( 'admin_enqueue_scripts', 'codice_seo_admin_assets' );
