<?php
/**
 * Search results template.
 *
 * @package WCAG_WP
 */

get_header(); ?>

<main id="main" class="site-main" tabindex="-1">
	<header class="page-header">
		<h1 class="page-title">
			<?php
			printf(
				/* translators: %s: search query. */
				esc_html__( 'Search results for: %s', 'wcag-wp' ),
				'<span>' . get_search_query() . '</span>'
			);
			?>
		</h1>
	</header>

	<?php if ( have_posts() ) : ?>
		<p role="status" class="search-results-count">
			<?php
			printf(
				/* translators: %d: total results. */
				esc_html( _n( '%d result found.', '%d results found.', (int) $wp_query->found_posts, 'wcag-wp' ) ),
				(int) $wp_query->found_posts
			);
			?>
		</p>

		<?php
		while ( have_posts() ) :
			the_post();
			get_template_part( 'template-parts/content', 'search' );
		endwhile;

		wcag_wp_the_posts_pagination();
		?>
	<?php else : ?>
		<?php get_template_part( 'template-parts/content', 'none' ); ?>
	<?php endif; ?>
</main>

<?php
get_sidebar();
get_footer();
