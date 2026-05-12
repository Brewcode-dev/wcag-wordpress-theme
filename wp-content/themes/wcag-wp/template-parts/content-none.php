<?php
/**
 * Empty state.
 *
 * @package WCAG_WP
 */
?>
<section class="no-results not-found" aria-labelledby="no-results-title">
	<header class="page-header">
		<h1 id="no-results-title" class="page-title"><?php esc_html_e( 'Nothing found', 'wcag-wp' ); ?></h1>
	</header>

	<div class="page-content">
		<?php if ( is_search() ) : ?>
			<p><?php esc_html_e( 'Sorry, no results matched your search. Try different keywords.', 'wcag-wp' ); ?></p>
			<?php get_search_form(); ?>
		<?php else : ?>
			<p><?php esc_html_e( 'It seems we cannot find what you are looking for.', 'wcag-wp' ); ?></p>
			<?php get_search_form(); ?>
		<?php endif; ?>
	</div>
</section>
