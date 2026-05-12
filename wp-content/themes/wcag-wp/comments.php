<?php
/**
 * Comments template.
 *
 * @package WCAG_WP
 */

if ( post_password_required() ) {
	return;
}
?>
<section id="comments" class="comments-area" aria-labelledby="comments-title">
	<?php if ( have_comments() ) : ?>
		<h2 id="comments-title" class="comments-title">
			<?php
			$count = get_comments_number();
			if ( '1' === $count ) {
				printf(
					/* translators: %s: post title. */
					esc_html__( 'One thought on &ldquo;%s&rdquo;', 'wcag-wp' ),
					'<span>' . esc_html( get_the_title() ) . '</span>'
				);
			} else {
				printf(
					/* translators: 1: comment count, 2: post title. */
					esc_html( _nx( '%1$s thought on &ldquo;%2$s&rdquo;', '%1$s thoughts on &ldquo;%2$s&rdquo;', (int) $count, 'comments title', 'wcag-wp' ) ),
					esc_html( number_format_i18n( $count ) ),
					'<span>' . esc_html( get_the_title() ) . '</span>'
				);
			}
			?>
		</h2>

		<ol class="comment-list">
			<?php
			wp_list_comments(
				array(
					'style'      => 'ol',
					'short_ping' => true,
					'avatar_size' => 48,
				)
			);
			?>
		</ol>

		<?php
		the_comments_navigation(
			array(
				'prev_text' => '<span aria-hidden="true">&larr;</span> ' . __( 'Older comments', 'wcag-wp' ),
				'next_text' => __( 'Newer comments', 'wcag-wp' ) . ' <span aria-hidden="true">&rarr;</span>',
			)
		);
		?>

		<?php if ( ! comments_open() ) : ?>
			<p class="no-comments"><?php esc_html_e( 'Comments are closed.', 'wcag-wp' ); ?></p>
		<?php endif; ?>

	<?php endif; ?>

	<?php comment_form(); ?>
</section>
