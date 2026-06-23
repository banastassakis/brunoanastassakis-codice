<?php
/**
 * page-sobre.php — Página Sobre
 *
 * Narrativa em primeira pessoa sobre o autor, sua tese de posicionamento,
 * trajetória e áreas de atuação. Sem currículo cronológico comercial,
 * sem logos e sem nomear empregadores.
 *
 * @package codice
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

$hide_title = function_exists( 'codice_should_hide_page_title' ) && codice_should_hide_page_title( get_the_ID() );
$contato_page = get_page_by_path( 'contato' );
$contato_url  = $contato_page ? get_permalink( $contato_page ) : home_url( '/contato/' );
$linkedin_url = function_exists( 'codice_get_linkedin_url' ) ? codice_get_linkedin_url() : '';
?>

<main id="conteudo" class="site-main" role="main" tabindex="-1">
	<div class="container-reading">
		<?php
		if ( function_exists( 'codice_render_breadcrumbs' ) ) {
			codice_render_breadcrumbs();
		}
		?>
		<article class="page-sobre">
			<header class="entry-header">
				<span class="meta entry-header__label"><?php esc_html_e( 'Autor', 'codice' ); ?></span>
				<h1 class="<?php echo esc_attr( $hide_title ? 'entry-header__title sr-only' : 'entry-header__title' ); ?>"><?php echo esc_html( get_the_title() ); ?></h1>
			</header>

			<div class="entry-content">
				<?php
				// ── 1. BIO EDITORIAL ──────────────────────────────────────────────────
				// Apresentação pessoal e o propósito da publicação.
				?>
				<section class="sobre-section sobre-section--bio mb-lg">
					<p class="lede">
						<?php
						esc_html_e(
							'Texto provisório para a página Sobre da publicação editorial.',
							'codice'
						);
						?>
					</p>
					<p>
						<?php
						esc_html_e(
							'A versão final será escrita na etapa de conteúdo, com foco no lugar de autoria, nos temas da publicação e no escopo de atuação, sem transformar a página em currículo, portfólio ou vitrine comercial.',
							'codice'
						);
						?>
					</p>
				</section>

				<hr class="section-divider mb-lg">

				<?php
				// ── 2. TESE DE POSICIONAMENTO ──────────────────────────────────────────
				// A visão do autor sobre como os domínios de trabalho se interconectam.
				?>
				<section class="sobre-section sobre-section--tese mb-lg">
					<h2 class="h3"><?php esc_html_e( 'Tese de posicionamento', 'codice' ); ?></h2>
					<p>
						<?php
						esc_html_e(
							'Esta seção reservará a tese editorial do site: a relação entre conteúdo, comunicação, eventos, IA e ecossistemas editoriais como campo de pensamento.',
							'codice'
						);
						?>
					</p>
					<p>
						<?php
						esc_html_e(
							'O texto definitivo deve manter tom sóbrio, evitar promessas de performance e tratar tecnologia como camada de método, não como argumento promocional.',
							'codice'
						);
						?>
					</p>
				</section>

				<hr class="section-divider mb-lg">

				<?php
				// ── 3. TRAJETÓRIA RESUMIDA ─────────────────────────────────────────────
				// Narrativa profissional curta (cerca de 15 anos entre Portugal e Brasil).
				?>
				<section class="sobre-section sobre-section--trajetoria mb-lg">
					<h2 class="h3"><?php esc_html_e( 'Trajetória', 'codice' ); ?></h2>
					<p>
						<?php
						esc_html_e(
							'A trajetória será resumida em texto corrido na etapa de conteúdo real, com atenção ao escopo de atuação e sem lista cronológica de cargos.',
							'codice'
						);
						?>
					</p>
					<p>
						<?php
						esc_html_e(
							'Até lá, este bloco permanece como placeholder para validar ritmo, largura de leitura e hierarquia visual da página.',
							'codice'
						);
						?>
					</p>
				</section>

				<hr class="section-divider mb-lg">

				<?php
				// ── 4. ÁREAS DE ATUAÇÃO ───────────────────────────────────────────────
				// Domínios de interesse, alinhados com as categorias do site.
				?>
				<section class="sobre-section sobre-section--areas mb-lg">
					<h2 class="h3"><?php esc_html_e( 'Áreas de atuação', 'codice' ); ?></h2>
					<ul class="sobre-areas-list list-bare">
						<li class="sobre-area-item mb-sm">
							<strong class="sobre-area-title"><?php esc_html_e( 'Conteúdo', 'codice' ); ?></strong>
							<span class="sobre-area-desc d-block text-slate font-sans">
								<?php esc_html_e( 'Estrutura, acervo, governança, experiência e ciclo de vida do conteúdo como produto editorial.', 'codice' ); ?>
							</span>
						</li>
						<li class="sobre-area-item mb-sm">
							<strong class="sobre-area-title"><?php esc_html_e( 'Comunicação', 'codice' ); ?></strong>
							<span class="sobre-area-desc d-block text-slate font-sans">
								<?php esc_html_e( 'Linguagem pública, reputação, posicionamento, marca, canais e clareza em mercados complexos.', 'codice' ); ?>
							</span>
						</li>
						<li class="sobre-area-item mb-sm">
							<strong class="sobre-area-title"><?php esc_html_e( 'Eventos', 'codice' ); ?></strong>
							<span class="sobre-area-desc d-block text-slate font-sans">
								<?php esc_html_e( 'Curadoria, formatos, experiência, pauta, audiência e circulação de ideias.', 'codice' ); ?>
							</span>
						</li>
						<li class="sobre-area-item mb-sm">
							<strong class="sobre-area-title"><?php esc_html_e( 'IA', 'codice' ); ?></strong>
							<span class="sobre-area-desc d-block text-slate font-sans">
								<?php esc_html_e( 'Automação, pesquisa, organização, produção, análise, documentação e reaproveitamento de conteúdo.', 'codice' ); ?>
							</span>
						</li>
						<li class="sobre-area-item">
							<strong class="sobre-area-title"><?php esc_html_e( 'Ecossistema', 'codice' ); ?></strong>
							<span class="sobre-area-desc d-block text-slate font-sans">
								<?php esc_html_e( 'Integração entre conteúdo, comunicação, eventos, IA, canais, pessoas, decisões, fluxos e operação editorial.', 'codice' ); ?>
							</span>
						</li>
					</ul>
				</section>

				<hr class="section-divider mb-lg">

				<?php
				// ── 5. PROVA PROFISSIONAL DISCRETA ─────────────────────────────────────
				// Lastro de credibilidade sem nomear marcas/empregadores de forma direta.
				?>
				<section class="sobre-section sobre-section--prova mb-lg">
					<h2 class="h3"><?php esc_html_e( 'Atuação recente', 'codice' ); ?></h2>
					<p>
						<?php
						esc_html_e(
							'A prova profissional será discreta e descritiva, limitada ao escopo de atuação que fizer sentido para a publicação.',
							'codice'
						);
						?>
					</p>
					<p>
						<?php
						esc_html_e(
							'Detalhes nominais, quando necessários, devem permanecer em perfis externos e materiais profissionais próprios.',
							'codice'
						);
						?>
					</p>
				</section>

				<hr class="section-divider mb-lg">

				<?php
				// ── 6 e 7. LINKS DE SAÍDA ──────────────────────────────────────────────
				// LinkedIn, CV e Contato.
				?>
				<section class="sobre-section sobre-section--links flex flex-wrap gap-md">
					<?php if ( $linkedin_url ) : ?>
						<a href="<?php echo esc_url( $linkedin_url ); ?>" class="btn" target="_blank" rel="noopener noreferrer">
							<?php esc_html_e( 'Perfil no LinkedIn', 'codice' ); ?>
						</a>
					<?php endif; ?>
					<a href="<?php echo esc_url( $contato_url ); ?>" class="btn btn-ghost">
						<?php esc_html_e( 'Iniciar conversa', 'codice' ); ?>
					</a>
				</section>
			</div>
		</article>
	</div>
</main>

<?php
get_footer();
