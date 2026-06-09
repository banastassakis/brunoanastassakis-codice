<?php
/**
 * page-manutencao.php — Public maintenance screen.
 *
 * @package codice
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( defined( 'CODICE_MAINTENANCE_MODE' ) && CODICE_MAINTENANCE_MODE && ! current_user_can( 'manage_options' ) ) {
	status_header( 503 );
	nocache_headers();
} else {
	status_header( 200 );
}
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>
<body <?php body_class( 'maintenance-template' ); ?>>
<?php wp_body_open(); ?>

<a class="skip-link" href="#conteudo">
	<?php esc_html_e( 'Pular para o conteúdo', 'codice' ); ?>
</a>

<main id="conteudo" class="site-main maintenance-main" role="main" tabindex="-1">
	<div class="container maintenance-layout">
		<section class="maintenance-screen" aria-labelledby="maintenance-title">
			<p class="maintenance-screen__label"><?php esc_html_e( 'Manutenção', 'codice' ); ?></p>
			<h1 id="maintenance-title" class="maintenance-screen__title">
				<?php esc_html_e( 'Este espaço está sendo preparado.', 'codice' ); ?>
			</h1>
			<h2 id="maintenance-contact-title" class="maintenance-screen__text">
				<?php esc_html_e( 'Contatos:', 'codice' ); ?>
			</h2>

			<div class="maintenance-contact" aria-labelledby="maintenance-contact-title">
				<ul class="maintenance-contact__list list-bare">
					<li class="maintenance-contact__item">
						<a class="maintenance-contact__link" href="<?php echo esc_url( 'mailto:contato@brunoanastassakis.com' ); ?>">
							<svg class="maintenance-contact__icon" viewBox="0 0 24 24" width="20" height="20" aria-hidden="true" focusable="false">
								<path d="M4 6h16v12H4z" fill="none" stroke="currentColor" stroke-width="1.5" />
								<path d="m4 7 8 6 8-6" fill="none" stroke="currentColor" stroke-width="1.5" />
							</svg>
							<span><?php esc_html_e( 'E-mail:', 'codice' ); ?></span>
							<span>contato@brunoanastassakis.com</span>
						</a>
					</li>
					<li class="maintenance-contact__item">
						<a class="maintenance-contact__link" href="<?php echo esc_url( 'https://www.linkedin.com/in/brunoanastassakis/' ); ?>" target="_blank" rel="noopener noreferrer">
							<svg class="maintenance-contact__icon" viewBox="0 0 24 24" width="20" height="20" aria-hidden="true" focusable="false">
								<path d="M5 9h3v10H5zM5 5h3v3H5zM11 9h3v2a3 3 0 0 1 5 2v6h-3v-5a2 2 0 0 0-2-2v7h-3z" fill="currentColor" />
							</svg>
							<span><?php esc_html_e( 'LinkedIn:', 'codice' ); ?></span>
							<span>linkedin.com/in/brunoanastassakis</span>
						</a>
					</li>
					<li class="maintenance-contact__item">
						<a class="maintenance-contact__link" href="<?php echo esc_url( 'https://wa.me/5521986957214' ); ?>" target="_blank" rel="noopener noreferrer">
							<svg class="maintenance-contact__icon" viewBox="0 0 24 24" width="20" height="20" aria-hidden="true" focusable="false">
								<path d="M6 5h12v14H6z" fill="none" stroke="currentColor" stroke-width="1.5" />
								<path d="M9 8c1 4 3 6 7 7" fill="none" stroke="currentColor" stroke-width="1.5" />
								<path d="M10 8h2l1 2-1 1" fill="none" stroke="currentColor" stroke-width="1.5" />
							</svg>
							<span><?php esc_html_e( 'WhatsApp:', 'codice' ); ?></span>
							<span>+55 21 986957214</span>
						</a>
					</li>
				</ul>
			</div>
		</section>
		<figure class="maintenance-visual">
			<img
				src="<?php echo esc_url( get_template_directory_uri() . '/assets/img/maintenance-retroprint.svg' ); ?>"
				width="640"
				height="760"
				alt="<?php esc_attr_e( 'Composição editorial retroprint com camadas de papel e formas de organização visual, sugerindo um espaço em preparação.', 'codice' ); ?>"
				loading="eager"
				decoding="async"
			>
		</figure>
	</div>
</main>

<?php wp_footer(); ?>
</body>
</html>
