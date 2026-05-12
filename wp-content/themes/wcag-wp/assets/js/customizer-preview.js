/**
 * Customizer live preview.
 */
( function ( $ ) {
	'use strict';

	function setVar( name, value ) {
		document.documentElement.style.setProperty( name, value );
	}

	wp.customize( 'wcag_wp_color_primary',    function ( v ) { v.bind( function ( n ) { setVar( '--wcag-color-primary', n ); } ); } );
	wp.customize( 'wcag_wp_color_background', function ( v ) { v.bind( function ( n ) { setVar( '--wcag-color-background', n ); } ); } );
	wp.customize( 'wcag_wp_color_text',       function ( v ) { v.bind( function ( n ) { setVar( '--wcag-color-text', n ); } ); } );
	wp.customize( 'wcag_wp_color_link',       function ( v ) { v.bind( function ( n ) { setVar( '--wcag-color-link', n ); } ); } );
	wp.customize( 'wcag_wp_color_focus',      function ( v ) { v.bind( function ( n ) { setVar( '--wcag-color-focus', n ); } ); } );
	wp.customize( 'wcag_wp_base_font_size',   function ( v ) { v.bind( function ( n ) { setVar( '--wcag-base-font-size', parseInt( n, 10 ) + 'px' ); } ); } );
	wp.customize( 'wcag_wp_line_height',      function ( v ) { v.bind( function ( n ) { setVar( '--wcag-line-height', n ); } ); } );
	wp.customize( 'wcag_wp_radius',           function ( v ) { v.bind( function ( n ) { setVar( '--wcag-radius', parseInt( n, 10 ) + 'px' ); } ); } );

	wp.customize( 'wcag_wp_skip_link_text', function ( v ) {
		v.bind( function ( n ) {
			var $sl = $( '.skip-link' );
			if ( $sl.length ) { $sl.text( n ); }
		} );
	} );
}( jQuery ) );
