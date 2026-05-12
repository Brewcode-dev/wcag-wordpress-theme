<?php
/**
 * Accessible image widget.
 *
 * Forces an explicit decision: meaningful alt OR explicit "decorative" flag
 * (which renders alt="" + role="presentation"). Prevents missing/auto-generated alts.
 *
 * @package WCAG_WP
 */

namespace WCAG_WP\Widgets;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Image_Widget extends Base {

	public function get_name() {
		return 'wcag-image';
	}

	public function get_title() {
		return esc_html__( 'WCAG Image', 'wcag-wp' );
	}

	public function get_icon() {
		return 'eicon-image';
	}

	protected function register_controls() {
		$this->start_controls_section(
			'section_image',
			array( 'label' => esc_html__( 'Image', 'wcag-wp' ) )
		);

		$this->add_control(
			'image',
			array(
				'label'   => esc_html__( 'Choose image', 'wcag-wp' ),
				'type'    => \Elementor\Controls_Manager::MEDIA,
				'dynamic' => array( 'active' => true ),
				'default' => array(
					'url' => \Elementor\Utils::get_placeholder_image_src(),
				),
			)
		);

		$this->add_control(
			'is_decorative',
			array(
				'label'       => esc_html__( 'This image is decorative', 'wcag-wp' ),
				'type'        => \Elementor\Controls_Manager::SWITCHER,
				'default'     => '',
				'description' => esc_html__( 'Enable only when the image conveys no information. Renders alt="" + role="presentation" (WCAG 1.1.1).', 'wcag-wp' ),
			)
		);

		$this->add_control(
			'alt',
			array(
				'label'       => esc_html__( 'Alternative text (required when not decorative)', 'wcag-wp' ),
				'type'        => \Elementor\Controls_Manager::TEXTAREA,
				'placeholder' => esc_html__( 'Describe the image and its purpose in 1 sentence.', 'wcag-wp' ),
				'condition'   => array( 'is_decorative!' => 'yes' ),
				'dynamic'     => array( 'active' => true ),
			)
		);

		$this->add_control(
			'caption',
			array(
				'label' => esc_html__( 'Caption', 'wcag-wp' ),
				'type'  => \Elementor\Controls_Manager::TEXT,
				'dynamic' => array( 'active' => true ),
			)
		);

		$this->add_control(
			'link',
			array(
				'label' => esc_html__( 'Link to', 'wcag-wp' ),
				'type'  => \Elementor\Controls_Manager::URL,
			)
		);

		$this->end_controls_section();
	}

	protected function render() {
		$s = $this->get_settings_for_display();

		if ( empty( $s['image']['url'] ) ) {
			return;
		}

		$decorative = 'yes' === $s['is_decorative'];
		$alt        = $decorative ? '' : trim( wp_strip_all_tags( $s['alt'] ) );

		$img_attrs = array(
			'src'      => esc_url( $s['image']['url'] ),
			'alt'      => esc_attr( $alt ),
			'loading'  => 'lazy',
			'decoding' => 'async',
		);
		if ( $decorative ) {
			$img_attrs['role']        = 'presentation';
			$img_attrs['aria-hidden'] = 'true';
		}

		$img_html = '<img';
		foreach ( $img_attrs as $k => $v ) {
			$img_html .= ' ' . $k . '="' . $v . '"';
		}
		$img_html .= ' />';

		$open  = '';
		$close = '';
		if ( ! empty( $s['link']['url'] ) ) {
			$this->add_link_attributes( 'link', $s['link'] );
			$open  = '<a ' . $this->get_render_attribute_string( 'link' ) . '>';
			$close = '</a>';
		}

		if ( ! empty( $s['caption'] ) ) {
			echo '<figure class="wcag-image">';
			echo $open . $img_html . $close; // phpcs:ignore WordPress.Security.EscapeOutput
			echo '<figcaption class="wcag-image__caption">' . esc_html( $s['caption'] ) . '</figcaption>';
			echo '</figure>';
		} else {
			echo '<div class="wcag-image">';
			echo $open . $img_html . $close; // phpcs:ignore WordPress.Security.EscapeOutput
			echo '</div>';
		}

		if ( ! $decorative && '' === $alt && is_user_logged_in() && current_user_can( 'edit_theme_options' ) ) {
			echo '<p class="wcag-image__warning" role="status">' . esc_html__( 'WCAG warning: this image has no alt text and is not marked decorative. Visible only to logged-in editors.', 'wcag-wp' ) . '</p>';
		}
	}
}
