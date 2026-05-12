/**
 * Customizer controls (admin pane).
 *
 * - Real-time contrast ratio calculation for foreground/background pairs.
 * - Visual pass/fail badges next to color controls per WCAG 2.1 AA.
 */

( function ( $ ) {
	'use strict';

	function hexToRgb( hex ) {
		if ( ! hex ) { return null; }
		hex = String( hex ).replace( '#', '' );
		if ( hex.length === 3 ) {
			hex = hex.split( '' ).map( function ( c ) { return c + c; } ).join( '' );
		}
		if ( hex.length !== 6 ) { return null; }
		return {
			r: parseInt( hex.substr( 0, 2 ), 16 ),
			g: parseInt( hex.substr( 2, 2 ), 16 ),
			b: parseInt( hex.substr( 4, 2 ), 16 )
		};
	}

	function luminance( rgb ) {
		var a = [ rgb.r, rgb.g, rgb.b ].map( function ( v ) {
			v /= 255;
			return v <= 0.03928 ? v / 12.92 : Math.pow( ( v + 0.055 ) / 1.055, 2.4 );
		} );
		return a[0] * 0.2126 + a[1] * 0.7152 + a[2] * 0.0722;
	}

	function contrast( hex1, hex2 ) {
		var rgb1 = hexToRgb( hex1 );
		var rgb2 = hexToRgb( hex2 );
		if ( ! rgb1 || ! rgb2 ) { return 0; }
		var l1 = luminance( rgb1 );
		var l2 = luminance( rgb2 );
		var bright = Math.max( l1, l2 );
		var dark   = Math.min( l1, l2 );
		return ( bright + 0.05 ) / ( dark + 0.05 );
	}

	function badge( ratio ) {
		var passAA      = ratio >= 4.5;
		var passAA_LG   = ratio >= 3.0;
		var passAAA     = ratio >= 7.0;
		var classes     = 'wcag-contrast-badge';
		var label;
		if ( passAAA )     { classes += ' is-aaa'; label = 'AAA'; }
		else if ( passAA ) { classes += ' is-aa';  label = 'AA';  }
		else if ( passAA_LG ) { classes += ' is-aa-large'; label = 'AA large only'; }
		else               { classes += ' is-fail'; label = 'FAIL'; }
		return '<span class="' + classes + '">' + ratio.toFixed( 2 ) + ':1 <strong>' + label + '</strong></span>';
	}

	function updateBadges() {
		var bg      = wp.customize( 'wcag_wp_color_background' )().toString();
		var text    = wp.customize( 'wcag_wp_color_text' )().toString();
		var link    = wp.customize( 'wcag_wp_color_link' )().toString();
		var primary = wp.customize( 'wcag_wp_color_primary' )().toString();

		render( 'wcag_wp_color_text', 'Text vs background: ', contrast( text, bg ) );
		render( 'wcag_wp_color_link', 'Link vs background: ', contrast( link, bg ) );
		render( 'wcag_wp_color_primary', 'Primary button (white text on color): ', contrast( '#ffffff', primary ) );
	}

	function render( settingId, prefix, ratio ) {
		var $el = $( '#customize-control-' + settingId );
		$el.find( '.wcag-contrast-info' ).remove();
		$el.append(
			$( '<p class="wcag-contrast-info" style="margin-top:.5rem;font-size:12px"></p>' )
				.html( prefix + badge( ratio ) )
		);
	}

	wp.customize.bind( 'ready', function () {
		[
			'wcag_wp_color_background',
			'wcag_wp_color_text',
			'wcag_wp_color_link',
			'wcag_wp_color_primary'
		].forEach( function ( id ) {
			wp.customize( id, function ( v ) {
				v.bind( updateBadges );
			} );
		} );
		updateBadges();
	} );

	// Inject minimal styles for the badges.
	$( function () {
		$( '<style>'
			+ '.wcag-contrast-badge{display:inline-block;padding:.1rem .4rem;border-radius:4px;font-weight:700;margin-left:.25rem}'
			+ '.wcag-contrast-badge.is-aaa{background:#006100;color:#fff}'
			+ '.wcag-contrast-badge.is-aa{background:#0b5fff;color:#fff}'
			+ '.wcag-contrast-badge.is-aa-large{background:#ffbf00;color:#000}'
			+ '.wcag-contrast-badge.is-fail{background:#b40000;color:#fff}'
			+ '</style>'
		).appendTo( 'head' );
	} );

}( jQuery ) );
