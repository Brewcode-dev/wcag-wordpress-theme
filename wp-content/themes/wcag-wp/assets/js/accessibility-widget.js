/**
 * Accessibility widget controller.
 *
 * Persists user preferences in localStorage and applies them as HTML/body classes.
 * Initial state is applied ASAP on DOMContentLoaded to minimise flash.
 */

( function () {
	'use strict';

	var STORAGE_KEY = 'wcagWpA11yPrefs';

	var DEFAULTS = {
		fontLevel: 0,
		highContrast: false,
		negativeContrast: false,
		grayscale: false,
		underlineLinks: false,
		highlightFocus: false,
		readableFont: false,
		pauseMotion: false,
		biggerCursor: false
	};

	var FONT_LABELS = { '-1': '95%', '0': '100%', '1': '112%', '2': '125%', '3': '150%', '4': '175%', '5': '200%' };

	var state = load();
	var widgetEl = null;
	var fabEl    = null;
	var panelEl  = null;
	var closeEl  = null;
	var backdrop = null;
	var lastFocus = null;

	apply( state );

	if ( document.readyState !== 'loading' ) {
		init();
	} else {
		document.addEventListener( 'DOMContentLoaded', init );
	}

	function init() {
		widgetEl = document.querySelector( '[data-wcag-a11y-widget]' );
		if ( ! widgetEl ) { return; }

		fabEl    = widgetEl.querySelector( '.wcag-a11y__fab' );
		panelEl  = widgetEl.querySelector( '.wcag-a11y__panel' );
		closeEl  = widgetEl.querySelector( '.wcag-a11y__close' );
		backdrop = widgetEl.querySelector( '[data-wcag-backdrop]' );

		syncUi();

		fabEl.addEventListener( 'click', openPanel );
		closeEl.addEventListener( 'click', closePanel );

		// Action buttons (stepper)
		widgetEl.querySelectorAll( '[data-wcag-action]' ).forEach( function ( btn ) {
			btn.addEventListener( 'click', function () {
				var action = btn.getAttribute( 'data-wcag-action' );
				if ( action === 'font-increase' ) {
					state.fontLevel = Math.min( 5, state.fontLevel + 1 );
				} else if ( action === 'font-decrease' ) {
					state.fontLevel = Math.max( -1, state.fontLevel - 1 );
				} else if ( action === 'font-reset' ) {
					state.fontLevel = 0;
				} else if ( action === 'reset-all' ) {
					state = Object.assign( {}, DEFAULTS );
				}
				save();
				apply( state );
				syncUi();
			} );
		} );

		// Card toggles
		widgetEl.querySelectorAll( '[data-wcag-toggle]' ).forEach( function ( input ) {
			input.addEventListener( 'change', function () {
				var key = camelize( input.getAttribute( 'data-wcag-toggle' ) );
				state[ key ] = input.checked;
				if ( key === 'highContrast' && input.checked ) {
					state.negativeContrast = false;
				} else if ( key === 'negativeContrast' && input.checked ) {
					state.highContrast = false;
				}
				save();
				apply( state );
				syncUi();
			} );
		} );

		// Keyboard
		document.addEventListener( 'keydown', function ( e ) {
			if ( panelEl.hidden ) { return; }
			if ( e.key === 'Escape' ) {
				e.preventDefault();
				closePanel();
			} else if ( e.key === 'Tab' ) {
				trapFocus( e );
			}
		} );

		// Click outside (on backdrop) closes
		if ( backdrop ) {
			backdrop.addEventListener( 'click', closePanel );
		}
		document.addEventListener( 'click', function ( e ) {
			if ( panelEl.hidden ) { return; }
			if ( widgetEl.contains( e.target ) ) { return; }
			closePanel();
		} );
	}

	function openPanel() {
		lastFocus = document.activeElement;
		fabEl.setAttribute( 'aria-expanded', 'true' );
		panelEl.hidden = false;
		if ( backdrop ) { backdrop.hidden = false; }
		// Move focus to the panel itself so SR users hear the title.
		setTimeout( function () { panelEl.focus(); }, 0 );
		document.documentElement.style.overflow = 'hidden';
	}

	function closePanel() {
		fabEl.setAttribute( 'aria-expanded', 'false' );
		panelEl.hidden = true;
		if ( backdrop ) { backdrop.hidden = true; }
		document.documentElement.style.overflow = '';
		if ( lastFocus && typeof lastFocus.focus === 'function' ) {
			lastFocus.focus();
		} else {
			fabEl.focus();
		}
	}

	function trapFocus( e ) {
		var focusable = panelEl.querySelectorAll(
			'button, [href], input:not([type="hidden"]), select, textarea, [tabindex]:not([tabindex="-1"])'
		);
		focusable = Array.prototype.filter.call( focusable, function ( el ) {
			return ! el.disabled && el.offsetParent !== null;
		} );
		if ( focusable.length === 0 ) { return; }
		var first = focusable[ 0 ];
		var last  = focusable[ focusable.length - 1 ];

		if ( e.shiftKey && document.activeElement === first ) {
			e.preventDefault();
			last.focus();
		} else if ( ! e.shiftKey && document.activeElement === last ) {
			e.preventDefault();
			first.focus();
		} else if ( ! panelEl.contains( document.activeElement ) ) {
			e.preventDefault();
			first.focus();
		}
	}

	function camelize( str ) {
		return str.replace( /-([a-z])/g, function ( m, c ) { return c.toUpperCase(); } );
	}

	function load() {
		try {
			var raw = localStorage.getItem( STORAGE_KEY );
			if ( raw ) {
				return Object.assign( {}, DEFAULTS, JSON.parse( raw ) );
			}
		} catch ( e ) {}
		return Object.assign( {}, DEFAULTS );
	}

	function save() {
		try { localStorage.setItem( STORAGE_KEY, JSON.stringify( state ) ); } catch ( e ) {}
	}

	function apply( s ) {
		var html = document.documentElement;
		var body = document.body || document.getElementsByTagName( 'body' )[ 0 ];
		if ( ! body ) {
			document.addEventListener( 'DOMContentLoaded', function () { apply( state ); }, { once: true } );
			return;
		}

		for ( var i = -1; i <= 5; i++ ) {
			html.classList.remove( 'wcag-fs-' + i );
			html.classList.remove( 'wcag-fs--' + Math.abs( i ) );
		}
		if ( s.fontLevel < 0 ) {
			html.classList.add( 'wcag-fs--1' );
		} else if ( s.fontLevel > 0 ) {
			html.classList.add( 'wcag-fs-' + s.fontLevel );
		}

		toggleBodyClass( body, 'wcag-high-contrast',     s.highContrast );
		toggleBodyClass( body, 'wcag-negative-contrast', s.negativeContrast );
		toggleBodyClass( body, 'wcag-grayscale',         s.grayscale );
		toggleBodyClass( body, 'wcag-underline-links',   s.underlineLinks );
		toggleBodyClass( body, 'wcag-highlight-focus',   s.highlightFocus );
		toggleBodyClass( body, 'wcag-readable-font',     s.readableFont );
		toggleBodyClass( body, 'wcag-pause-motion',      s.pauseMotion );
		toggleBodyClass( body, 'wcag-bigger-cursor',     s.biggerCursor );
	}

	function toggleBodyClass( body, cls, on ) {
		body.classList.toggle( cls, !! on );
	}

	function syncUi() {
		if ( ! widgetEl ) { return; }
		var meter = widgetEl.querySelector( '[data-wcag-font-level]' );
		if ( meter ) {
			meter.textContent = ( FONT_LABELS[ String( state.fontLevel ) ] || '100%' );
		}
		widgetEl.querySelectorAll( '[data-wcag-toggle]' ).forEach( function ( input ) {
			var key = camelize( input.getAttribute( 'data-wcag-toggle' ) );
			input.checked = !! state[ key ];
		} );
	}
}() );
