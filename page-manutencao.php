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

$site_name     = get_bloginfo( 'name' );
$contact_email = function_exists( 'codice_get_contact_email' ) ? codice_get_contact_email() : get_option( 'admin_email' );
$linkedin_url  = function_exists( 'codice_get_linkedin_url' ) ? codice_get_linkedin_url() : '';
$whatsapp_url  = function_exists( 'codice_get_whatsapp_url' ) ? codice_get_whatsapp_url() : '';
$visual_path   = get_template_directory() . '/assets/img/maintenance-retroprint.jpg';
$visual_url    = get_template_directory_uri() . '/assets/img/maintenance-retroprint.jpg';
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php echo esc_attr( get_bloginfo( 'charset' ) ); ?>">
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
			<div class="maintenance-screen__top">
				<header class="maintenance-identity">
					<p class="maintenance-identity__name">
						<?php echo esc_html( $site_name ); ?>
						<span class="maintenance-identity__separator" aria-hidden="true">|</span>
						<span class="maintenance-identity__status"><?php esc_html_e( 'Em manutenção', 'codice' ); ?></span>
					</p>
				</header>
			</div>

			<div class="maintenance-screen__center">
				<h1 id="maintenance-title" class="maintenance-screen__title">
					<?php esc_html_e( 'Este espaço está sendo preparado.', 'codice' ); ?>
				</h1>
			</div>

			<div class="maintenance-screen__bottom">
				<div class="maintenance-contact" aria-label="<?php esc_attr_e( 'Canais de contato', 'codice' ); ?>">
					<ul class="maintenance-contact__list list-bare">
						<?php if ( is_email( $contact_email ) ) : ?>
							<li class="maintenance-contact__item">
								<a class="maintenance-contact__link" href="<?php echo esc_url( 'mailto:' . $contact_email ); ?>">
									<span class="maintenance-contact__icon" aria-hidden="true">
										<svg viewBox="0 0 24 24" focusable="false">
											<rect x="3" y="5" width="18" height="14" rx="2" />
											<path d="M3 7l9 6 9-6" />
										</svg>
									</span>
									<span class="maintenance-contact__label"><?php esc_html_e( 'E-mail', 'codice' ); ?></span>
								</a>
							</li>
						<?php endif; ?>
						<?php if ( $linkedin_url ) : ?>
							<li class="maintenance-contact__item">
								<a class="maintenance-contact__link" href="<?php echo esc_url( $linkedin_url ); ?>" target="_blank" rel="noopener noreferrer">
									<span class="maintenance-contact__icon" aria-hidden="true">
										<svg viewBox="0 0 24 24" focusable="false">
											<path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6z" />
											<rect x="2" y="9" width="4" height="12" />
											<circle cx="4" cy="4" r="2" />
										</svg>
									</span>
									<span class="maintenance-contact__label"><?php esc_html_e( 'LinkedIn', 'codice' ); ?></span>
								</a>
							</li>
						<?php endif; ?>
						<?php if ( $whatsapp_url ) : ?>
							<li class="maintenance-contact__item">
								<a class="maintenance-contact__link" href="<?php echo esc_url( $whatsapp_url ); ?>" target="_blank" rel="noopener noreferrer">
									<span class="maintenance-contact__icon" aria-hidden="true">
										<svg viewBox="0 0 24 24" focusable="false">
											<path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z" />
										</svg>
									</span>
									<span class="maintenance-contact__label"><?php esc_html_e( 'WhatsApp', 'codice' ); ?></span>
								</a>
							</li>
						<?php endif; ?>
					</ul>
				</div>
			</div>
		</section>
		<?php if ( file_exists( $visual_path ) ) : ?>
			<figure class="maintenance-visual">
				<img
					src="<?php echo esc_url( $visual_url ); ?>"
					width="900"
					height="1350"
					alt="<?php esc_attr_e( 'Composição editorial retroprint com camadas de papel, texturas e formas gráficas, sugerindo um espaço em preparação.', 'codice' ); ?>"
					loading="eager"
					decoding="async"
				>
			</figure>
		<?php endif; ?>
	</div>
</main>

<?php wp_footer(); ?>
</body>
</html>
