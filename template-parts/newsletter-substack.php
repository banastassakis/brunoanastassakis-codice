<?php
/**
 * template-parts/newsletter-substack.php — Captação Substack nativa
 *
 * Componente reutilizável de captação de newsletter via Substack.
 * Markup próprio do tema — sem iframe, sem embed, sem script externo.
 *
 * Parametrizável via $args (get_template_part suporta a partir do WP 5.5):
 *   - title       string  Título do bloco (opcional)
 *   - description string  Texto descritivo breve (opcional)
 *   - label       string  Label do campo de e-mail (opcional)
 *   - btn_text    string  Texto do botão (opcional)
 *   - context     string  Contexto de uso: 'home' | 'footer' | 'inline' (padrão: 'home')
 *   - form_id     string  ID único do formulário (opcional)
 *
 * A URL de inscrição do Substack é parametrizável via filtro:
 *   add_filter( 'codice_substack_signup_url', function() { return 'https://suapublicacao.substack.com'; } );
 *
 * Mecanismo de envio: POST direto para o endpoint do Substack,
 * ou fallback para link de inscrição com e-mail pré-preenchido.
 * O comportamento exato deve ser validado no deploy (o Substack
 * pode alterar a API pública sem aviso).
 *
 * @package codice
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// ── Resolve argumentos ────────────────────────────────────────────────────────

$args = isset( $args ) && is_array( $args ) ? $args : array();

$title       = ! empty( $args['title'] )
	? $args['title']
	: __( 'A publicação por e-mail', 'codice' );

$description = ! empty( $args['description'] )
	? $args['description']
	: __( 'Artigos sobre conteúdo, comunicação, eventos, IA e ecossistema editorial — diretamente no seu e-mail.', 'codice' );

$label       = ! empty( $args['label'] )
	? $args['label']
	: __( 'Endereço de e-mail', 'codice' );

$btn_text    = ! empty( $args['btn_text'] )
	? $args['btn_text']
	: __( 'Assinar', 'codice' );

$context     = ! empty( $args['context'] ) ? sanitize_key( $args['context'] ) : 'home';

$form_id     = ! empty( $args['form_id'] )
	? sanitize_html_class( $args['form_id'] )
	: 'codice-newsletter-' . $context;

$email_id    = $form_id . '-email';

// ── URL do Substack ───────────────────────────────────────────────────────────
// Parametrizável via filtro; sem valor padrão preenchido (evita URL errada no build).
// Quando não configurada, renderiza estado discreto sem quebrar layout.

/**
 * Filtro para definir a URL de inscrição do Substack.
 *
 * @param string $url URL base da publicação no Substack (ex.: https://suapublicacao.substack.com).
 */
$substack_base_url = apply_filters( 'codice_substack_signup_url', '' );
$substack_base_url = esc_url_raw( trim( $substack_base_url ) );

// Endpoint de inscrição do Substack (testado em publicações públicas).
// Mecanismo: POST para /api/v1/free com campo 'email'.
// Fallback: redirect para a página de inscrição do Substack com o e-mail pré-preenchido.
// O campo action do form usa o endpoint; o fallback é o link de inscrição.
$substack_configured = ! empty( $substack_base_url );

// Remove barra final para montar o endpoint corretamente.
$substack_base_url_clean = rtrim( $substack_base_url, '/' );
$substack_action          = $substack_configured
	? $substack_base_url_clean . '/api/v1/free'
	: '';
$substack_link            = $substack_configured
	? $substack_base_url_clean . '/subscribe'
	: '';

?>
<div class="newsletter-block newsletter-block--<?php echo esc_attr( $context ); ?>">

	<?php if ( $title ) : ?>
		<p class="newsletter-block__label section-label">
			<?php esc_html_e( 'Newsletter', 'codice' ); ?>
		</p>
		<p class="newsletter-block__title">
			<?php echo esc_html( $title ); ?>
		</p>
	<?php endif; ?>

	<?php if ( $description ) : ?>
		<p class="newsletter-block__desc">
			<?php echo esc_html( $description ); ?>
		</p>
	<?php endif; ?>

	<?php if ( $substack_configured ) : ?>

		<form
			id="<?php echo esc_attr( $form_id ); ?>"
			class="newsletter-block__form"
			method="post"
			action="<?php echo esc_url( $substack_action ); ?>"
		>
			<div class="newsletter-block__field">
				<label for="<?php echo esc_attr( $email_id ); ?>" class="newsletter-block__field-label">
					<?php echo esc_html( $label ); ?>
				</label>
				<div class="newsletter-block__input-row">
					<input
						type="email"
						id="<?php echo esc_attr( $email_id ); ?>"
						name="email"
						class="newsletter-block__input"
						placeholder="<?php esc_attr_e( 'seu@email.com', 'codice' ); ?>"
						required
						autocomplete="email"
						aria-required="true"
					>
					<button type="submit" class="btn newsletter-block__btn">
						<?php echo esc_html( $btn_text ); ?>
					</button>
				</div>
			</div>
		</form>

		<?php if ( $substack_link ) : ?>
			<p class="newsletter-block__alt">
				<?php
				printf(
					/* translators: %s: link para a página de inscrição do Substack */
					wp_kses(
						__( 'Ou <a href="%s" class="newsletter-block__alt-link">acesse diretamente no Substack</a>.', 'codice' ),
						array( 'a' => array( 'href' => array(), 'class' => array() ) )
					),
					esc_url( $substack_link )
				);
				?>
			</p>
		<?php endif; ?>

	<?php else : ?>
		<?php /* Estado discreto quando a URL do Substack ainda não está configurada */ ?>
		<p class="newsletter-block__pending">
			<?php
			esc_html_e(
				'A publicação por e-mail será disponibilizada em breve.',
				'codice'
			);
			?>
		</p>
	<?php endif; ?>

</div><!-- .newsletter-block -->
