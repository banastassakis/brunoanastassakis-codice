<?php
/**
 * Template Name: Sobre
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
?>

<main id="conteudo" class="site-main" role="main" tabindex="-1">
	<div class="container-reading">
		<article class="page-sobre">
			<header class="entry-header">
				<span class="meta entry-header__label"><?php esc_html_e( 'Autor', 'codice' ); ?></span>
				<h1 class="<?php echo esc_attr( $hide_title ? 'entry-header__title sr-only' : 'entry-header__title' ); ?>"><?php the_title(); ?></h1>
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
							'Escrevo sobre conteúdo, comunicação, eventos, inteligência artificial e os ecossistemas que organizam a operação editorial.',
							'codice'
						);
						?>
					</p>
					<p>
						<?php
						esc_html_e(
							'Este espaço funciona como um registro ativo de ideias, análises e reflexões estruturadas sobre a intersecção de comunicação, tecnologia e produto. Menos um currículo estático e mais uma ferramenta de interlocução.',
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
							'Acredito que conteúdo precisa ser tratado como produto editorial: estrutura, acervo, governança, experiência e ciclo de vida. Comunicação, nesse contexto, é linguagem pública e construção de autoridade em mercados complexos, não apenas tática de campanha ou performance.',
							'codice'
						);
						?>
					</p>
					<p>
						<?php
						esc_html_e(
							'A tecnologia — incluindo inteligência artificial e automação — deve ser compreendida como uma camada operacional e de repetição. Ela é governada para reduzir o ruído informacional e otimizar processos, sem que o hype obscureça o rigor intelectual ou as prioridades organizacionais.',
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
							'Com cerca de 15 anos de atuação profissional acumulados entre Portugal e Brasil, minha trajetória desenvolve-se na convergência entre comunicação institucional, design de produtos digitais, curadoria de eventos corporativos e operações de marketing estratégico.',
							'codice'
						);
						?>
					</p>
					<p>
						<?php
						esc_html_e(
							'Trabalho na estruturação de fluxos de produção, na coordenação de stakeholders em ambientes complexos e no desenho de experiências de leitura e relacionamento que geram credibilidade e constroem pontes intelectuais.',
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
							'Atualmente, atuo na liderança de operações e no direcionamento de canais de conteúdo para públicos B2B de alta complexidade. Coordeno iniciativas de educação corporativa, curadoria de grandes painéis de mercado e governança de plataformas de comunicação digital voltadas à geração de pensamento crítico e relacionamentos duráveis.',
							'codice'
						);
						?>
					</p>
					<p>
						<?php
						esc_html_e(
							'Para consultar o histórico nominal completo, com listagem de cargos e passagens por organizações, recomendo acessar meu perfil profissional externo.',
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
					<a href="https://linkedin.com/in/brunoanastassakis" class="btn" target="_blank" rel="noopener noreferrer">
						<?php esc_html_e( 'Perfil no LinkedIn', 'codice' ); ?>
					</a>
					<a href="<?php echo esc_url( get_permalink( get_page_by_path( 'contato' ) ) ); ?>" class="btn btn-ghost">
						<?php esc_html_e( 'Iniciar conversa', 'codice' ); ?>
					</a>
				</section>
			</div>
		</article>
	</div>
</main>

<?php
get_footer();
