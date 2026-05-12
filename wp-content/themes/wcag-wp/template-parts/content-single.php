<?php
/**
 * Single post content part.
 *
 * @package WCAG_WP
 */
?>
<article id="post-<?php the_ID(); ?>" <?php post_class( 'entry entry--single' ); ?>>
	<header class="entry-header">
		<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
		<?php if ( 'post' === get_post_type() ) : ?>
			<div class="entry-meta">
				<?php wcag_wp_posted_on(); ?>
				<?php wcag_wp_posted_by(); ?>
			</div>
		<?php endif; ?>
	</header>

	<?php wcag_wp_post_thumbnail(); ?>

	<div class="entry-content">
		<?php the_content(); ?>

		<?php
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

	<footer class="entry-footer">
		<?php wcag_wp_entry_footer(); ?>
	</footer>
</article>
