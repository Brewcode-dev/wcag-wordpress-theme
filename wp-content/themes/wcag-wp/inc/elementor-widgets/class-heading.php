<?php
/**
 * Accessible heading widget.
 *
 * Enforces an explicit semantic level (h1–h6) separate from visual size,
 * so editors can't accidentally produce an h2 styled like an h5 (or vice versa).
 *
 * @package WCAG_WP
 */

namespace WCAG_WP\Widgets;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Heading extends Base {

	public function get_name() {
		return 'wcag-heading';
	}

	public function get_title() {
		return esc_html__( 'WCAG Heading', 'wcag-wp' );
	}

	public function get_icon() {
		return 'eicon-t-letter';
	}

	protected function register_controls() {
		$this->start_controls_section(
			'section',
			array( 'label' => esc_html__( 'Heading', 'wcag-wp' ) )
		);

		$this->add_control(
			'text',
			array(
				'label'   => esc_html__( 'Text', 'wcag-wp' ),
				'type'    => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( 'Page title', 'wcag-wp' ),
				'dynamic' => array( 'active' => true ),
			)
		);

		$this->add_control(
			'tag',
			array(
				'label'       => esc_html__( 'Semantic level', 'wcag-wp' ),
				'type'        => \Elementor\Controls_Manager::SELECT,
				'default'     => 'h2',
				'options'     => array(
					'h1' => 'H1',
					'h2' => 'H2',
					'h3' => 'H3',
					'h4' => 'H4',
					'h5' => 'H5',
					'h6' => 'H6',
				),
				'description' => esc_html__( 'Use exactly one H1 per page.', 'wcag-wp' ),
			)
		);

		$this->add_control(
			'size',
			array(
				'label'   => esc_html__( 'Visual size', 'wcag-wp' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'default' => 'auto',
				'options' => array(
					'auto'   => esc_html__( 'Match semantic level', 'wcag-wp' ),
					'h1'     => esc_html__( 'Like H1', 'wcag-wp' ),
					'h2'     => esc_html__( 'Like H2', 'wcag-wp' ),
					'h3'     => esc_html__( 'Like H3', 'wcag-wp' ),
					'h4'     => esc_html__( 'Like H4', 'wcag-wp' ),
					'h5'     => esc_html__( 'Like H5', 'wcag-wp' ),
				),
			)
		);

		$this->end_controls_section();
	}

	protected function render() {
		$s    = $this->get_settings_for_display();
		$tag  = in_array( $s['tag'], array( 'h1', 'h2', 'h3', 'h4', 'h5', 'h6' ), true ) ? $s['tag'] : 'h2';
		$size = 'auto' === $s['size'] ? $tag : $s['size'];
		printf(
			'<%1$s class="wcag-heading wcag-heading--%2$s">%3$s</%1$s>',
			esc_attr( $tag ),
			esc_attr( $size ),
			esc_html( $s['text'] )
		);
	}
}
