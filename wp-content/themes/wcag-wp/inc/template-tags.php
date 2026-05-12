<?php
/**
 * Custom template tags.
 *
 * @package WCAG_WP
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'wcag_wp_posted_on' ) ) {
	function wcag_wp_posted_on() {
		$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
		if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
			$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated screen-reader-text" datetime="%3$s">%4$s</time>';
		}

		$time_string = sprintf(
			$time_string,
			esc_attr( get_the_date( DATE_W3C ) ),
			esc_html( get_the_date() ),
			esc_attr( get_the_modified_date( DATE_W3C ) ),
			esc_html( get_the_modified_date() )
		);

		printf(
			/* translators: %s: post date. */
			'<span class="posted-on"><span class="screen-reader-text">%1$s </span><a href="%2$s" rel="bookmark">%3$s</a></span>',
			esc_html__( 'Posted on', 'wcag-wp' ),
			esc_url( get_permalink() ),
			$time_string // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		);
	}
}

if ( ! function_exists( 'wcag_wp_posted_by' ) ) {
	function wcag_wp_posted_by() {
		printf(
			/* translators: %s: post author. */
			'<span class="byline"><span class="screen-reader-text">%1$s </span><span class="author vcard"><a class="url fn n" href="%2$s">%3$s</a></span></span>',
			esc_html__( 'Author', 'wcag-wp' ),
			esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
			esc_html( get_the_author() )
		);
	}
}

if ( ! function_exists( 'wcag_wp_entry_footer' ) ) {
	function wcag_wp_entry_footer() {
		if ( 'post' === get_post_type() ) {
			$categories_list = get_the_category_list( esc_html__( ', ', 'wcag-wp' ) );
			if ( $categories_list ) {
				printf(
					'<span class="cat-links"><span class="screen-reader-text">%1$s </span>%2$s</span>',
					esc_html__( 'Categories:', 'wcag-wp' ),
					$categories_list // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				);
			}

			$tags_list = get_the_tag_list( '', esc_html__( ', ', 'wcag-wp' ) );
			if ( $tags_list ) {
				printf(
					'<span class="tags-links"><span class="screen-reader-text">%1$s </span>%2$s</span>',
					esc_html__( 'Tags:', 'wcag-wp' ),
					$tags_list // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				);
			}
		}

		if ( ! is_single() && ! post_password_required() && ( comments_open() || get_comments_number() ) ) {
			echo '<span class="comments-link">';
			comments_popup_link(
				sprintf(
					wp_kses(
						/* translators: %s: post title */
						__( 'Leave a comment<span class="screen-reader-text"> on %s</span>', 'wcag-wp' ),
						array( 'span' => array( 'class' => array() ) )
					),
					wp_kses_post( get_the_title() )
				)
			);
			echo '</span>';
		}

		edit_post_link(
			sprintf(
				wp_kses(
					/* translators: %s: post title */
					__( 'Edit <span class="screen-reader-text">%s</span>', 'wcag-wp' ),
					array( 'span' => array( 'class' => array() ) )
				),
				wp_kses_post( get_the_title() )
			),
			'<span class="edit-link">',
			'</span>'
		);
	}
}

if ( ! function_exists( 'wcag_wp_post_thumbnail' ) ) {
	function wcag_wp_post_thumbnail() {
		if ( post_password_required() || is_attachment() || ! has_post_thumbnail() ) {
			return;
		}

		$alt = trim( wp_strip_all_tags( get_post_meta( get_post_thumbnail_id(), '_wp_attachment_image_alt', true ) ) );
		$is_decorative = empty( $alt );

		if ( is_singular() ) {
			?>
			<figure class="post-thumbnail">
				<?php
				the_post_thumbnail(
					'wcag-wp-featured',
					array(
						'alt'      => $is_decorative ? '' : $alt,
						'role'     => $is_decorative ? 'presentation' : null,
						'loading'  => 'eager',
						'decoding' => 'async',
					)
				);
				?>
			</figure>
			<?php
		} else {
			?>
			<a class="post-thumbnail" href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1">
				<?php
				the_post_thumbnail(
					'medium_large',
					array(
						'alt'      => '',
						'loading'  => 'lazy',
						'decoding' => 'async',
					)
				);
				?>
			</a>
			<?php
		}
	}
}
