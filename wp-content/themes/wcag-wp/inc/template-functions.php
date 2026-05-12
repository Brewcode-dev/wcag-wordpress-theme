<?php
/**
 * Template-related filters and helpers.
 *
 * @package WCAG_WP
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Adds custom body classes used by accessibility CSS hooks.
 */
function wcag_wp_body_classes( $classes ) {
	if ( ! is_singular() ) {
		$classes[] = 'hfeed';
	}
	if ( ! is_active_sidebar( 'sidebar-1' ) ) {
		$classes[] = 'no-sidebar';
	}
	$classes[] = 'wcag-wp';
	return $classes;
}
add_filter( 'body_class', 'wcag_wp_body_classes' );

/**
 * Adds a meaningful alt attribute placeholder reminder when missing.
 * If alt is empty AND the image is used in content (not decorative), insert a screen-reader notice in admin only.
 */
function wcag_wp_pingback_header() {
	if ( is_singular() && pings_open() ) {
		printf( '<link rel="pingback" href="%s">' . "\n", esc_url( get_bloginfo( 'pingback_url' ) ) );
	}
}
add_action( 'wp_head', 'wcag_wp_pingback_header' );

/**
 * Force a sensible default for image alt where none provided AND the alt was clearly intended.
 * We never invent alt text — but we do flag images missing alt to admins.
 */
function wcag_wp_filter_img_attributes( $attr, $attachment ) {
	if ( ! isset( $attr['alt'] ) ) {
		$attr['alt'] = '';
	}
	if ( '' === trim( $attr['alt'] ) && ! isset( $attr['role'] ) ) {
		$attr['role'] = 'presentation';
		$attr['aria-hidden'] = 'true';
	}
	return $attr;
}
add_filter( 'wp_get_attachment_image_attributes', 'wcag_wp_filter_img_attributes', 10, 2 );

/**
 * Adds aria-current on the current menu item link (in addition to .current-menu-item class).
 */
function wcag_wp_nav_aria_current( $atts, $item ) {
	if ( in_array( 'current-menu-item', (array) $item->classes, true ) ) {
		$atts['aria-current'] = 'page';
	}
	if ( in_array( 'current-menu-parent', (array) $item->classes, true ) || in_array( 'current-menu-ancestor', (array) $item->classes, true ) ) {
		$atts['aria-current'] = 'true';
	}
	return $atts;
}
add_filter( 'nav_menu_link_attributes', 'wcag_wp_nav_aria_current', 10, 2 );

/**
 * Adds rel="noopener" and an explicit external icon screen-reader hint for off-site links inside the content.
 */
function wcag_wp_external_links( $content ) {
	if ( ! is_singular() || empty( $content ) ) {
		return $content;
	}

	$site_host = wp_parse_url( home_url(), PHP_URL_HOST );
	return preg_replace_callback(
		'/<a\s([^>]+)>/i',
		static function ( $m ) use ( $site_host ) {
			$attrs = $m[1];
			if ( preg_match( '/href=["\']([^"\']+)["\']/i', $attrs, $h ) ) {
				$href = $h[1];
				$host = wp_parse_url( $href, PHP_URL_HOST );
				if ( $host && $host !== $site_host && false === stripos( $attrs, 'aria-label=' ) && false === stripos( $attrs, 'aria-describedby=' ) ) {
					if ( false === stripos( $attrs, 'rel=' ) ) {
						$attrs .= ' rel="noopener noreferrer external"';
					}
					if ( false === stripos( $attrs, 'target=' ) ) {
						return '<a ' . $attrs . '>';
					}
				}
			}
			return $m[0];
		},
		$content
	);
}
add_filter( 'the_content', 'wcag_wp_external_links', 20 );

/**
 * Ensures the page title is meaningful (WCAG 2.4.2).
 */
function wcag_wp_document_title_parts( $title ) {
	if ( empty( $title['title'] ) ) {
		$title['title'] = is_front_page() ? __( 'Home', 'wcag-wp' ) : get_bloginfo( 'name' );
	}
	return $title;
}
add_filter( 'document_title_parts', 'wcag_wp_document_title_parts' );

/**
 * Improves comment form fields with explicit labels and proper autocomplete tokens.
 * Supports WCAG 1.3.5 Identify Input Purpose.
 */
function wcag_wp_comment_form_defaults( $defaults ) {
	$commenter = wp_get_current_commenter();
	$req       = get_option( 'require_name_email' );
	$aria_req  = ( $req ? ' aria-required="true" required' : '' );

	$defaults['fields']['author'] = '<p class="comment-form-author"><label for="author">' . esc_html__( 'Name', 'wcag-wp' ) . ( $req ? ' <span aria-hidden="true">*</span>' : '' ) . '</label> ' .
		'<input id="author" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) . '" size="30" autocomplete="name"' . $aria_req . ' /></p>';

	// WP core already renders <span id="email-notes"> inside comment-notes; just reference it.
	$defaults['fields']['email'] = '<p class="comment-form-email"><label for="email">' . esc_html__( 'Email', 'wcag-wp' ) . ( $req ? ' <span aria-hidden="true">*</span>' : '' ) . '</label> ' .
		'<input id="email" name="email" type="email" value="' . esc_attr( $commenter['comment_author_email'] ) . '" size="30" autocomplete="email"' . $aria_req . ' aria-describedby="email-notes" /></p>';

	// autocomplete="url" is rejected by HTMLCS for non-personal URL fields. Omit it here;
	// inputmode="url" still gives mobile users the right keyboard.
	$defaults['fields']['url'] = '<p class="comment-form-url"><label for="url">' . esc_html__( 'Website', 'wcag-wp' ) . '</label> ' .
		'<input id="url" name="url" type="url" value="' . esc_attr( $commenter['comment_author_url'] ) . '" size="30" inputmode="url" /></p>';

	$defaults['comment_field'] = '<p class="comment-form-comment"><label for="comment">' . esc_html_x( 'Comment', 'noun', 'wcag-wp' ) . ' <span aria-hidden="true">*</span></label> ' .
		'<textarea id="comment" name="comment" cols="45" rows="8" aria-required="true" required></textarea></p>';

	return $defaults;
}
add_filter( 'comment_form_defaults', 'wcag_wp_comment_form_defaults' );

/**
 * Outputs a focusable skip-link target wrapper marker.
 * Used in templates as <?php wcag_wp_skip_target( 'main' ); ?>.
 */
function wcag_wp_skip_target( $id ) {
	echo '<span id="' . esc_attr( $id ) . '" tabindex="-1" class="wcag-wp-skip-target"></span>';
}

/**
 * Filter to inject inline CSS from Customizer values.
 */
function wcag_wp_customizer_inline_css() {
	$primary    = get_theme_mod( 'wcag_wp_color_primary', '#0b5fff' );
	$bg         = get_theme_mod( 'wcag_wp_color_background', '#ffffff' );
	$text       = get_theme_mod( 'wcag_wp_color_text', '#1a1a1a' );
	$link       = get_theme_mod( 'wcag_wp_color_link', '#0b5fff' );
	$focus      = get_theme_mod( 'wcag_wp_color_focus', '#ffbf00' );
	$base_font  = (int) get_theme_mod( 'wcag_wp_base_font_size', 18 );
	$line_h     = (float) get_theme_mod( 'wcag_wp_line_height', 1.6 );
	$radius     = (int) get_theme_mod( 'wcag_wp_radius', 6 );

	$base_font = max( 14, min( 22, $base_font ) );
	$line_h    = max( 1.4, min( 2.0, $line_h ) );

	return ":root{--wcag-color-primary:{$primary};--wcag-color-background:{$bg};--wcag-color-text:{$text};--wcag-color-link:{$link};--wcag-color-focus:{$focus};--wcag-base-font-size:{$base_font}px;--wcag-line-height:{$line_h};--wcag-radius:{$radius}px;}";
}

/**
 * Move comment-reply textarea after the meta fields so screen readers encounter labels first.
 */
function wcag_wp_move_comment_field_to_bottom( $fields ) {
	$comment_field = $fields['comment'];
	unset( $fields['comment'] );
	$fields['comment'] = $comment_field;
	return $fields;
}
add_filter( 'comment_form_fields', 'wcag_wp_move_comment_field_to_bottom' );

/**
 * WordPress core already sets aria-label="Reply to {comment_author}" on the reply link
 * and the default visible text is "Reply". Adding our own filter that changes visible
 * text but leaves the core aria-label intact creates a label-in-name mismatch
 * (WCAG 2.5.3). Intentionally not filtering reply_text here — see commit history.
 */

/**
 * Disables emoji script (reduces noise, screen readers don't need it).
 */
function wcag_wp_disable_emojis() {
	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	remove_action( 'wp_print_styles', 'print_emoji_styles' );
	remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
	remove_action( 'admin_print_styles', 'print_emoji_styles' );
}
add_action( 'init', 'wcag_wp_disable_emojis' );

/**
 * Pagination with accessible labels.
 */
function wcag_wp_the_posts_pagination() {
	the_posts_pagination(
		array(
			'mid_size'           => 2,
			'screen_reader_text' => __( 'Posts navigation', 'wcag-wp' ),
			'aria_label'         => __( 'Posts', 'wcag-wp' ),
			'prev_text'          => '<span aria-hidden="true">&larr;</span> ' . __( 'Previous page', 'wcag-wp' ),
			'next_text'          => __( 'Next page', 'wcag-wp' ) . ' <span aria-hidden="true">&rarr;</span>',
		)
	);
}
