/**
 * Portfolio single — anchor nav with IntersectionObserver section highlighting.
 */
( function () {
	'use strict';

	var nav = document.getElementById( 'pf-anchor-nav' );
	if ( ! nav ) return;

	var links = nav.querySelectorAll( '.pf-anchor-link' );
	if ( ! links.length ) return;

	// Sticky shadow on scroll
	window.addEventListener( 'scroll', function () {
		nav.classList.toggle( 'is-scrolled', window.scrollY > 80 );
	}, { passive: true } );

	// Smooth scroll on anchor click
	links.forEach( function ( link ) {
		link.addEventListener( 'click', function ( e ) {
			var href     = link.getAttribute( 'href' );
			var targetId = href ? href.slice( 1 ) : '';
			var target   = targetId ? document.getElementById( targetId ) : null;
			if ( ! target ) return;
			e.preventDefault();
			target.scrollIntoView( { behavior: 'smooth', block: 'start' } );
		} );
	} );

	// IntersectionObserver for active highlighting
	var sectionIds = Array.from( links ).map( function ( l ) {
		var href = l.getAttribute( 'href' );
		return href ? href.slice( 1 ) : '';
	} ).filter( Boolean );

	var sections = sectionIds.map( function ( id ) {
		return document.getElementById( id );
	} ).filter( Boolean );

	if ( ! sections.length ) return;

	var headerEl  = document.getElementById( 'site-header' );
	var headerH   = headerEl ? headerEl.offsetHeight : 64;
	var navH      = nav.offsetHeight || 44;
	var rootTop   = -( headerH + navH ) + 'px';

	var activeId = sections[ 0 ] ? sections[ 0 ].id : '';

	function setActive( id ) {
		if ( id === activeId ) return;
		activeId = id;
		links.forEach( function ( link ) {
			var href = link.getAttribute( 'href' );
			link.classList.toggle( 'is-active', href === '#' + id );
		} );
	}

	// Start with first section active
	if ( activeId ) {
		links.forEach( function ( link ) {
			link.classList.toggle( 'is-active', link.getAttribute( 'href' ) === '#' + activeId );
		} );
	}

	var observer = new IntersectionObserver(
		function ( entries ) {
			entries.forEach( function ( entry ) {
				if ( entry.isIntersecting ) {
					setActive( entry.target.id );
				}
			} );
		},
		{
			rootMargin: rootTop + ' 0px -55% 0px',
			threshold:  0,
		}
	);

	sections.forEach( function ( section ) {
		observer.observe( section );
	} );
} )();
