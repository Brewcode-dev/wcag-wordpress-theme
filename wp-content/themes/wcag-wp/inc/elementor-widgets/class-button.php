<?php
/**
 * Accessible button widget.
 *
 * Differences vs. Elementor default button:
 *   - Always a real <a> or <button> element (never a div).
 *   - aria-label fallback when icon-only.
 *   - Required visible focus indicator using --wcag-color-focus.
 *   - Minimum target size 44×44 CSS px (WCAG 2.5.5 AAA, supports AA Reflow).
 *
 * @package WCAG_WP
 */

namespace WCAG_WP\Widgets;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Button extends Base {

	public function get_name() {
		return 'wcag-button';
	}

	public function get_title() {
		return esc_html__( 'WCAG Button', 'wcag-wp' );
	}

	public function get_icon() {
		return 'eicon-button';
	}

	protected function register_controls() {
		$this->start_controls_section(
			'section_content',
			array( 'label' => esc_html__( 'Button', 'wcag-wp' ) )
		);

		$this->add_control(
			'text',
			array(
				'label'       => esc_html__( 'Visible label', 'wcag-wp' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'default'     => esc_html__( 'Read more', 'wcag-wp' ),
				'placeholder' => esc_html__( 'Click me', 'wcag-wp' ),
				'dynamic'     => array( 'active' => true ),
			)
		);

		$this->add_control(
			'aria_label',
			array(
				'label'       => esc_html__( 'Accessible name (aria-label)', 'wcag-wp' ),
				'description' => esc_html__( 'Required only when the visible text is not descriptive on its own (e.g. icon-only buttons). Leave empty otherwise.', 'wcag-wp' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'dynamic'     => array( 'active' => true ),
			)
		);

		$this->add_control(
			'link',
			array(
				'label'       => esc_html__( 'Link', 'wcag-wp' ),
				'type'        => \Elementor\Controls_Manager::URL,
				'placeholder' => 'https://example.com',
				'default'     => array(
					'url'         => '',
					'is_external' => false,
					'nofollow'    => false,
				),
				'description' => esc_html__( 'When empty the widget renders a <button>. With URL → <a>.', 'wcag-wp' ),
			)
		);

		$this->add_control(
			'button_type',
			array(
				'label'   => esc_html__( 'Visual style', 'wcag-wp' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'default' => 'primary',
				'options' => array(
					'primary'   => esc_html__( 'Primary', 'wcag-wp' ),
					'secondary' => esc_html__( 'Secondary', 'wcag-wp' ),
					'ghost'     => esc_html__( 'Ghost', 'wcag-wp' ),
				),
			)
		);

		$this->add_control(
			'size',
			array(
				'label'   => esc_html__( 'Size', 'wcag-wp' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'default' => 'md',
				'options' => array(
					'sm' => esc_html__( 'Small (44px min height — WCAG)', 'wcag-wp' ),
					'md' => esc_html__( 'Medium', 'wcag-wp' ),
					'lg' => esc_html__( 'Large', 'wcag-wp' ),
				),
			)
		);

		$this->add_control(
			'align',
			array(
				'label'   => esc_html__( 'Alignment', 'wcag-wp' ),
				'type'    => \Elementor\Controls_Manager::CHOOSE,
				'options' => array(
					'left'   => array(
						'title' => esc_html__( 'Left', 'wcag-wp' ),
						'icon'  => 'eicon-text-align-left',
					),
					'center' => array(
						'title' => esc_html__( 'Center', 'wcag-wp' ),
						'icon'  => 'eicon-text-align-center',
					),
					'right'  => array(
						'title' => esc_html__( 'Right', 'wcag-wp' ),
						'icon'  => 'eicon-text-align-right',
					),
				),
				'default' => 'left',
				'prefix_class' => 'wcag-btn-align-',
			)
		);

		$this->end_controls_section();
	}

	protected function render() {
		$s = $this->get_settings_for_display();

		$classes = array(
			'wcag-btn',
			'wcag-btn--' . $s['button_type'],
			'wcag-btn--' . $s['size'],
		);

		$attrs = 'class="' . esc_attr( implode( ' ', $classes ) ) . '"';
		if ( ! empty( $s['aria_label'] ) ) {
			$attrs .= ' aria-label="' . esc_attr( $s['aria_label'] ) . '"';
		}

		$text = $s['text'] !== '' ? $s['text'] : '';

		if ( ! empty( $s['link']['url'] ) ) {
			$this->add_link_attributes( 'link', $s['link'] );
			$link_attrs = $this->get_render_attribute_string( 'link' );
			echo '<a ' . $link_attrs . ' ' . $attrs . '>'; // phpcs:ignore WordPress.Security.EscapeOutput
			echo esc_html( $text );
			if ( ! empty( $s['link']['is_external'] ) ) {
				echo '<span class="screen-reader-text"> ' . esc_html__( '(opens in a new tab)', 'wcag-wp' ) . '</span>';
			}
			echo '</a>';
		} else {
			echo '<button type="button" ' . $attrs . '>' . esc_html( $text ) . '</button>'; // phpcs:ignore WordPress.Security.EscapeOutput
		}
	}
}
