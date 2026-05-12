<?php
/**
 * Base class for WCAG WP Elementor widgets.
 *
 * @package WCAG_WP
 */

namespace WCAG_WP\Widgets;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( '\\Elementor\\Widget_Base' ) ) {
	return;
}

abstract class Base extends \Elementor\Widget_Base {

	public function get_categories() {
		return array( 'wcag-wp' );
	}

	public function get_keywords() {
		return array( 'wcag', 'accessible', 'a11y' );
	}

	/**
	 * Hardcode escaped heading rendering with a configurable tag.
	 */
	protected function render_heading( $tag, $text, $class = '' ) {
		$allowed = array( 'h1', 'h2', 'h3', 'h4', 'h5', 'h6' );
		$tag     = in_array( $tag, $allowed, true ) ? $tag : 'h2';
		printf(
			'<%1$s class="%2$s">%3$s</%1$s>',
			esc_attr( $tag ),
			esc_attr( $class ),
			esc_html( $text )
		);
	}
}
