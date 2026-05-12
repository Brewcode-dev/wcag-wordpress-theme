<?php
/**
 * Theme setup: supports, menus, image sizes.
 *
 * @package WCAG_WP
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function wcag_wp_setup() {
	load_theme_textdomain( 'wcag-wp', WCAG_WP_DIR . '/languages' );

	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'customize-selective-refresh-widgets' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'align-wide' );

	add_theme_support(
		'html5',
		array(
			'comment-list',
			'comment-form',
			'search-form',
			'gallery',
			'caption',
			'style',
			'script',
			'navigation-widgets',
		)
	);

	add_theme_support(
		'custom-logo',
		array(
			'height'               => 80,
			'width'                => 240,
			'flex-height'          => true,
			'flex-width'           => true,
			'unlink-homepage-logo' => true,
		)
	);

	// Accessibility-ready: explicit landmark structure provided by templates.
	register_nav_menus(
		array(
			'primary' => __( 'Primary menu', 'wcag-wp' ),
			'footer'  => __( 'Footer menu', 'wcag-wp' ),
			'social'  => __( 'Social menu', 'wcag-wp' ),
		)
	);

	add_editor_style( 'assets/css/editor.css' );

	add_image_size( 'wcag-wp-featured', 1280, 720, true );
}
add_action( 'after_setup_theme', 'wcag_wp_setup' );

function wcag_wp_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'wcag_wp_content_width', 1200 );
}
add_action( 'after_setup_theme', 'wcag_wp_content_width', 0 );

function wcag_wp_widgets_init() {
	register_sidebar(
		array(
			'name'          => __( 'Primary sidebar', 'wcag-wp' ),
			'id'            => 'sidebar-1',
			'description'   => __( 'Widgets shown in the primary sidebar.', 'wcag-wp' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s" aria-labelledby="%1$s-title">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 id="%1$s-title" class="widget-title">',
			'after_title'   => '</h2>',
		)
	);

	register_sidebar(
		array(
			'name'          => __( 'Footer widgets', 'wcag-wp' ),
			'id'            => 'sidebar-footer',
			'description'   => __( 'Widgets shown in the footer area.', 'wcag-wp' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s" aria-labelledby="%1$s-title">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 id="%1$s-title" class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
}
add_action( 'widgets_init', 'wcag_wp_widgets_init' );

/**
 * Add lang attribute helpers on the body when the post specifies a different language via custom field.
 * Helps satisfy WCAG 3.1.2 Language of Parts when editorial flow uses a `post_lang` meta field.
 */
function wcag_wp_body_lang_attr( $output ) {
	if ( is_singular() ) {
		$lang = get_post_meta( get_the_ID(), 'post_lang', true );
		if ( $lang ) {
			$output .= ' lang="' . esc_attr( $lang ) . '"';
		}
	}
	return $output;
}
add_filter( 'language_attributes', 'wcag_wp_body_lang_attr' );

/**
 * Ensure search form uses semantic labelling.
 */
function wcag_wp_search_form( $form ) {
	$unique_id = esc_attr( wp_unique_id( 'search-form-' ) );
	$aria_id   = $unique_id . '-label';

	$form = '<form role="search" method="get" class="search-form" action="' . esc_url( home_url( '/' ) ) . '" aria-labelledby="' . $aria_id . '">';
	$form .= '<label for="' . $unique_id . '" id="' . $aria_id . '" class="screen-reader-text">' . esc_html__( 'Search for:', 'wcag-wp' ) . '</label>';
	$form .= '<input type="search" id="' . $unique_id . '" class="search-field" placeholder="' . esc_attr__( 'Search…', 'wcag-wp' ) . '" value="' . get_search_query() . '" name="s" autocomplete="off" />';
	$form .= '<button type="submit" class="search-submit">' . esc_html__( 'Search', 'wcag-wp' ) . '</button>';
	$form .= '</form>';

	return $form;
}
add_filter( 'get_search_form', 'wcag_wp_search_form' );
