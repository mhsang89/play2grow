/**
 * Play2Grow Core – contact form enhancement (no dependencies).
 * Client-side validation + fetch() submission. Without JavaScript the form still
 * posts normally to admin-post.php.
 */
( function () {
	'use strict';

	var i18n = window.p2gcForm || {};

	/* ---------- Contact form ---------- */
	var phoneRe = /^(?:\+?84|0)(?:[35789]\d{8}|2\d{9})$/;
	var emailRe = /^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/;
	var nameRe = /^[\p{L}\p{M}\s.'-]+$/u;

	function errorEl( form, name ) {
		return form.querySelector( '#' + name + '-error' );
	}

	function setError( form, name, message ) {
		var input = form.querySelector( '[name="' + name + '"]' );
		var el = errorEl( form, name );
		if ( ! input ) {
			return;
		}
		if ( message ) {
			input.setAttribute( 'aria-invalid', 'true' );
			input.setAttribute( 'aria-describedby', name + '-error' );
			if ( el ) {
				el.textContent = message;
				el.hidden = false;
			}
		} else {
			input.removeAttribute( 'aria-invalid' );
			input.removeAttribute( 'aria-describedby' );
			if ( el ) {
				el.textContent = '';
				el.hidden = true;
			}
		}
	}

	function validate( form ) {
		var errors = {};
		var name = form.p2g_name.value.trim();
		var phone = form.p2g_phone.value.replace( /[\s.\-()]/g, '' );
		var email = form.p2g_email.value.trim();

		if ( ! name ) {
			errors.p2g_name = i18n.required;
		} else if ( name.length < 2 || name.length > 80 || ! nameRe.test( name ) ) {
			errors.p2g_name = i18n.nameLen;
		}
		if ( ! phone && ! email ) {
			errors.p2g_phone = i18n.contactReq;
		} else if ( phone && ! phoneRe.test( phone ) ) {
			errors.p2g_phone = i18n.phoneFormat;
		}
		if ( email && ! emailRe.test( email ) ) {
			errors.p2g_email = i18n.emailFormat;
		}
		if ( ! form.p2g_consent.checked ) {
			errors.p2g_consent = i18n.consentReq;
		}
		return errors;
	}

	function showStatus( wrap, type, message ) {
		var box = wrap.querySelector( '.form-status' );
		if ( ! box ) {
			return;
		}
		box.className = 'form-status form-status--' + type;
		box.textContent = message;
		box.hidden = false;
		box.focus();
	}

	document.querySelectorAll( '.lead-form' ).forEach( function ( form ) {
		var wrap = form.closest( '.lead-form-wrap' );
		var submit = form.querySelector( '[type="submit"]' );
		var touched = {};

		[ 'p2g_name', 'p2g_phone', 'p2g_email' ].forEach( function ( field ) {
			var input = form.querySelector( '[name="' + field + '"]' );
			if ( ! input ) {
				return;
			}
			input.addEventListener( 'blur', function () {
				touched[ field ] = true;
				var errors = validate( form );
				[ 'p2g_name', 'p2g_phone', 'p2g_email' ].forEach( function ( f ) {
					if ( touched[ f ] ) {
						setError( form, f, errors[ f ] );
					}
				} );
			} );
		} );
		form.p2g_consent.addEventListener( 'change', function () {
			if ( form.p2g_consent.checked ) {
				setError( form, 'p2g_consent', '' );
			}
		} );

		form.addEventListener( 'submit', function ( e ) {
			var errors = validate( form );
			[ 'p2g_name', 'p2g_phone', 'p2g_email', 'p2g_consent' ].forEach( function ( f ) {
				setError( form, f, errors[ f ] );
			} );
			var keys = Object.keys( errors );
			if ( keys.length ) {
				e.preventDefault();
				var first = form.querySelector( '[name="' + keys[ 0 ] + '"]' );
				if ( first ) {
					first.focus();
				}
				return;
			}

			if ( ! window.fetch || ! window.FormData ) {
				return; // Normal POST fallback.
			}
			e.preventDefault();

			var data = new FormData( form );
			data.append( 'p2g_ajax', '1' );
			submit.setAttribute( 'aria-busy', 'true' );
			submit.disabled = true;

			fetch( form.getAttribute( 'action' ), { method: 'POST', body: data, credentials: 'same-origin' } )
				.then( function ( res ) {
					return res.json().catch( function () {
						return { status: 'error', message: i18n.networkErr };
					} );
				} )
				.then( function ( json ) {
					if ( json && json.status === 'success' ) {
						wrap.classList.add( 'is-sent' );
						form.reset();
						showStatus( wrap, 'success', json.message );
						return;
					}
					var fieldErrors = ( json && json.errors ) || {};
					Object.keys( fieldErrors ).forEach( function ( f ) {
						setError( form, f, fieldErrors[ f ] );
					} );
					showStatus( wrap, 'error', ( json && json.message ) || i18n.networkErr );
				} )
				.catch( function () {
					showStatus( wrap, 'error', i18n.networkErr );
				} )
				.then( function () {
					submit.removeAttribute( 'aria-busy' );
					submit.disabled = false;
				} );
		} );
	} );
}() );
