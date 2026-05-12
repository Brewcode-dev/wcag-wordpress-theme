<?php
/**
 * WCAG Customizer panel.
 *
 * Provides settings for colors (with real-time contrast validation),
 * typography (base font / line-height) and the frontend accessibility widget.
 *
 * @package WCAG_WP
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function wcag_wp_customize_register( $wp_customize ) {

	$wp_customize->add_panel(
		'wcag_wp_panel',
		array(
			'title'       => __( 'WCAG accessibility', 'wcag-wp' ),
			'description' => __( 'Configure colors, typography and the frontend accessibility widget. Contrast ratios are validated in real time against WCAG 2.1 AA (4.5:1 for text, 3:1 for large text and UI components).', 'wcag-wp' ),
			'priority'    => 30,
		)
	);

	// --- Colors section --------------------------------------------------
	$wp_customize->add_section(
		'wcag_wp_section_colors',
		array(
			'title'       => __( 'Colors & contrast', 'wcag-wp' ),
			'panel'       => 'wcag_wp_panel',
			'description' => __( 'Set primary palette. Contrast ratios update live as you change values.', 'wcag-wp' ),
		)
	);

	$colors = array(
		'wcag_wp_color_background' => array(
			'label'   => __( 'Background', 'wcag-wp' ),
			'default' => '#ffffff',
		),
		'wcag_wp_color_text' => array(
			'label'   => __( 'Body text', 'wcag-wp' ),
			'default' => '#1a1a1a',
		),
		'wcag_wp_color_link' => array(
			'label'   => __( 'Links', 'wcag-wp' ),
			'default' => '#0b5fff',
		),
		'wcag_wp_color_primary' => array(
			'label'   => __( 'Primary (buttons, accents)', 'wcag-wp' ),
			'default' => '#0b5fff',
		),
		'wcag_wp_color_focus' => array(
			'label'   => __( 'Focus outline', 'wcag-wp' ),
			'default' => '#ffbf00',
		),
	);

	foreach ( $colors as $id => $cfg ) {
		$wp_customize->add_setting(
			$id,
			array(
				'default'           => $cfg['default'],
				'sanitize_callback' => 'sanitize_hex_color',
				'transport'         => 'postMessage',
			)
		);
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				$id,
				array(
					'label'   => $cfg['label'],
					'section' => 'wcag_wp_section_colors',
				)
			)
		);
	}

	// --- Typography section ----------------------------------------------
	$wp_customize->add_section(
		'wcag_wp_section_typography',
		array(
			'title'       => __( 'Typography', 'wcag-wp' ),
			'panel'       => 'wcag_wp_panel',
			'description' => __( 'Base font size and line height. WCAG recommends at least 1.5× line-height and the ability to enlarge to 200%.', 'wcag-wp' ),
		)
	);

	$wp_customize->add_setting(
		'wcag_wp_base_font_size',
		array(
			'default'           => 18,
			'sanitize_callback' => 'absint',
			'transport'         => 'postMessage',
		)
	);
	$wp_customize->add_control(
		'wcag_wp_base_font_size',
		array(
			'label'       => __( 'Base font size (px)', 'wcag-wp' ),
			'section'     => 'wcag_wp_section_typography',
			'type'        => 'number',
			'description' => __( 'Recommended 16–20 px.', 'wcag-wp' ),
			'input_attrs' => array(
				'min'  => 14,
				'max'  => 22,
				'step' => 1,
			),
		)
	);

	$wp_customize->add_setting(
		'wcag_wp_line_height',
		array(
			'default'           => 1.6,
			'sanitize_callback' => static function ( $v ) {
				$v = (float) $v;
				return max( 1.4, min( 2.0, $v ) );
			},
			'transport'         => 'postMessage',
		)
	);
	$wp_customize->add_control(
		'wcag_wp_line_height',
		array(
			'label'       => __( 'Line height', 'wcag-wp' ),
			'section'     => 'wcag_wp_section_typography',
			'type'        => 'number',
			'description' => __( 'Recommended 1.5–1.8.', 'wcag-wp' ),
			'input_attrs' => array(
				'min'  => 1.4,
				'max'  => 2.0,
				'step' => 0.05,
			),
		)
	);

	$wp_customize->add_setting(
		'wcag_wp_radius',
		array(
			'default'           => 6,
			'sanitize_callback' => 'absint',
			'transport'         => 'postMessage',
		)
	);
	$wp_customize->add_control(
		'wcag_wp_radius',
		array(
			'label'       => __( 'UI corner radius (px)', 'wcag-wp' ),
			'section'     => 'wcag_wp_section_typography',
			'type'        => 'number',
			'input_attrs' => array(
				'min'  => 0,
				'max'  => 20,
				'step' => 1,
			),
		)
	);

	// --- Accessibility widget section ------------------------------------
	$wp_customize->add_section(
		'wcag_wp_section_a11y_widget',
		array(
			'title' => __( 'Accessibility widget', 'wcag-wp' ),
			'panel' => 'wcag_wp_panel',
			'description' => __( 'Frontend control that lets users adjust font size, switch to high contrast, etc.', 'wcag-wp' ),
		)
	);

	$wp_customize->add_setting(
		'wcag_wp_a11y_widget_enabled',
		array(
			'default'           => true,
			'sanitize_callback' => 'wp_validate_boolean',
		)
	);
	$wp_customize->add_control(
		'wcag_wp_a11y_widget_enabled',
		array(
			'label'   => __( 'Enable accessibility widget', 'wcag-wp' ),
			'section' => 'wcag_wp_section_a11y_widget',
			'type'    => 'checkbox',
		)
	);

	$wp_customize->add_setting(
		'wcag_wp_a11y_widget_position',
		array(
			'default'           => 'bottom-right',
			'sanitize_callback' => static function ( $v ) {
				return in_array( $v, array( 'bottom-right', 'bottom-left', 'top-right', 'top-left' ), true ) ? $v : 'bottom-right';
			},
		)
	);
	$wp_customize->add_control(
		'wcag_wp_a11y_widget_position',
		array(
			'label'   => __( 'Widget position', 'wcag-wp' ),
			'section' => 'wcag_wp_section_a11y_widget',
			'type'    => 'select',
			'choices' => array(
				'bottom-right' => __( 'Bottom right', 'wcag-wp' ),
				'bottom-left'  => __( 'Bottom left', 'wcag-wp' ),
				'top-right'    => __( 'Top right', 'wcag-wp' ),
				'top-left'     => __( 'Top left', 'wcag-wp' ),
			),
		)
	);

	// --- Skip link section -----------------------------------------------
	$wp_customize->add_section(
		'wcag_wp_section_skip',
		array(
			'title' => __( 'Skip link', 'wcag-wp' ),
			'panel' => 'wcag_wp_panel',
		)
	);
	$wp_customize->add_setting(
		'wcag_wp_skip_link_text',
		array(
			'default'           => __( 'Skip to main content', 'wcag-wp' ),
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		)
	);
	$wp_customize->add_control(
		'wcag_wp_skip_link_text',
		array(
			'label'   => __( 'Skip link text', 'wcag-wp' ),
			'section' => 'wcag_wp_section_skip',
			'type'    => 'text',
		)
	);

	// --- Selective refresh for the skip link -----------------------------
	if ( isset( $wp_customize->selective_refresh ) ) {
		$wp_customize->selective_refresh->add_partial(
			'wcag_wp_skip_link_text',
			array(
				'selector'        => '.skip-link',
				'render_callback' => static function () {
					return esc_html( get_theme_mod( 'wcag_wp_skip_link_text', __( 'Skip to main content', 'wcag-wp' ) ) );
				},
			)
		);
	}
}
add_action( 'customize_register', 'wcag_wp_customize_register' );

/**
 * Sanitize boolean helper for older WP.
 */
if ( ! function_exists( 'wp_validate_boolean' ) ) {
	function wp_validate_boolean( $v ) {
		return (bool) $v;
	}
}
