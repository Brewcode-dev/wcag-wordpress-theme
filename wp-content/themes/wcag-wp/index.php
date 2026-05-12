<?php
/**
 * Main index template — fallback for all queries that don't have a more specific template.
 *
 * @package WCAG_WP
 */

get_header(); ?>

<main id="main" class="site-main" tabindex="-1">
	<?php if ( have_posts() ) : ?>

		<?php if ( is_home() && ! is_front_page() ) : ?>
			<header class="page-header">
				<h1 class="page-title"><?php single_post_title(); ?></h1>
			</header>
		<?php endif; ?>

		<?php
		while ( have_posts() ) :
			the_post();
			get_template_part( 'template-parts/content', get_post_type() );
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
