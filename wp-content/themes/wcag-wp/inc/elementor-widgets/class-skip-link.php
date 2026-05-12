<?php
/**
 * Skip-link widget (in case the user builds the page with Elementor Theme Builder
 * and wants the skip link rendered inside an Elementor template).
 *
 * @package WCAG_WP
 */

namespace WCAG_WP\Widgets;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Skip_Link extends Base {

	public function get_name() {
		return 'wcag-skip-link';
	}

	public function get_title() {
		return esc_html__( 'WCAG Skip link', 'wcag-wp' );
	}

	public function get_icon() {
		return 'eicon-anchor';
	}

	protected function register_controls() {
		$this->start_controls_section(
			'section_skip',
			array( 'label' => esc_html__( 'Skip link', 'wcag-wp' ) )
		);

		$this->add_control(
			'target',
			array(
				'label'   => esc_html__( 'Target ID (without #)', 'wcag-wp' ),
				'type'    => \Elementor\Controls_Manager::TEXT,
				'default' => 'main',
			)
		);
		$this->add_control(
			'text',
			array(
				'label'   => esc_html__( 'Link text', 'wcag-wp' ),
				'type'    => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( 'Skip to main content', 'wcag-wp' ),
			)
		);

		$this->end_controls_section();
	}

	protected function render() {
		$s = $this->get_settings_for_display();
		printf(
			'<a class="skip-link" href="#%1$s">%2$s</a>',
			esc_attr( $s['target'] ),
			esc_html( $s['text'] )
		);
	}
}
