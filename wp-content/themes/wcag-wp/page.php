<?php
/**
 * Page template.
 *
 * @package WCAG_WP
 */

get_header(); ?>

<main id="main" class="site-main" tabindex="-1">
	<?php
	while ( have_posts() ) :
		the_post();
		get_template_part( 'template-parts/content', 'page' );

		if ( comments_open() || get_comments_number() ) {
			comments_template();
		}
	endwhile;
	?>
</main>

<?php
get_footer();
