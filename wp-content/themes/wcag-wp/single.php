<?php
/**
 * Single post template.
 *
 * @package WCAG_WP
 */

get_header(); ?>

<main id="main" class="site-main" tabindex="-1">
	<?php
	while ( have_posts() ) :
		the_post();

		get_template_part( 'template-parts/content', 'single' );

		$prev = get_previous_post();
		$next = get_next_post();

		if ( $prev || $next ) :
			?>
			<nav class="post-navigation" role="navigation" aria-label="<?php esc_attr_e( 'Post', 'wcag-wp' ); ?>">
				<h2 class="screen-reader-text"><?php esc_html_e( 'Post navigation', 'wcag-wp' ); ?></h2>
				<div class="nav-links">
					<?php
					previous_post_link(
						'<div class="nav-previous">%link</div>',
						'<span class="meta-nav" aria-hidden="true">&larr;</span> <span class="screen-reader-text">' . esc_html__( 'Previous post:', 'wcag-wp' ) . '</span><span class="post-title">%title</span>'
					);
					next_post_link(
						'<div class="nav-next">%link</div>',
						'<span class="screen-reader-text">' . esc_html__( 'Next post:', 'wcag-wp' ) . '</span><span class="post-title">%title</span> <span class="meta-nav" aria-hidden="true">&rarr;</span>'
					);
					?>
				</div>
			</nav>
			<?php
		endif;

		if ( comments_open() || get_comments_number() ) {
			comments_template();
		}

	endwhile;
	?>
</main>

<?php
get_sidebar();
get_footer();
