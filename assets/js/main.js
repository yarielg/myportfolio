/**
 * Yariel Gordillo Portfolio — main.js
 * Sticky header, mobile nav, smooth scroll, contact form AJAX.
 */
( function () {
	'use strict';

	// ── STICKY HEADER ──────────────────────────────────────────────────────────
	const header = document.getElementById( 'site-header' );

	function handleScroll() {
		if ( window.scrollY > 20 ) {
			header.classList.add( 'scrolled' );
		} else {
			header.classList.remove( 'scrolled' );
		}
	}

	window.addEventListener( 'scroll', handleScroll, { passive: true } );
	handleScroll(); // run once on load

	// ── MOBILE NAV TOGGLE ──────────────────────────────────────────────────────
	const navToggle = document.getElementById( 'nav-toggle' );
	const siteNav   = document.getElementById( 'site-nav' );

	if ( navToggle && siteNav ) {
		// Inject "Let's Connect" CTA at the bottom of the mobile nav list.
		const navList = siteNav.querySelector( '.nav-list' );
		if ( navList ) {
			const ctaItem = document.createElement( 'li' );
			ctaItem.className = 'nav-cta-mobile';
			ctaItem.innerHTML = '<a href="#contact" class="btn btn-primary" style="margin-top:.5rem;width:100%;justify-content:center;">Let\'s Connect</a>';
			navList.appendChild( ctaItem );
		}

		function closeNav() {
			navToggle.setAttribute( 'aria-expanded', 'false' );
			navToggle.setAttribute( 'aria-label', 'Open navigation menu' );
			siteNav.classList.remove( 'is-open' );
			document.body.style.overflow = '';
		}

		navToggle.addEventListener( 'click', function () {
			const isOpen = this.getAttribute( 'aria-expanded' ) === 'true';
			this.setAttribute( 'aria-expanded', String( ! isOpen ) );
			this.setAttribute( 'aria-label', ! isOpen ? 'Close navigation menu' : 'Open navigation menu' );
			siteNav.classList.toggle( 'is-open', ! isOpen );
			document.body.style.overflow = ! isOpen ? 'hidden' : '';
		} );

		siteNav.querySelectorAll( 'a' ).forEach( function ( link ) {
			link.addEventListener( 'click', closeNav );
		} );

		document.addEventListener( 'keydown', function ( e ) {
			if ( e.key === 'Escape' && siteNav.classList.contains( 'is-open' ) ) {
				closeNav();
				navToggle.focus();
			}
		} );

		// Close nav on outside click
		document.addEventListener( 'click', function ( e ) {
			if ( siteNav.classList.contains( 'is-open' ) &&
				 ! siteNav.contains( e.target ) &&
				 ! navToggle.contains( e.target ) ) {
				closeNav();
			}
		} );
	}

	// ── CONTACT FORM ───────────────────────────────────────────────────────────
	const form     = document.getElementById( 'yg-contact-form' );
	const feedback = document.getElementById( 'form-feedback' );

	if ( form && feedback && window.ygData ) {
		form.addEventListener( 'submit', function ( e ) {
			e.preventDefault();

			const submitBtn = form.querySelector( '[type="submit"]' );
			const original  = submitBtn.textContent;

			submitBtn.disabled     = true;
			submitBtn.textContent  = 'Sending…';
			feedback.className     = '';
			feedback.textContent   = '';
			feedback.style.display = 'none';

			function sendForm( recaptchaToken ) {
				const data = new FormData( form );
				data.append( 'action', 'yg_contact' );
				data.append( 'nonce',  ygData.nonce );
				if ( recaptchaToken ) {
					data.append( 'recaptcha_token', recaptchaToken );
				}

				fetch( ygData.ajaxurl, {
					method:      'POST',
					body:        data,
					credentials: 'same-origin',
				} )
					.then( function ( res ) { return res.json(); } )
					.then( function ( json ) {
						if ( json.success ) {
							feedback.className   = 'success';
							feedback.textContent = json.data.message;
							form.reset();
						} else {
							feedback.className   = 'error';
							feedback.textContent = json.data.message || 'Something went wrong.';
						}
						feedback.style.display = 'block';
					} )
					.catch( function () {
						feedback.className   = 'error';
						feedback.textContent = 'Network error. Please try again or email me directly.';
						feedback.style.display = 'block';
					} )
					.finally( function () {
						submitBtn.disabled    = false;
						submitBtn.textContent = original;
						feedback.scrollIntoView( { behavior: 'smooth', block: 'nearest' } );
					} );
			}

			// Get reCAPTCHA v3 token if configured, then submit
			if ( window.grecaptcha && ygData.recaptchaSiteKey ) {
				grecaptcha.ready( function () {
					grecaptcha.execute( ygData.recaptchaSiteKey, { action: 'contact' } )
						.then( function ( token ) { sendForm( token ); } )
						.catch( function ()        { sendForm( '' );    } );
				} );
			} else {
				sendForm( '' );
			}
		} );
	}

	// ── SCROLL REVEAL ──────────────────────────────────────────────────────────
	// Progressive enhancement: only hides/animates when JS runs, IntersectionObserver
	// is supported, and the visitor hasn't asked for reduced motion.
	const prefersReduced = window.matchMedia( '(prefers-reduced-motion: reduce)' ).matches;

	if ( ! prefersReduced && 'IntersectionObserver' in window ) {
		document.documentElement.classList.add( 'yg-anim' );

		const revealSelectors = [
			'.section-header', '.value-card', '.service-card', '.case-card',
			'.plugin-card', '.testimonial-card', '.experience-item', '.ai-card',
			'.about-highlight', '.process-step', '.outcome-card', '.insight-card',
			'.skill-group', '.education-note', '.ai-closing', '.work-card',
		];

		const targets = document.querySelectorAll( revealSelectors.join( ',' ) );

		// Stagger siblings that share a parent for a subtle cascade.
		const groupIndex = new Map();
		targets.forEach( function ( el ) {
			el.classList.add( 'yg-reveal' );
			const parent = el.parentElement;
			const i = groupIndex.get( parent ) || 0;
			groupIndex.set( parent, i + 1 );
			if ( i > 0 ) {
				el.style.transitionDelay = Math.min( i * 70, 280 ) + 'ms';
			}
		} );

		const observer = new IntersectionObserver( function ( entries, obs ) {
			entries.forEach( function ( entry ) {
				if ( entry.isIntersecting ) {
					entry.target.classList.add( 'is-in' );
					obs.unobserve( entry.target );
				}
			} );
		}, { rootMargin: '0px 0px -8% 0px', threshold: 0.08 } );

		targets.forEach( function ( el ) { observer.observe( el ); } );

		// Fail-safe: reveal anything already in the viewport (covers landing on an
		// anchor or clicking a nav link before the observer fires — avoids blank sections).
		function revealVisible() {
			targets.forEach( function ( el ) {
				if ( el.classList.contains( 'is-in' ) ) { return; }
				const r = el.getBoundingClientRect();
				if ( r.top < window.innerHeight && r.bottom > 0 ) {
					el.classList.add( 'is-in' );
					observer.unobserve( el );
				}
			} );
		}
		window.addEventListener( 'load', revealVisible );
		window.addEventListener( 'hashchange', function () { setTimeout( revealVisible, 450 ); } );

		// Throttled scroll fail-safe so nothing can stay hidden even on fast jumps.
		let revealTicking = false;
		window.addEventListener( 'scroll', function () {
			if ( ! revealTicking ) {
				window.requestAnimationFrame( function () { revealVisible(); revealTicking = false; } );
				revealTicking = true;
			}
		}, { passive: true } );
	}

} )();
