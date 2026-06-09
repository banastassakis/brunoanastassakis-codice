<?php
/**
 * index.php — Template fallback do tema Códice
 *
 * Cobre qualquer tipo de página não tratado por um template específico.
 * Renderiza o loop básico de posts quando há conteúdo, ou um estado
 * vazio elegante quando não há.
 *
 * Conforme as regras do Códice: placeholder-first — nunca quebra
 * por falta de posts. Estado vazio tratado com elegância.
 *
 * @package codice
 */

get_header();
?>

<main id="conteudo" class="site-main" role="main" tabindex="-1">
	<div class="container">

		<?php if ( have_posts() ) : ?>

			<ul class="posts-list" role="list">

				<?php
				while ( have_posts() ) :
					the_post();
					?>

					<li class="post-item">
						<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

							<header class="post-item__header">

								<p class="post-item__meta">
									<?php
									// Categoria principal (a primeira), se houver.
									$categories = get_the_category();
									if ( ! empty( $categories ) ) {
										echo '<span class="post-item__cat">' . esc_html( $categories[0]->name ) . '</span>';
										echo '<span class="post-item__meta-sep" aria-hidden="true"> &middot; </span>';
									}

									// Data no formato Mono / metadados.
									echo '<time class="post-item__date" datetime="' . esc_attr( get_the_date( 'Y-m-d' ) ) . '">';
									echo esc_html( get_the_date() );
									echo '</time>';
									?>
								</p>

								<h2 class="post-item__title">
									<a href="<?php the_permalink(); ?>">
										<?php the_title(); ?>
									</a>
								</h2>

							</header>

							<?php if ( has_excerpt() || get_the_excerpt() ) : ?>
								<div class="post-item__excerpt">
									<?php the_excerpt(); ?>
								</div>
							<?php endif; ?>

							<a class="post-item__read-more" href="<?php the_permalink(); ?>" aria-label="<?php echo esc_attr( sprintf( /* translators: %s: título do post */ __( 'Ler: %s', 'codice' ), get_the_title() ) ); ?>">
								<?php esc_html_e( 'Ler artigo', 'codice' ); ?>
							</a>

						</article>
					</li>

				<?php endwhile; ?>

			</ul>

			<?php
			// Paginação numérica (será estilizada na Etapa 2).
			the_posts_pagination(
				array(
					'prev_text' => esc_html__( '&larr; Anterior', 'codice' ),
					'next_text' => esc_html__( 'Próximo &rarr;', 'codice' ),
				)
			);
			?>

		<?php else : ?>

			<div class="empty-state" role="status">
				<h1 class="empty-state__title">
					<?php esc_html_e( 'Nenhum conteúdo publicado ainda.', 'codice' ); ?>
				</h1>
				<p class="empty-state__text">
					<?php esc_html_e( 'Os artigos aparecerão aqui assim que forem publicados.', 'codice' ); ?>
				</p>
			</div>

		<?php endif; ?>

	</div>
</main>

<?php
get_footer();
