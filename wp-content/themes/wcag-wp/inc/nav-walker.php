<?php
/**
 * Accessible nav walker.
 *
 * Renders submenus with explicit aria-controls / aria-expanded toggle buttons
 * and stable IDs so the toggle's controls relationship is valid.
 *
 * @package WCAG_WP
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WCAG_WP_Nav_Walker extends Walker_Nav_Menu {

	private $submenu_id_stack = array();

	public function start_lvl( &$output, $depth = 0, $args = null ) {
		$indent = str_repeat( "\t", $depth );
		$id     = end( $this->submenu_id_stack );
		if ( ! $id ) {
			$id = 'submenu-' . wp_unique_id();
		}
		$output .= "\n{$indent}<ul class=\"sub-menu\" id=\"" . esc_attr( $id ) . "\">\n";
	}

	public function end_lvl( &$output, $depth = 0, $args = null ) {
		$indent = str_repeat( "\t", $depth );
		$output .= "{$indent}</ul>\n";
	}

	public function display_element( $element, &$children_elements, $max_depth, $depth, $args, &$output ) {
		$has_children = ! empty( $children_elements[ $element->ID ] );
		if ( $has_children ) {
			$this->submenu_id_stack[] = 'submenu-of-' . $element->ID;
		}
		parent::display_element( $element, $children_elements, $max_depth, $depth, $args, $output );
		if ( $has_children ) {
			array_pop( $this->submenu_id_stack );
		}
	}

	public function start_el( &$output, $item, $depth = 0, $args = null, $id = 0 ) {
		$indent  = ( $depth ) ? str_repeat( "\t", $depth ) : '';
		$classes = empty( $item->classes ) ? array() : (array) $item->classes;
		$classes[] = 'menu-item-' . $item->ID;

		$has_children = in_array( 'menu-item-has-children', $classes, true );
		if ( $has_children ) {
			$classes[] = 'has-submenu';
		}

		$class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args, $depth ) );
		$class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';

		$id_attr = apply_filters( 'nav_menu_item_id', 'menu-item-' . $item->ID, $item, $args, $depth );
		$id_attr = $id_attr ? ' id="' . esc_attr( $id_attr ) . '"' : '';

		$output .= $indent . '<li' . $id_attr . $class_names . '>';

		$atts                  = array();
		$atts['title']         = ! empty( $item->attr_title ) ? $item->attr_title : '';
		$atts['target']        = ! empty( $item->target ) ? $item->target : '';
		$atts['rel']           = '_blank' === $atts['target']
			? trim( ( ! empty( $item->xfn ) ? $item->xfn : '' ) . ' noopener noreferrer' )
			: $item->xfn;
		$atts['href']          = ! empty( $item->url ) ? $item->url : '';
		$atts['aria-current']  = in_array( 'current-menu-item', $classes, true ) ? 'page' : '';
		if ( '_blank' === $atts['target'] ) {
			$atts['aria-describedby'] = 'menu-item-new-window-' . $item->ID;
		}

		$atts = apply_filters( 'nav_menu_link_attributes', $atts, $item, $args, $depth );

		$attributes = '';
		foreach ( $atts as $attr => $value ) {
			if ( '' !== $value && false !== $value ) {
				$safe   = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );
				$attributes .= ' ' . $attr . '="' . $safe . '"';
			}
		}

		$title = apply_filters( 'the_title', $item->title, $item->ID );
		$title = apply_filters( 'nav_menu_item_title', $title, $item, $args, $depth );

		$item_output  = $args->before ?? '';
		$item_output .= '<a' . $attributes . '>';
		$item_output .= ( $args->link_before ?? '' ) . $title . ( $args->link_after ?? '' );
		$item_output .= '</a>';

		if ( '_blank' === $atts['target'] ) {
			$item_output .= '<span id="menu-item-new-window-' . esc_attr( $item->ID ) . '" class="screen-reader-text"> ' . esc_html__( '(opens in a new window)', 'wcag-wp' ) . '</span>';
		}

		if ( $has_children && 0 === $depth ) {
			$submenu_id = 'submenu-of-' . $item->ID;
			$item_output .= '<button type="button" class="submenu-toggle" aria-expanded="false" aria-controls="' . esc_attr( $submenu_id ) . '">';
			$item_output .= '<span class="screen-reader-text">' . sprintf(
				/* translators: %s: parent menu item title. */
				esc_html__( 'Show submenu for %s', 'wcag-wp' ),
				esc_html( wp_strip_all_tags( $title ) )
			) . '</span>';
			$item_output .= '<svg class="submenu-arrow" aria-hidden="true" focusable="false" viewBox="0 0 20 20"><path d="M5 7l5 6 5-6z" fill="currentColor"/></svg>';
			$item_output .= '</button>';
		}

		$item_output .= $args->after ?? '';

		$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
	}
}
