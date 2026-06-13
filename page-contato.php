<?php
/**
 * Template Name: Contato
 * page-contato.php — Página Contato
 *
 * Apresenta o formulário de contato customizado (Nome, E-mail, Contexto, Mensagem),
 * processado via admin-post por inc/contact-form.php, e fornece links diretos
 * de contato de forma discreta e sem promessas comerciais.
 *
 * @package codice
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

$hide_title = function_exists( 'codice_should_hide_page_title' ) && codice_should_hide_page_title( get_the_ID() );
$contact_email = function_exists( 'codice_get_contact_email' ) ? codice_get_contact_email() : get_option( 'admin_email' );
$linkedin_url  = function_exists( 'codice_get_linkedin_url' ) ? codice_get_linkedin_url() : '';
?>

<main id="conteudo" class="site-main" role="main" tabindex="-1">
	<div class="container-reading">
		<?php
		if ( function_exists( 'codice_render_breadcrumbs' ) ) {
			codice_render_breadcrumbs();
		}
		?>
		<article class="page-contato">
			<header class="entry-header">
				<span class="meta entry-header__label"><?php esc_html_e( 'Conversa', 'codice' ); ?></span>
				<h1 class="<?php echo esc_attr( $hide_title ? 'entry-header__title sr-only' : 'entry-header__title' ); ?>"><?php echo esc_html( get_the_title() ); ?></h1>
				<p class="lede">
					<?php
						esc_html_e(
							'Utilize este espaço para propor discussões, questionamentos ou conversas relacionadas aos temas da publicação.',
							'codice'
						);
					?>
				</p>
			</header>

			<div class="entry-content">
				<?php
				// Renderiza o formulário de contato customizado.
				if ( function_exists( 'codice_contact_form_render' ) ) {
					codice_contact_form_render();
				} else {
					?>
					<div class="form-feedback form-feedback--error" role="alert">
						<p><?php esc_html_e( 'Erro: O sistema de formulários não está disponível no momento.', 'codice' ); ?></p>
					</div>
					<?php
				}
				?>

				<hr class="section-divider mb-lg">

				<?php
				// Canais alternativos discretos
				?>
				<section class="contato-alternativo">
					<h2 class="h4 mb-sm"><?php esc_html_e( 'Canais diretos', 'codice' ); ?></h2>
					<p class="text-slate font-sans mb-sm">
						<?php
						esc_html_e(
							'Se preferir contornar o formulário ou anexar referências, pode enviar um e-mail direto ou utilizar redes profissionais:',
							'codice'
						);
						?>
					</p>

					<ul class="contato-alternativo__list list-bare">
						<?php if ( is_email( $contact_email ) ) : ?>
							<li class="mb-xs font-mono meta">
								<?php esc_html_e( 'E-mail:', 'codice' ); ?>
								<a href="<?php echo esc_url( 'mailto:' . $contact_email ); ?>" class="link-plain">
									<?php echo esc_html( $contact_email ); ?>
								</a>
							</li>
						<?php endif; ?>
						<?php if ( $linkedin_url ) : ?>
							<li class="font-mono meta">
								<?php esc_html_e( 'LinkedIn:', 'codice' ); ?>
								<a href="<?php echo esc_url( $linkedin_url ); ?>" target="_blank" rel="noopener noreferrer" class="link-plain">
									<?php echo esc_html( preg_replace( '#^https?://(www\.)?#', '', untrailingslashit( $linkedin_url ) ) ); ?>
								</a>
							</li>
						<?php endif; ?>
					</ul>
				</section>
			</div>
		</article>
	</div>
</main>

<?php
get_footer();
