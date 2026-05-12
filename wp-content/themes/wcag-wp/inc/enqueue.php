<?php
/**
 * Enqueues styles and scripts.
 *
 * @package WCAG_WP
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function wcag_wp_enqueue_assets() {
	// Main stylesheet (registered for theme metadata only).
	wp_enqueue_style(
		'wcag-wp-style',
		get_stylesheet_uri(),
		array(),
		WCAG_WP_VERSION
	);

	wp_enqueue_style(
		'wcag-wp-main',
		WCAG_WP_URI . '/assets/css/main.css',
		array( 'wcag-wp-style' ),
		WCAG_WP_VERSION
	);

	wp_enqueue_style(
		'wcag-wp-accessibility',
		WCAG_WP_URI . '/assets/css/accessibility.css',
		array( 'wcag-wp-main' ),
		WCAG_WP_VERSION
	);

	// Inline CSS variables based on Customizer values.
	$inline = wcag_wp_customizer_inline_css();
	if ( $inline ) {
		wp_add_inline_style( 'wcag-wp-main', $inline );
	}

	wp_enqueue_script(
		'wcag-wp-skip-link-focus-fix',
		WCAG_WP_URI . '/assets/js/skip-link-focus-fix.js',
		array(),
		WCAG_WP_VERSION,
		array(
			'in_footer' => false,
			'strategy'  => 'defer',
		)
	);

	wp_enqueue_script(
		'wcag-wp-navigation',
		WCAG_WP_URI . '/assets/js/navigation.js',
		array(),
		WCAG_WP_VERSION,
		array(
			'in_footer' => true,
			'strategy'  => 'defer',
		)
	);

	wp_localize_script(
		'wcag-wp-navigation',
		'wcagWpNav',
		array(
			'open'  => __( 'Open submenu', 'wcag-wp' ),
			'close' => __( 'Close submenu', 'wcag-wp' ),
			'menu'  => __( 'Menu', 'wcag-wp' ),
		)
	);

	wp_enqueue_script(
		'wcag-wp-accessibility-widget',
		WCAG_WP_URI . '/assets/js/accessibility-widget.js',
		array(),
		WCAG_WP_VERSION,
		array(
			'in_footer' => true,
			'strategy'  => 'defer',
		)
	);

	wp_localize_script(
		'wcag-wp-accessibility-widget',
		'wcagWpA11y',
		array(
			'labels' => array(
				'toggle'        => __( 'Accessibility settings', 'wcag-wp' ),
				'panelTitle'    => __( 'Accessibility settings', 'wcag-wp' ),
				'close'         => __( 'Close accessibility panel', 'wcag-wp' ),
				'fontIncrease'  => __( 'Increase text size', 'wcag-wp' ),
				'fontDecrease'  => __( 'Decrease text size', 'wcag-wp' ),
				'fontReset'     => __( 'Reset text size', 'wcag-wp' ),
				'contrast'      => __( 'High contrast', 'wcag-wp' ),
				'underlineLinks'=> __( 'Underline links', 'wcag-wp' ),
				'readableFont'  => __( 'Readable font', 'wcag-wp' ),
				'pauseMotion'   => __( 'Pause animations', 'wcag-wp' ),
				'biggerCursor'  => __( 'Larger cursor', 'wcag-wp' ),
				'resetAll'      => __( 'Reset all settings', 'wcag-wp' ),
				'on'            => __( 'on', 'wcag-wp' ),
				'off'           => __( 'off', 'wcag-wp' ),
			),
		)
	);

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'wcag_wp_enqueue_assets' );

function wcag_wp_admin_enqueue() {
	wp_enqueue_style(
		'wcag-wp-admin',
		WCAG_WP_URI . '/assets/css/admin.css',
		array(),
		WCAG_WP_VERSION
	);
}
add_action( 'admin_enqueue_scripts', 'wcag_wp_admin_enqueue' );

function wcag_wp_customizer_preview_js() {
	wp_enqueue_script(
		'wcag-wp-customizer-preview',
		WCAG_WP_URI . '/assets/js/customizer-preview.js',
		array( 'customize-preview' ),
		WCAG_WP_VERSION,
		true
	);
}
add_action( 'customize_preview_init', 'wcag_wp_customizer_preview_js' );

function wcag_wp_customizer_controls_js() {
	wp_enqueue_script(
		'wcag-wp-customizer-controls',
		WCAG_WP_URI . '/assets/js/customizer-controls.js',
		array( 'customize-controls', 'jquery', 'wp-color-picker' ),
		WCAG_WP_VERSION,
		true
	);
	wp_enqueue_style( 'wp-color-picker' );
}
add_action( 'customize_controls_enqueue_scripts', 'wcag_wp_customizer_controls_js' );
