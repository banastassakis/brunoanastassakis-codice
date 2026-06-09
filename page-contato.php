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
?>

<main id="conteudo" class="site-main" role="main" tabindex="-1">
	<div class="container-reading">
		<article class="page-contato">
			<header class="entry-header">
				<span class="meta entry-header__label"><?php esc_html_e( 'Conversa', 'codice' ); ?></span>
				<h1 class="<?php echo esc_attr( $hide_title ? 'entry-header__title sr-only' : 'entry-header__title' ); ?>"><?php the_title(); ?></h1>
				<p class="lede">
					<?php
					esc_html_e(
						'Utilize este espaço para propor discussões, questionamentos ou parcerias institucionais. O diálogo é conduzido de forma direta e consultiva.',
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
						<li class="mb-xs font-mono meta">
							<?php esc_html_e( 'E-mail:', 'codice' ); ?>
							<a href="mailto:contato@brunoanastassakis.com" class="link-plain">
								contato@brunoanastassakis.com
							</a>
						</li>
						<li class="font-mono meta">
							<?php esc_html_e( 'LinkedIn:', 'codice' ); ?>
							<a href="https://linkedin.com/in/brunoanastassakis" target="_blank" rel="noopener noreferrer" class="link-plain">
								linkedin.com/in/brunoanastassakis
							</a>
						</li>
					</ul>
				</section>
			</div>
		</article>
	</div>
</main>

<?php
get_footer();
