/**
 * Play2Grow front-end behaviour (no dependencies).
 * - Mobile navigation + accessible sub-menus
 * - Header search toggle
 */
( function () {
	'use strict';

	var i18n = window.p2gI18n || {};
	var desktopQuery = window.matchMedia( '(min-width: 1200px)' );

	/* ---------- Navigation ---------- */
	var toggle = document.querySelector( '.nav-toggle' );
	var nav = document.getElementById( 'site-nav' );

	function setMenu( open ) {
		if ( ! toggle || ! nav ) {
			return;
		}
		toggle.setAttribute( 'aria-expanded', open ? 'true' : 'false' );
		toggle.setAttribute( 'aria-label', open ? i18n.closeMenu || 'Close menu' : i18n.openMenu || 'Open menu' );
		nav.classList.toggle( 'is-open', open );
	}

	if ( toggle && nav ) {
		setMenu( false );
		toggle.addEventListener( 'click', function () {
			setMenu( toggle.getAttribute( 'aria-expanded' ) !== 'true' );
		} );
		document.addEventListener( 'keydown', function ( e ) {
			if ( e.key === 'Escape' && nav.classList.contains( 'is-open' ) ) {
				setMenu( false );
				toggle.focus();
			}
		} );
		var onChange = function () {
			if ( desktopQuery.matches ) {
				setMenu( false );
			}
		};
		if ( desktopQuery.addEventListener ) {
			desktopQuery.addEventListener( 'change', onChange );
		} else if ( desktopQuery.addListener ) {
			desktopQuery.addListener( onChange );
		}
	}

	document.querySelectorAll( '.submenu-toggle' ).forEach( function ( btn ) {
		var sub = btn.parentElement ? btn.parentElement.querySelector( ':scope > .sub-menu' ) : null;
		if ( ! sub ) {
			return;
		}
		var label = btn.getAttribute( 'aria-label' ) || '';
		btn.addEventListener( 'click', function () {
			var open = btn.getAttribute( 'aria-expanded' ) !== 'true';
			btn.setAttribute( 'aria-expanded', open ? 'true' : 'false' );
			btn.setAttribute( 'aria-label', open ? label.replace( i18n.openSub || 'Mở', i18n.closeSub || 'Đóng' ) : label );
			sub.classList.toggle( 'is-open', open );
		} );
		btn.parentElement.addEventListener( 'keydown', function ( e ) {
			if ( e.key === 'Escape' && sub.classList.contains( 'is-open' ) ) {
				btn.click();
				btn.focus();
			}
		} );
	} );

	/* ---------- Header search ---------- */
	var searchToggle = document.querySelector( '[data-search-toggle]' );
	var searchPanel = document.getElementById( 'header-search' );
	if ( searchToggle && searchPanel ) {
		searchToggle.setAttribute( 'role', 'button' );
		searchToggle.addEventListener( 'click', function ( e ) {
			e.preventDefault();
			var open = searchPanel.hasAttribute( 'hidden' );
			if ( open ) {
				searchPanel.removeAttribute( 'hidden' );
				var input = searchPanel.querySelector( 'input[type="search"]' );
				if ( input ) {
					input.focus();
				}
			} else {
				searchPanel.setAttribute( 'hidden', '' );
			}
			searchToggle.setAttribute( 'aria-expanded', open ? 'true' : 'false' );
		} );
		searchPanel.addEventListener( 'keydown', function ( e ) {
			if ( e.key === 'Escape' ) {
				searchPanel.setAttribute( 'hidden', '' );
				searchToggle.setAttribute( 'aria-expanded', 'false' );
				searchToggle.focus();
			}
		} );
	}
}() );
