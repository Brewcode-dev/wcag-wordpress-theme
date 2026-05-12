<?php
/**
 * Elementor compatibility.
 *
 * - Declares theme/Elementor support
 * - Registers a dedicated widget category "WCAG"
 * - Registers custom accessible widgets (button, accordion, tabs, image, form, skip-link)
 * - Hardens default Elementor output for WCAG 2.1 AA (icon-only buttons, headings, etc.)
 *
 * @package WCAG_WP
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function wcag_wp_elementor_is_active() {
	return did_action( 'elementor/loaded' );
}

/**
 * Register a custom Elementor widget category for our WCAG widgets.
 */
function wcag_wp_register_widget_category( $elements_manager ) {
	$elements_manager->add_category(
		'wcag-wp',
		array(
			'title' => esc_html__( 'WCAG (accessible)', 'wcag-wp' ),
			'icon'  => 'fa fa-universal-access',
		)
	);
}
add_action( 'elementor/elements/categories_registered', 'wcag_wp_register_widget_category' );

/**
 * Register our custom widgets after Elementor base widget is available.
 */
function wcag_wp_register_elementor_widgets( $widgets_manager ) {
	$dir = WCAG_WP_DIR . '/inc/elementor-widgets/';
	require_once $dir . 'class-wcag-elementor-base.php';
	require_once $dir . 'class-button.php';
	require_once $dir . 'class-accordion.php';
	require_once $dir . 'class-tabs.php';
	require_once $dir . 'class-image.php';
	require_once $dir . 'class-skip-link.php';
	require_once $dir . 'class-heading.php';

	$widgets_manager->register( new \WCAG_WP\Widgets\Button() );
	$widgets_manager->register( new \WCAG_WP\Widgets\Accordion() );
	$widgets_manager->register( new \WCAG_WP\Widgets\Tabs() );
	$widgets_manager->register( new \WCAG_WP\Widgets\Image_Widget() );
	$widgets_manager->register( new \WCAG_WP\Widgets\Skip_Link() );
	$widgets_manager->register( new \WCAG_WP\Widgets\Heading() );
}
add_action( 'elementor/widgets/register', 'wcag_wp_register_elementor_widgets' );

/**
 * Make sure Elementor's frontend stylesheet is loaded after ours
 * so accessibility overrides win for default widgets.
 */
function wcag_wp_elementor_overrides_css() {
	if ( ! wcag_wp_elementor_is_active() ) {
		return;
	}
	wp_enqueue_style(
		'wcag-wp-elementor-overrides',
		WCAG_WP_URI . '/assets/css/elementor-overrides.css',
		array( 'wcag-wp-accessibility' ),
		WCAG_WP_VERSION
	);
}
add_action( 'wp_enqueue_scripts', 'wcag_wp_elementor_overrides_css', 99 );

/**
 * Allow Elementor canvas / theme builder for full-width pages while keeping our skip-link + a11y widget.
 */
function wcag_wp_elementor_locations( $manager ) {
	if ( ! method_exists( $manager, 'register_all_core_location' ) ) {
		return;
	}
	$manager->register_all_core_location();
}
add_action( 'elementor/theme/register_locations', 'wcag_wp_elementor_locations' );

add_action(
	'after_setup_theme',
	static function () {
		add_theme_support( 'elementor' );
	}
);
