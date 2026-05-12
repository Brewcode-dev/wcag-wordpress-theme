<?php
/**
 * Search result content part.
 *
 * @package WCAG_WP
 */
?>
<article id="post-<?php the_ID(); ?>" <?php post_class( 'entry entry--search' ); ?>>
	<header class="entry-header">
		<?php the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' ); ?>
		<?php if ( 'post' === get_post_type() ) : ?>
			<div class="entry-meta">
				<?php wcag_wp_posted_on(); ?>
			</div>
		<?php endif; ?>
	</header>

	<div class="entry-summary">
		<?php the_excerpt(); ?>
	</div>
</article>
