<?php
/**
 * Page content part.
 *
 * @package WCAG_WP
 */
?>
<article id="post-<?php the_ID(); ?>" <?php post_class( 'entry entry--page' ); ?>>
	<header class="entry-header">
		<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
	</header>

	<?php wcag_wp_post_thumbnail(); ?>

	<div class="entry-content">
		<?php
		the_content();

		wp_link_pages(
			array(
				'before'      => '<nav class="page-links" role="navigation" aria-label="' . esc_attr__( 'Page', 'wcag-wp' ) . '"><span class="screen-reader-text">' . esc_html__( 'Pages:', 'wcag-wp' ) . '</span>',
				'after'       => '</nav>',
				'link_before' => '<span>',
				'link_after'  => '</span>',
			)
		);
		?>
	</div>

	<?php if ( get_edit_post_link() ) : ?>
		<footer class="entry-footer">
			<?php
			edit_post_link(
				sprintf(
					wp_kses(
						/* translators: %s: post title */
						__( 'Edit <span class="screen-reader-text">%s</span>', 'wcag-wp' ),
						array( 'span' => array( 'class' => array() ) )
					),
					wp_kses_post( get_the_title() )
				),
				'<span class="edit-link">',
				'</span>'
			);
			?>
		</footer>
	<?php endif; ?>
</article>
