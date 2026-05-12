<?php
/**
 * Default content part (post in archive).
 *
 * @package WCAG_WP
 */
?>
<article id="post-<?php the_ID(); ?>" <?php post_class( 'entry' ); ?>>
	<header class="entry-header">
		<?php
		if ( is_singular() ) {
			the_title( '<h1 class="entry-title">', '</h1>' );
		} else {
			the_title(
				sprintf(
					'<h2 class="entry-title"><a href="%s" rel="bookmark">',
					esc_url( get_permalink() )
				),
				'</a></h2>'
			);
		}

		if ( 'post' === get_post_type() ) :
			?>
			<div class="entry-meta">
				<?php wcag_wp_posted_on(); ?>
				<?php wcag_wp_posted_by(); ?>
			</div>
			<?php
		endif;
		?>
	</header>

	<?php wcag_wp_post_thumbnail(); ?>

	<div class="entry-summary">
		<?php the_excerpt(); ?>
		<p><a class="more-link" href="<?php echo esc_url( get_permalink() ); ?>" aria-label="<?php
			printf( esc_attr__( 'Read more: %s', 'wcag-wp' ), esc_attr( get_the_title() ) );
		?>"><?php esc_html_e( 'Read more', 'wcag-wp' ); ?> <span class="screen-reader-text"><?php the_title(); ?></span></a></p>
	</div>

	<footer class="entry-footer">
		<?php wcag_wp_entry_footer(); ?>
	</footer>
</article>
