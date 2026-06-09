<?php
/**
 * footer.php — Rodapé do tema Códice
 *
 * Fecha o conteúdo principal, renderiza o rodapé sóbrio
 * e inclui wp_footer() antes de fechar o documento.
 *
 * @package codice
 */
?>

<footer class="site-footer" role="contentinfo">
	<div class="site-footer__inner">
		<p class="site-footer__copy">
			<?php
			printf(
				/* translators: 1: nome do site, 2: ano atual */
				esc_html__( '%1$s © %2$s', 'codice' ),
				esc_html( get_bloginfo( 'name' ) ),
				esc_html( gmdate( 'Y' ) )
			);
			?>
		</p>
	</div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
