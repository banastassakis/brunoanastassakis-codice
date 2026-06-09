<?php
/**
 * inc/contact-form.php — Handler do formulário de contato
 *
 * Processa o envio do formulário de contato do tema Códice.
 * Requisitos:
 * - nonce wp_nonce_field + verificação
 * - honeypot anti-spam
 * - sanitização de todos os campos
 * - validação de campos obrigatórios e formato de e-mail
 * - envio via wp_mail()
 * - destinatário parametrizável via filtro codice_contact_form_recipient
 * - redirecionamento de volta para a página de contato com query args de status
 * - sem salvar no banco, sem plugin, sem AJAX nesta etapa
 *
 * @package codice
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Renderiza o formulário de contato.
 * Chamado no template page-contato.php.
 *
 * @param array $args Argumentos opcionais (form_id, page_url).
 */
function codice_contact_form_render( $args = array() ) {
	$defaults = array(
		'form_id'  => 'codice-contact-form',
		'page_url' => get_permalink(),
	);
	$args = wp_parse_args( $args, $defaults );

	// Mensagens de retorno via query string.
	$status = isset( $_GET['contact'] ) ? sanitize_key( $_GET['contact'] ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
	$code   = isset( $_GET['code'] )    ? sanitize_key( $_GET['code'] )    : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended

	?>
	<?php if ( 'ok' === $status ) : ?>
		<div class="form-feedback form-feedback--success" role="status" tabindex="-1">
			<p><?php esc_html_e( 'Mensagem enviada. Obrigado pelo contato.', 'codice' ); ?></p>
		</div>
	<?php elseif ( 'erro' === $status ) : ?>
		<div class="form-feedback form-feedback--error" role="alert" tabindex="-1">
			<?php
			$messages = array(
				'campos'  => esc_html__( 'Todos os campos são obrigatórios.', 'codice' ),
				'email'   => esc_html__( 'O endereço de e-mail não é válido.', 'codice' ),
				'spam'    => esc_html__( 'Envio inválido.', 'codice' ),
				'nonce'   => esc_html__( 'Sessão expirada. Por favor, tente novamente.', 'codice' ),
				'falha'   => esc_html__( 'Não foi possível enviar a mensagem. Tente mais tarde.', 'codice' ),
			);
			$msg = isset( $messages[ $code ] ) ? $messages[ $code ] : esc_html__( 'Ocorreu um erro. Por favor, tente novamente.', 'codice' );
			echo '<p>' . esc_html( $msg ) . '</p>';
			?>
		</div>
	<?php endif; ?>

	<form
		id="<?php echo esc_attr( $args['form_id'] ); ?>"
		class="contact-form"
		method="post"
		action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>"
		novalidate
	>
		<?php wp_nonce_field( 'codice_contact_send', 'codice_contact_nonce' ); ?>
		<input type="hidden" name="action" value="codice_contact_send">
		<input type="hidden" name="codice_redirect_url" value="<?php echo esc_url( $args['page_url'] ); ?>">

		<?php /* Honeypot anti-spam — visualmente oculto via CSS */ ?>
		<div class="contact-form__honeypot" aria-hidden="true">
			<label for="codice_hp_field">
				<?php esc_html_e( 'Não preencha este campo', 'codice' ); ?>
			</label>
			<input
				type="text"
				id="codice_hp_field"
				name="codice_hp"
				value=""
				tabindex="-1"
				autocomplete="off"
			>
		</div>

		<div class="contact-form__group">
			<label for="contact-name">
				<?php esc_html_e( 'Nome', 'codice' ); ?>
			</label>
			<input
				type="text"
				id="contact-name"
				name="codice_name"
				value=""
				required
				autocomplete="name"
				aria-required="true"
			>
		</div>

		<div class="contact-form__group">
			<label for="contact-email">
				<?php esc_html_e( 'E-mail', 'codice' ); ?>
			</label>
			<input
				type="email"
				id="contact-email"
				name="codice_email"
				value=""
				required
				autocomplete="email"
				aria-required="true"
			>
		</div>

		<div class="contact-form__group">
			<label for="contact-subject">
				<?php esc_html_e( 'Contexto / assunto', 'codice' ); ?>
			</label>
			<input
				type="text"
				id="contact-subject"
				name="codice_subject"
				value=""
				required
				aria-required="true"
			>
		</div>

		<div class="contact-form__group">
			<label for="contact-message">
				<?php esc_html_e( 'Mensagem', 'codice' ); ?>
			</label>
			<textarea
				id="contact-message"
				name="codice_message"
				rows="7"
				required
				aria-required="true"
			></textarea>
		</div>

		<div class="contact-form__submit">
			<button type="submit" class="btn">
				<?php esc_html_e( 'Enviar mensagem', 'codice' ); ?>
			</button>
		</div>

	</form>
	<?php
}

/**
 * Registra o handler do formulário para usuários não autenticados e autenticados.
 */
add_action( 'admin_post_nopriv_codice_contact_send', 'codice_contact_handle_submission' );
add_action( 'admin_post_codice_contact_send',        'codice_contact_handle_submission' );

/**
 * Processa o envio do formulário de contato.
 * Valida nonce, honeypot, sanitiza campos, valida obrigatoriedade e envia e-mail.
 * Redireciona de volta com query args de status.
 */
function codice_contact_handle_submission() {

	// URL de redirecionamento — validada como URL relativa ao site.
	$posted_redirect_url = ! empty( $_POST['codice_redirect_url'] ) // phpcs:ignore WordPress.Security.NonceVerification.Missing
		? esc_url_raw( wp_unslash( $_POST['codice_redirect_url'] ) ) // phpcs:ignore WordPress.Security.NonceVerification.Missing
		: '';
	$redirect_url = wp_validate_redirect( $posted_redirect_url, home_url( '/contato/' ) );

	// Fallback absoluto de segurança.
	if ( ! $redirect_url ) {
		$redirect_url = home_url( '/contato/' );
	}

	/**
	 * Verifica nonce.
	 * Redireciona com erro se inválido.
	 */
	if (
		! isset( $_POST['codice_contact_nonce'] ) ||
		! wp_verify_nonce( sanitize_key( wp_unslash( $_POST['codice_contact_nonce'] ) ), 'codice_contact_send' )
	) {
		wp_safe_redirect( add_query_arg( array( 'contact' => 'erro', 'code' => 'nonce' ), $redirect_url ) );
		exit;
	}

	/**
	 * Honeypot anti-spam: campo deve estar vazio.
	 */
	$honeypot = isset( $_POST['codice_hp'] ) ? sanitize_text_field( wp_unslash( $_POST['codice_hp'] ) ) : '';
	if ( ! empty( $honeypot ) ) {
		wp_safe_redirect( add_query_arg( array( 'contact' => 'erro', 'code' => 'spam' ), $redirect_url ) );
		exit;
	}

	/**
	 * Sanitização de todos os campos.
	 */
	$name    = isset( $_POST['codice_name'] )    ? sanitize_text_field( wp_unslash( $_POST['codice_name'] ) )    : '';
	$email   = isset( $_POST['codice_email'] )   ? sanitize_email( wp_unslash( $_POST['codice_email'] ) )        : '';
	$subject = isset( $_POST['codice_subject'] ) ? sanitize_text_field( wp_unslash( $_POST['codice_subject'] ) ) : '';
	$message = isset( $_POST['codice_message'] ) ? sanitize_textarea_field( wp_unslash( $_POST['codice_message'] ) ) : '';

	/**
	 * Validação de campos obrigatórios.
	 */
	if ( empty( $name ) || empty( $email ) || empty( $subject ) || empty( $message ) ) {
		wp_safe_redirect( add_query_arg( array( 'contact' => 'erro', 'code' => 'campos' ), $redirect_url ) );
		exit;
	}

	/**
	 * Validação de formato de e-mail.
	 */
	if ( ! is_email( $email ) ) {
		wp_safe_redirect( add_query_arg( array( 'contact' => 'erro', 'code' => 'email' ), $redirect_url ) );
		exit;
	}

	/**
	 * Destinatário parametrizável via filtro.
	 * Fallback: admin_email da instalação.
	 *
	 * Uso: add_filter( 'codice_contact_form_recipient', function() { return 'contato@brunoanastassakis.com'; } );
	 */
	$recipient = apply_filters(
		'codice_contact_form_recipient',
		function_exists( 'codice_get_contact_email' ) ? codice_get_contact_email() : get_option( 'admin_email' )
	);
	$recipient = sanitize_email( $recipient );

	// Garante que o destinatário é um e-mail válido.
	if ( ! is_email( $recipient ) ) {
		wp_safe_redirect( add_query_arg( array( 'contact' => 'erro', 'code' => 'falha' ), $redirect_url ) );
		exit;
	}

	/**
	 * Monta o e-mail.
	 * Assunto sóbrio e traduzível.
	 */
	/* translators: 1: nome do remetente, 2: contexto/assunto */
	$mail_subject = sprintf(
		/* translators: 1: nome do remetente, 2: assunto */
		esc_html__( 'Contato — %1$s: %2$s', 'codice' ),
		str_replace( array( "\r", "\n" ), ' ', $name ),
		$subject
	);

	$mail_body  = esc_html__( 'Nova mensagem recebida pelo formulário de contato.', 'codice' ) . "\n\n";
	$mail_body .= esc_html__( 'Nome:', 'codice' ) . ' ' . $name . "\n";
	$mail_body .= esc_html__( 'E-mail:', 'codice' ) . ' ' . $email . "\n";
	$mail_body .= esc_html__( 'Contexto / assunto:', 'codice' ) . ' ' . $subject . "\n\n";
	$mail_body .= esc_html__( 'Mensagem:', 'codice' ) . "\n" . $message . "\n";

	$mail_headers = array(
		'Content-Type: text/plain; charset=UTF-8',
		'Reply-To: ' . str_replace( array( "\r", "\n" ), ' ', $name ) . ' <' . $email . '>',
	);

	/**
	 * Envio via wp_mail().
	 * Nota: a entrega real depende da configuração de e-mail do servidor
	 * (Hostgator pode exigir SMTP). Pendência técnica conhecida — validar no deploy.
	 */
	$sent = wp_mail( $recipient, $mail_subject, $mail_body, $mail_headers );

	if ( $sent ) {
		wp_safe_redirect( add_query_arg( 'contact', 'ok', $redirect_url ) );
	} else {
		wp_safe_redirect( add_query_arg( array( 'contact' => 'erro', 'code' => 'falha' ), $redirect_url ) );
	}
	exit;
}
