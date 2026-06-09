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
	<div class="maintenance-layout">
		<section class="maintenance-screen" aria-labelledby="maintenance-title">
			<p class="maintenance-screen__label"><?php esc_html_e( 'MANUTENÇÃO', 'codice' ); ?></p>
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
							<span><?php esc_html_e( 'E-mail:', 'codice' ); ?></span>
							<span>contato@brunoanastassakis.com</span>
						</a>
					</li>
					<li class="maintenance-contact__item">
						<a class="maintenance-contact__link" href="<?php echo esc_url( 'https://www.linkedin.com/in/brunoanastassakis/' ); ?>" target="_blank" rel="noopener noreferrer">
							<span><?php esc_html_e( 'LinkedIn:', 'codice' ); ?></span>
							<span>linkedin.com/in/brunoanastassakis</span>
						</a>
					</li>
					<li class="maintenance-contact__item">
						<a class="maintenance-contact__link" href="<?php echo esc_url( 'https://wa.me/5521986957214' ); ?>" target="_blank" rel="noopener noreferrer">
							<span><?php esc_html_e( 'WhatsApp:', 'codice' ); ?></span>
							<span>+55 21 98695-7214</span>
						</a>
					</li>
				</ul>
			</div>
		</section>
		<figure class="maintenance-visual">
			<img
				src="<?php echo esc_url( get_template_directory_uri() . '/assets/img/maintenance-retroprint.png' ); ?>"
				width="1024"
				height="1536"
				alt="<?php esc_attr_e( 'Composição editorial retroprint com camadas de papel, texturas e formas gráficas, sugerindo um espaço em preparação.', 'codice' ); ?>"
				loading="eager"
				decoding="async"
			>
		</figure>
	</div>
</main>

<?php wp_footer(); ?>
</body>
</html>
