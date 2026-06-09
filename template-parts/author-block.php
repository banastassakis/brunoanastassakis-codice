<?php
/**
 * template-parts/author-block.php — Bloco reutilizável curto sobre o autor
 *
 * Usado na home editorial (front-page.php).
 * Texto placeholder neutro e editorial. Sem empregadores, sem clientes,
 * sem métricas, sem cargos, sem eventos, sem resultados inventados.
 * Tom editorial, não promocional.
 *
 * Link para a página Sobre com fallback seguro (não gera link falso se a
 * página não existir no banco).
 *
 * Regras Códice:
 * - Toda saída escapada; strings de UI traduzíveis (text domain 'codice').
 * - HTML5 semântico.
 * - Não é currículo nem lista de serviços.
 *
 * @package codice
 */

// Segurança: não executar diretamente.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// ── Link para a página Sobre (fallback seguro) ──────────────────────────────
// Tenta localizar a página pelo slug 'sobre'. Se não existir, não gera link.
$sobre_page = get_page_by_path( 'sobre' );
$sobre_url  = $sobre_page ? get_permalink( $sobre_page->ID ) : '';

?>

<div class="author-block">

	<span class="section-label"><?php esc_html_e( 'O autor', 'codice' ); ?></span>

	<div class="author-block__content">

		<p class="author-block__text">
			<?php
			esc_html_e(
				'Bruno Anastassakis escreve sobre conteúdo, comunicação, eventos, IA e ecossistema editorial. '
				. 'Esta publicação reúne ensaios, análises e textos de método sobre os campos em que pensa e atua.',
				'codice'
			);
			?>
		</p>

		<?php if ( $sobre_url ) : ?>
			<a
				class="author-block__link"
				href="<?php echo esc_url( $sobre_url ); ?>"
			>
				<?php esc_html_e( 'Sobre o autor', 'codice' ); ?> &rarr;
			</a>
		<?php endif; ?>

	</div><!-- .author-block__content -->

</div><!-- .author-block -->
