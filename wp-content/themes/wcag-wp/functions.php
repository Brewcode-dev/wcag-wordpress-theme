<?php
/**
 * WCAG WP theme functions.
 *
 * @package WCAG_WP
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'WCAG_WP_VERSION', '1.0.0' );
define( 'WCAG_WP_DIR', get_template_directory() );
define( 'WCAG_WP_URI', get_template_directory_uri() );

require_once WCAG_WP_DIR . '/inc/setup.php';
require_once WCAG_WP_DIR . '/inc/enqueue.php';
require_once WCAG_WP_DIR . '/inc/template-tags.php';
require_once WCAG_WP_DIR . '/inc/template-functions.php';
require_once WCAG_WP_DIR . '/inc/nav-walker.php';
require_once WCAG_WP_DIR . '/inc/customizer.php';
require_once WCAG_WP_DIR . '/inc/accessibility-widget.php';
require_once WCAG_WP_DIR . '/inc/elementor-compat.php';
