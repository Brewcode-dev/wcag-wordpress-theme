<?php
/**
 * 404 template.
 *
 * @package WCAG_WP
 */

get_header(); ?>

<main id="main" class="site-main error-404 not-found" tabindex="-1">
	<header class="page-header">
		<h1 class="page-title"><?php esc_html_e( 'Page not found', 'wcag-wp' ); ?></h1>
	</header>

	<div class="page-content">
		<p><?php esc_html_e( 'The page you are looking for could not be found. It may have been moved or removed.', 'wcag-wp' ); ?></p>

		<h2><?php esc_html_e( 'Try one of these:', 'wcag-wp' ); ?></h2>
		<ul>
			<li><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Go to home page', 'wcag-wp' ); ?></a></li>
		</ul>

		<h2><?php esc_html_e( 'Search the site', 'wcag-wp' ); ?></h2>
		<?php get_search_form(); ?>

		<h2><?php esc_html_e( 'Recent posts', 'wcag-wp' ); ?></h2>
		<?php
		$recent = new WP_Query(
			array(
				'posts_per_page'      => 5,
				'ignore_sticky_posts' => true,
				'no_found_rows'       => true,
			)
		);
		if ( $recent->have_posts() ) {
			echo '<ul>';
			while ( $recent->have_posts() ) {
				$recent->the_post();
				echo '<li><a href="' . esc_url( get_permalink() ) . '">' . esc_html( get_the_title() ) . '</a></li>';
			}
			echo '</ul>';
			wp_reset_postdata();
		}
		?>
	</div>
</main>

<?php
get_footer();
