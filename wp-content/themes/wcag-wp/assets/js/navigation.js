/**
 * Primary navigation interactions.
 *
 * - Mobile menu toggle with aria-expanded sync.
 * - Submenu open/close on click for touch + keyboard.
 * - Submenu open on hover/focus-within for desktop.
 * - Keyboard escape closes submenus / mobile menu.
 * - Focus trap: none required for inline navigation; we just keep focus order natural.
 * - Accordion (WCAG widget) and Tabs (WCAG widget) wired here too.
 */

( function () {
	'use strict';

	function onReady( fn ) {
		if ( document.readyState !== 'loading' ) {
			fn();
		} else {
			document.addEventListener( 'DOMContentLoaded', fn );
		}
	}

	onReady( function () {
		initMobileToggle();
		initSubmenus();
		initAccordions();
		initTabs();
	} );

	/* ---------- Mobile menu ---------- */
	function initMobileToggle() {
		var toggle = document.querySelector( '.menu-toggle' );
		var nav    = document.getElementById( 'site-navigation' );
		if ( ! toggle || ! nav ) { return; }

		toggle.addEventListener( 'click', function () {
			var expanded = toggle.getAttribute( 'aria-expanded' ) === 'true';
			toggle.setAttribute( 'aria-expanded', String( ! expanded ) );
			nav.classList.toggle( 'is-open', ! expanded );
		} );

		document.addEventListener( 'keydown', function ( e ) {
			if ( e.key === 'Escape' && nav.classList.contains( 'is-open' ) ) {
				toggle.setAttribute( 'aria-expanded', 'false' );
				nav.classList.remove( 'is-open' );
				toggle.focus();
			}
		} );
	}

	/* ---------- Submenus ---------- */
	function initSubmenus() {
		var toggles = document.querySelectorAll( '.main-navigation .submenu-toggle' );
		toggles.forEach( function ( btn ) {
			btn.addEventListener( 'click', function () {
				var expanded = btn.getAttribute( 'aria-expanded' ) === 'true';
				closeSiblings( btn );
				btn.setAttribute( 'aria-expanded', String( ! expanded ) );
				var submenu = document.getElementById( btn.getAttribute( 'aria-controls' ) );
				if ( submenu ) { submenu.classList.toggle( 'is-open', ! expanded ); }
			} );
		} );

		document.addEventListener( 'click', function ( e ) {
			if ( ! e.target.closest( '.main-navigation .menu-item-has-children' ) ) {
				toggles.forEach( function ( btn ) {
					btn.setAttribute( 'aria-expanded', 'false' );
					var submenu = document.getElementById( btn.getAttribute( 'aria-controls' ) );
					if ( submenu ) { submenu.classList.remove( 'is-open' ); }
				} );
			}
		} );

		document.addEventListener( 'keydown', function ( e ) {
			if ( e.key !== 'Escape' ) { return; }
			toggles.forEach( function ( btn ) {
				if ( btn.getAttribute( 'aria-expanded' ) === 'true' ) {
					btn.setAttribute( 'aria-expanded', 'false' );
					var submenu = document.getElementById( btn.getAttribute( 'aria-controls' ) );
					if ( submenu ) { submenu.classList.remove( 'is-open' ); }
					btn.focus();
				}
			} );
		} );
	}

	function closeSiblings( current ) {
		var siblings = document.querySelectorAll( '.main-navigation .submenu-toggle' );
		siblings.forEach( function ( btn ) {
			if ( btn !== current ) {
				btn.setAttribute( 'aria-expanded', 'false' );
				var submenu = document.getElementById( btn.getAttribute( 'aria-controls' ) );
				if ( submenu ) { submenu.classList.remove( 'is-open' ); }
			}
		} );
	}

	/* ---------- Accordion (WCAG widget) ---------- */
	function initAccordions() {
		document.querySelectorAll( '.wcag-accordion' ).forEach( function ( accordion ) {
			var multi    = accordion.getAttribute( 'data-multiselect' ) === 'true';
			var triggers = accordion.querySelectorAll( '.wcag-accordion__trigger' );

			triggers.forEach( function ( trigger, idx ) {
				trigger.addEventListener( 'click', function () {
					var expanded = trigger.getAttribute( 'aria-expanded' ) === 'true';
					if ( ! multi ) {
						triggers.forEach( function ( other ) {
							if ( other !== trigger ) {
								other.setAttribute( 'aria-expanded', 'false' );
								var panel = document.getElementById( other.getAttribute( 'aria-controls' ) );
								if ( panel ) { panel.hidden = true; }
							}
						} );
					}
					trigger.setAttribute( 'aria-expanded', String( ! expanded ) );
					var panel = document.getElementById( trigger.getAttribute( 'aria-controls' ) );
					if ( panel ) { panel.hidden = expanded; }
				} );

				trigger.addEventListener( 'keydown', function ( e ) {
					var key = e.key;
					if ( key !== 'ArrowDown' && key !== 'ArrowUp' && key !== 'Home' && key !== 'End' ) { return; }
					e.preventDefault();
					var target;
					if ( key === 'ArrowDown' ) { target = triggers[ ( idx + 1 ) % triggers.length ]; }
					if ( key === 'ArrowUp' )   { target = triggers[ ( idx - 1 + triggers.length ) % triggers.length ]; }
					if ( key === 'Home' )      { target = triggers[ 0 ]; }
					if ( key === 'End' )       { target = triggers[ triggers.length - 1 ]; }
					if ( target ) { target.focus(); }
				} );
			} );
		} );
	}

	/* ---------- Tabs (WCAG widget) ---------- */
	function initTabs() {
		document.querySelectorAll( '.wcag-tabs' ).forEach( function ( tabsRoot ) {
			var tabs   = Array.prototype.slice.call( tabsRoot.querySelectorAll( '[role="tab"]' ) );
			var panels = tabs.map( function ( t ) { return document.getElementById( t.getAttribute( 'aria-controls' ) ); } );
			var orient = tabsRoot.querySelector( '[role="tablist"]' ).getAttribute( 'aria-orientation' ) || 'horizontal';

			tabs.forEach( function ( tab, idx ) {
				tab.addEventListener( 'click', function () { activate( idx ); } );
				tab.addEventListener( 'keydown', function ( e ) {
					var nextKey = orient === 'vertical' ? 'ArrowDown' : 'ArrowRight';
					var prevKey = orient === 'vertical' ? 'ArrowUp'   : 'ArrowLeft';

					if ( e.key === nextKey ) {
						e.preventDefault();
						activate( ( idx + 1 ) % tabs.length, true );
					} else if ( e.key === prevKey ) {
						e.preventDefault();
						activate( ( idx - 1 + tabs.length ) % tabs.length, true );
					} else if ( e.key === 'Home' ) {
						e.preventDefault();
						activate( 0, true );
					} else if ( e.key === 'End' ) {
						e.preventDefault();
						activate( tabs.length - 1, true );
					}
				} );
			} );

			function activate( idx, focus ) {
				tabs.forEach( function ( t, i ) {
					var active = i === idx;
					t.setAttribute( 'aria-selected', String( active ) );
					t.setAttribute( 'tabindex', active ? '0' : '-1' );
					if ( panels[ i ] ) {
						panels[ i ].hidden = ! active;
					}
				} );
				if ( focus ) { tabs[ idx ].focus(); }
			}
		} );
	}
}() );
