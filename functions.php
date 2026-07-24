<?php
/**
 * Yariel Gordillo Portfolio — functions.php
 * Theme setup, asset enqueue, static data arrays, contact form AJAX handler.
 * CPTs and Customizer live in inc/.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// ── INCLUDES ───────────────────────────────────────────────────────────────────
require_once get_template_directory() . '/inc/customizer.php';
require_once get_template_directory() . '/inc/cpt-portfolio.php';
require_once get_template_directory() . '/inc/cpt-skills.php';
require_once get_template_directory() . '/inc/seeder.php';

// ── THEME SETUP ────────────────────────────────────────────────────────────────

function yg_theme_setup(): void {
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'custom-logo' );
	add_theme_support( 'html5', [
		'search-form', 'comment-form', 'comment-list',
		'gallery', 'caption', 'style', 'script',
	] );

	register_nav_menus( [
		'primary' => __( 'Primary Navigation', 'yg-portfolio' ),
	] );
}
add_action( 'after_setup_theme', 'yg_theme_setup' );

// ── ASSETS ─────────────────────────────────────────────────────────────────────

// Cache-busting asset version from file modified time, so CSS/JS changes always
// reach browsers on deploy. Falls back to theme version if the file is missing.
function yg_asset_ver( string $path ): string {
	return file_exists( $path )
		? (string) filemtime( $path )
		: (string) wp_get_theme()->get( 'Version' );
}

function yg_enqueue_assets(): void {
	$dir = get_template_directory();
	$uri = get_template_directory_uri();

	// Modern type system: Sora (headings) + Inter (body).
	wp_enqueue_style(
		'yg-fonts',
		'https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Sora:wght@600;700;800&display=swap',
		[],
		null
	);

	wp_enqueue_style(
		'yg-main',
		$uri . '/assets/css/main.css',
		[ 'yg-fonts' ],
		yg_asset_ver( $dir . '/assets/css/main.css' )
	);

	wp_enqueue_script(
		'yg-main',
		$uri . '/assets/js/main.js',
		[],
		yg_asset_ver( $dir . '/assets/js/main.js' ),
		true
	);

	$localize = [
		'ajaxurl' => admin_url( 'admin-ajax.php' ),
		'nonce'   => wp_create_nonce( 'yg_contact_nonce' ),
	];

	$rc_site_key = get_theme_mod( 'yg_recaptcha_site_key', '' );
	if ( $rc_site_key && is_front_page() ) {
		wp_enqueue_script(
			'google-recaptcha',
			'https://www.google.com/recaptcha/api.js?render=' . rawurlencode( $rc_site_key ),
			[],
			null,
			true
		);
		$localize['recaptchaSiteKey'] = $rc_site_key;
	}

	wp_localize_script( 'yg-main', 'ygData', $localize );
}
add_action( 'wp_enqueue_scripts', 'yg_enqueue_assets' );

// Preconnect to Google Fonts hosts so the webfont loads faster.
function yg_resource_hints( array $hints, string $relation ): array {
	if ( 'preconnect' === $relation ) {
		$hints[] = 'https://fonts.googleapis.com';
		$hints[] = [ 'href' => 'https://fonts.gstatic.com', 'crossorigin' => 'anonymous' ];
	}
	return $hints;
}
add_filter( 'wp_resource_hints', 'yg_resource_hints', 10, 2 );

// ── SEO / SOCIAL SHARE META ─────────────────────────────────────────────────────
// Description + Open Graph + Twitter tags so the link previews well when shared
// (recruiters paste URLs into Slack/email). Drop a 1200×630 image at
// assets/images/og-default.jpg and it will be used automatically (deploys as code).

function yg_meta_tags(): void {
	$desc = get_theme_mod(
		'yg_meta_description',
		'Senior WooCommerce, WordPress & PHP developer — 12+ years building production e-commerce systems, with two plugins published on WordPress.org. Open to full-time & contract roles · Miami / remote.'
	);
	$title    = wp_get_document_title();
	$home     = home_url( '/' );
	$og_path  = get_template_directory() . '/assets/images/og-default.jpg';
	$og_url   = get_template_directory_uri() . '/assets/images/og-default.jpg';

	echo '<meta name="description" content="' . esc_attr( $desc ) . '">' . "\n";
	echo '<meta property="og:type" content="website">' . "\n";
	echo '<meta property="og:site_name" content="' . esc_attr( get_bloginfo( 'name' ) ) . '">' . "\n";
	echo '<meta property="og:title" content="' . esc_attr( $title ) . '">' . "\n";
	echo '<meta property="og:description" content="' . esc_attr( $desc ) . '">' . "\n";
	echo '<meta property="og:url" content="' . esc_url( $home ) . '">' . "\n";
	echo '<meta name="twitter:card" content="summary_large_image">' . "\n";
	echo '<meta name="twitter:title" content="' . esc_attr( $title ) . '">' . "\n";
	echo '<meta name="twitter:description" content="' . esc_attr( $desc ) . '">' . "\n";

	if ( file_exists( $og_path ) ) {
		echo '<meta property="og:image" content="' . esc_url( $og_url ) . '">' . "\n";
		echo '<meta property="og:image:width" content="1200">' . "\n";
		echo '<meta property="og:image:height" content="630">' . "\n";
		echo '<meta name="twitter:image" content="' . esc_url( $og_url ) . '">' . "\n";
	}
}
add_action( 'wp_head', 'yg_meta_tags', 1 );

// ── PORTFOLIO SINGLE ASSETS ────────────────────────────────────────────────────

function yg_enqueue_portfolio_assets(): void {
	if ( ! is_singular( 'portfolio' ) ) {
		return;
	}
	$dir = get_template_directory();
	$uri = get_template_directory_uri();

	wp_enqueue_style(
		'yg-portfolio',
		$uri . '/assets/css/portfolio.css',
		[ 'yg-main' ],
		yg_asset_ver( $dir . '/assets/css/portfolio.css' )
	);

	wp_enqueue_script(
		'yg-portfolio',
		$uri . '/assets/js/portfolio.js',
		[],
		yg_asset_ver( $dir . '/assets/js/portfolio.js' ),
		true
	);
}
add_action( 'wp_enqueue_scripts', 'yg_enqueue_portfolio_assets' );

// ── HEX → RGB PARTS ────────────────────────────────────────────────────────────

function yg_hex_to_rgba_parts( string $hex ): string {
	$hex = ltrim( $hex, '#' );
	if ( strlen( $hex ) === 3 ) {
		$hex = $hex[0] . $hex[0] . $hex[1] . $hex[1] . $hex[2] . $hex[2];
	}
	return implode( ', ', [
		hexdec( substr( $hex, 0, 2 ) ),
		hexdec( substr( $hex, 2, 2 ) ),
		hexdec( substr( $hex, 4, 2 ) ),
	] );
}

// ── CONTACT FORM AJAX ──────────────────────────────────────────────────────────

function yg_handle_contact(): void {
	check_ajax_referer( 'yg_contact_nonce', 'nonce' );

	$name    = sanitize_text_field( wp_unslash( $_POST['name']         ?? '' ) );
	$email   = sanitize_email(       wp_unslash( $_POST['email']        ?? '' ) );
	$type    = sanitize_text_field( wp_unslash( $_POST['project_type'] ?? '' ) );
	$message = sanitize_textarea_field( wp_unslash( $_POST['message']   ?? '' ) );

	if ( empty( $name ) || empty( $email ) || empty( $message ) ) {
		wp_send_json_error( [ 'message' => 'Please fill in all required fields.' ] );
	}

	if ( ! is_email( $email ) ) {
		wp_send_json_error( [ 'message' => 'Please enter a valid email address.' ] );
	}

	// ── reCAPTCHA v3 verification ──────────────────────────────────────────────
	$secret_key = get_theme_mod( 'yg_recaptcha_secret_key', '' );
	if ( $secret_key ) {
		$rc_token = sanitize_text_field( wp_unslash( $_POST['recaptcha_token'] ?? '' ) );

		if ( empty( $rc_token ) ) {
			wp_send_json_error( [ 'message' => 'Security check failed. Please refresh the page and try again.' ] );
		}

		$rc_response = wp_remote_post( 'https://www.google.com/recaptcha/api/siteverify', [
			'timeout' => 10,
			'body'    => [
				'secret'   => $secret_key,
				'response' => $rc_token,
				'remoteip' => sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ?? '' ) ),
			],
		] );

		if ( is_wp_error( $rc_response ) ) {
			wp_send_json_error( [ 'message' => 'Security check failed. Please try again.' ] );
		}

		$rc_result = json_decode( wp_remote_retrieve_body( $rc_response ), true );

		if ( empty( $rc_result['success'] ) || ( $rc_result['score'] ?? 0 ) < 0.5 ) {
			wp_send_json_error( [ 'message' => 'Security check failed. Please try again or email me directly.' ] );
		}
	}

	$to      = 'yariel.gordillo@gmail.com';
	$subject = '[Portfolio] New inquiry from ' . $name;
	$body    = "Name: {$name}\nEmail: {$email}\nProject Type: {$type}\n\nMessage:\n{$message}";
	$headers = [
		'Content-Type: text/plain; charset=UTF-8',
		"Reply-To: {$name} <{$email}>",
	];

	if ( wp_mail( $to, $subject, $body, $headers ) ) {
		wp_send_json_success( [ 'message' => 'Thank you! I will be in touch soon.' ] );
	} else {
		wp_send_json_error( [ 'message' => 'Message failed to send. Please email me directly.' ] );
	}
}
add_action( 'wp_ajax_yg_contact',        'yg_handle_contact' );
add_action( 'wp_ajax_nopriv_yg_contact', 'yg_handle_contact' );

// ── INLINE SVG ICONS ───────────────────────────────────────────────────────────

function yg_icon( string $name, string $class = '' ): string {
	$c = $class ? ' class="' . esc_attr( $class ) . '"' : '';

	$icons = [
		'audit'       => '<svg' . $c . ' viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>',
		'plugin'      => '<svg' . $c . ' viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 2L2 7l10 5 10-5-10-5z"/><path d="M2 17l10 5 10-5"/><path d="M2 12l10 5 10-5"/></svg>',
		'api'         => '<svg' . $c . ' viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="2" y="3" width="6" height="6" rx="1"/><rect x="16" y="3" width="6" height="6" rx="1"/><rect x="9" y="15" width="6" height="6" rx="1"/><path d="M5 9v3a2 2 0 002 2h10a2 2 0 002-2V9"/><line x1="12" y1="14" x2="12" y2="15"/></svg>',
		'performance' => '<svg' . $c . ' viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="13 2 13 9 20 9"/><path d="M22 12A10 10 0 1112 2"/><polyline points="10 15 12 13 14 15"/></svg>',
		'automation'  => '<svg' . $c . ' viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="3"/><path d="M19.07 4.93a10 10 0 010 14.14M4.93 4.93a10 10 0 000 14.14"/><path d="M14.83 9.17a4 4 0 010 5.66M9.17 9.17a4 4 0 000 5.66"/></svg>',
		'check'       => '<svg' . $c . ' viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="20 6 9 17 4 12"/></svg>',
		'arrow-right' => '<svg' . $c . ' viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>',
		'linkedin'    => '<svg' . $c . ' viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M16 8a6 6 0 016 6v7h-4v-7a2 2 0 00-2-2 2 2 0 00-2 2v7h-4v-7a6 6 0 016-6zM2 9h4v12H2z"/><circle cx="4" cy="4" r="2"/></svg>',
		'github'      => '<svg' . $c . ' viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M9 19c-5 1.5-5-2.5-7-3m14 6v-3.87a3.37 3.37 0 00-.94-2.61c3.14-.35 6.44-1.54 6.44-7A5.44 5.44 0 0020 4.77 5.07 5.07 0 0019.91 1S18.73.65 16 2.48a13.38 13.38 0 00-7 0C6.27.65 5.09 1 5.09 1A5.07 5.07 0 005 4.77a5.44 5.44 0 00-1.5 3.78c0 5.42 3.3 6.61 6.44 7A3.37 3.37 0 009 18.13V22"/></svg>',
		'mail'        => '<svg' . $c . ' viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>',
		'contra'      => '<svg' . $c . ' viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 14H9V8h2v8zm4 0h-2V8h2v8z"/></svg>',
			'star'        => '<svg' . $c . ' viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>',
			'quote'       => '<svg' . $c . ' viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M9.5 5C6.46 5 4 7.46 4 10.5V19h8v-8H6.5c0-1.66 1.34-3 3-3V5zm10 0C16.46 5 14 7.46 14 10.5V19h8v-8h-5.5c0-1.66 1.34-3 3-3V5z"/></svg>',
			'download'    => '<svg' . $c . ' viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>',
			'users'       => '<svg' . $c . ' viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/></svg>',
			'external'    => '<svg' . $c . ' viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M18 13v6a2 2 0 01-2 2H5a2 2 0 01-2-2V8a2 2 0 012-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>',
			'wordpress'   => '<svg' . $c . ' viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 2C6.49 2 2 6.49 2 12s4.49 10 10 10 10-4.49 10-10S17.51 2 12 2zm0 1.2a8.8 8.8 0 016.9 3.35h-.16c-.6 0-1.02.52-1.02 1.08 0 .5.29.93.6 1.43.23.41.5.93.5 1.68 0 .52-.2 1.13-.46 1.98l-.6 2.02-2.19-6.52c.36-.02.69-.06.69-.06.33-.04.29-.52-.04-.5 0 0-.98.08-1.61.08-.6 0-1.6-.08-1.6-.08-.33-.02-.37.48-.04.5 0 0 .31.04.63.06l.92 2.52-1.29 3.87-2.15-6.39c.36-.02.69-.06.69-.06.33-.04.29-.52-.04-.5 0 0-.98.08-1.61.08-.11 0-.24 0-.38-.01A8.79 8.79 0 0112 3.2zM3.66 8.38l3.9 10.68A8.8 8.8 0 013.2 12c0-1.28.27-2.5.46-3.62zm8.6 3.24l1.98 5.42c.01.03.03.06.05.09-.66.23-1.36.36-2.09.36-.62 0-1.21-.09-1.78-.26l1.84-5.61zm5.34-1.13c.32.83.36 1.83.02 3.03l-.02.05-1.72 4.98a8.83 8.83 0 003.62-6.55c0-.53-.05-1.04-.14-1.51z"/></svg>',
		'menu'        => '<svg' . $c . ' viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="18" x2="21" y2="18"/></svg>',
		'close'       => '<svg' . $c . ' viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>',
	];

	return $icons[ $name ] ?? '';
}

// ── DATA: SERVICES ─────────────────────────────────────────────────────────────

function yg_get_services(): array {
	return [
		[
			'title'       => 'WooCommerce Store Audit &amp; Optimization',
			'description' => 'I review WooCommerce stores to find issues affecting checkout, payments, performance, plugins, admin speed, shipping/tax setup, and customer experience.',
			'tags'        => [ 'WooCommerce', 'Store Audit', 'Checkout Optimization', 'Plugin Review', 'Performance Review' ],
			'icon'        => 'audit',
		],
		[
			'title'       => 'Custom WordPress Plugin Development',
			'description' => 'I build custom WordPress and WooCommerce plugins for business-specific workflows, admin tools, shortcodes, checkout logic, product/order features, reporting, and backend automation.',
			'tags'        => [ 'WordPress Plugin', 'WooCommerce Plugin', 'PHP', 'MySQL', 'Custom Development' ],
			'icon'        => 'plugin',
		],
		[
			'title'       => 'WooCommerce API Integrations',
			'description' => 'I connect WooCommerce with shipping tools, inventory systems, ERPs, CRMs, payment gateways, reporting tools, fulfillment systems, and other third-party APIs.',
			'tags'        => [ 'REST API', 'Webhooks', 'Inventory Sync', 'ERP', 'CRM', 'Shipping Integration' ],
			'icon'        => 'api',
		],
		[
			'title'       => 'WooCommerce Performance Troubleshooting',
			'description' => 'I diagnose slow admin dashboards, checkout delays, database queries, plugin conflicts, scheduled actions, AJAX/REST issues, caching problems, and hosting limitations.',
			'tags'        => [ 'Performance', 'Slow Admin', 'Query Monitor', 'Caching', 'MySQL', 'Troubleshooting' ],
			'icon'        => 'performance',
		],
		[
			'title'       => 'E-commerce Automation Workflows',
			'description' => 'I help stores reduce manual work through order automation, CSV exports, reporting workflows, customer notifications, inventory updates, scheduled tasks, and AI-assisted workflows.',
			'tags'        => [ 'Automation', 'Workflow Automation', 'CSV Export', 'Reports', 'AI Workflows' ],
			'icon'        => 'automation',
		],
	];
}

// ── DATA: PUBLISHED PLUGINS (WordPress.org — verifiable proof) ──────────────────
// Real, public, clickable. Update the stats here when they change on WordPress.org.

function yg_get_published_plugins(): array {
	return [
		[
			'name'    => 'WRN Pricing Rules for WooCommerce',
			'tagline' => 'Role-based pricing and custom price lists for WooCommerce B2B, wholesale, and VIP stores — logged-in customers automatically see their assigned prices across shop, cart, checkout, and order emails.',
			'url'     => 'https://wordpress.org/plugins/wr-price-list-for-woocommerce/',
			'tags'    => [ 'WooCommerce', 'B2B Pricing', 'PHP', 'Published' ],
			'stats'   => [
				[ 'icon' => 'star',     'label' => '4.3 rating' ],
				[ 'icon' => 'users',    'label' => '50+ active installs' ],
				[ 'icon' => 'wordpress','label' => 'On WordPress.org' ],
			],
		],
		[
			'name'    => 'WRN Store Monitor Lite',
			'tagline' => 'Detects WooCommerce operational issues — failed and stuck orders, payment failures, checkout configuration, inventory, and environment diagnostics — and surfaces them on a single health dashboard.',
			'url'     => 'https://wordpress.org/plugins/wrn-store-monitor-lite/',
			'tags'    => [ 'WooCommerce', 'Monitoring', 'PHP', 'Published' ],
			'stats'   => [
				[ 'icon' => 'wordpress', 'label' => 'On WordPress.org' ],
				[ 'icon' => 'download',  'label' => 'Free & open source' ],
				[ 'icon' => 'check',     'label' => 'Reviewed & approved' ],
			],
		],
	];
}

// ── DATA: SELECTED WORK (real live sites) ───────────────────────────────────────
// Real client sites. Add an 'image' key (theme asset URL) to any entry to show a
// screenshot instead of the gradient+monogram placeholder. Deploys with the theme.

function yg_get_selected_work(): array {
	return [
		// E-commerce / WooCommerce builds — capability tags reflect real features.
		'ecommerce' => [
			[ 'name' => 'Bee-Och',              'url' => 'https://bee-och.com/',            'accent' => '#f59e0b', 'tags' => [ 'Subscriptions', 'Bundles', 'Loyalty & Referrals', 'B2B + B2C' ] ],
			[ 'name' => 'Old Dominion Furniture','url' => 'https://www.olddominionfurniture.com/', 'accent' => '#8b5cf6', 'tags' => [ 'B2B + B2C', 'ERP Integration', 'Inventory Sync', 'Distributor Portal' ] ],
			[ 'name' => 'MediaShout',            'url' => 'https://mediashout.com/',         'accent' => '#06b6d4', 'tags' => [ 'Subscriptions', 'Bundles', 'License Keys' ] ],
			[ 'name' => 'Ecolink',               'url' => 'https://ecolink.com/',            'accent' => '#10b981', 'tags' => [ 'ShipStation', 'Fulfillment' ] ],
			[ 'name' => 'The Floor Project',     'url' => 'https://thefloorproject.com/',    'accent' => '#f43f5e', 'tags' => [ 'Catalog', 'Inventory Feed', 'B2B', 'Dealer Program' ] ],
			[ 'name' => 'Quest Manufacturing',   'url' => 'https://questmanufacturing.net/', 'accent' => '#3b82f6', 'tags' => [ 'B2B', 'Manufacturer' ] ],
			[ 'name' => 'Brenton USA',           'url' => 'https://www.brentonusa.com/',     'accent' => '#6366f1', 'tags' => [ 'WooCommerce' ] ],
			[ 'name' => 'YardCraft',             'url' => 'https://www.yardcraft.com/',      'accent' => '#22c55e', 'tags' => [ 'WooCommerce' ] ],
			[ 'name' => 'TRACT Optics',          'url' => 'https://tractoptics.com/',        'accent' => '#0ea5e9', 'tags' => [ 'WooCommerce' ] ],
			[ 'name' => 'iBull Mfg',             'url' => 'https://ibullmfg.com/',           'accent' => '#d97706', 'tags' => [ 'WooCommerce', 'Manufacturer' ] ],
			[ 'name' => 'Falcon Strike USA',     'url' => 'https://www.falconstrikeusa.com/','accent' => '#ef4444', 'tags' => [ 'WooCommerce' ] ],
			[ 'name' => 'Comp-N-Choke',          'url' => 'https://compnchoke.com/',         'accent' => '#14b8a6', 'tags' => [ 'WooCommerce' ] ],
			[ 'name' => 'Cornfield Fishing Gear','url' => 'https://cornfieldfishinggear.com/','accent' => '#0891b2', 'tags' => [ 'WooCommerce' ] ],
			[ 'name' => 'Steinel Ammo',          'url' => 'https://steinelammo.com/',        'accent' => '#a16207', 'tags' => [ 'WooCommerce' ] ],
			[ 'name' => 'Mobile Chicken House',  'url' => 'https://mobilechickenhouse.com/', 'accent' => '#65a30d', 'tags' => [ 'WooCommerce' ] ],
			[ 'name' => 'Porch Store',           'url' => 'https://porchstore.com/',         'accent' => '#7c3aed', 'tags' => [ 'WooCommerce' ] ],
			[ 'name' => 'Northern Machine',      'url' => 'https://northernmachine.com/',    'accent' => '#475569', 'tags' => [ 'WooCommerce', 'Manufacturer' ] ],
			[ 'name' => 'MyHDiet',               'url' => 'https://myhdiet.com/',            'accent' => '#16a34a', 'tags' => [ 'Shopify', 'Subscriptions' ] ],
		],
		// Non-store websites — shown as a compact link list.
		'websites' => [
			[ 'name' => 'Affinity Consulting',   'url' => 'https://www.affinityconsulting.com/' ],
			[ 'name' => 'Lawyerist',             'url' => 'https://lawyerist.com/' ],
			[ 'name' => 'Partners in Aviation',  'url' => 'https://www.partnersinaviation.com/' ],
			[ 'name' => 'Lone Mountain Aircraft','url' => 'https://lonemountainaircraft.com/' ],
			[ 'name' => 'IBP',                   'url' => 'https://www.ibpllc.com/' ],
			[ 'name' => 'Savvy Aviation',        'url' => 'https://www.savvyaviation.com/' ],
			[ 'name' => 'SNF Solutions',         'url' => 'https://snf-solutions.com/' ],
			[ 'name' => 'The CEO Forum',         'url' => 'https://theceoforum.org/' ],
			[ 'name' => 'Snyder Wrestling',      'url' => 'https://snyderwrestling.com/' ],
			[ 'name' => 'DTH Remodel',           'url' => 'https://www.dthremodel.com/' ],
			[ 'name' => 'Bullen Tech',           'url' => 'https://www.bullentech.com/' ],
			[ 'name' => 'Seven Seas Insurance',  'url' => 'https://sevenseasins.com/' ],
			[ 'name' => 'Helly Es Coaching',     'url' => 'https://hellyescoachingonline.com/' ],
			[ 'name' => 'Vision Trust',          'url' => 'https://visiontrust.org/' ],
		],
	];
}

// ── DATA: TESTIMONIALS ─────────────────────────────────────────────────────────
// Real client reviews. Company-name references removed at the client's request;
// wording otherwise preserved. Stored here as code so it deploys with the theme.

function yg_get_testimonials(): array {
	return [
		[
			'quote'  => 'Yariel Gordillo provides us excellent advice and immediately demonstrated top-level knowledge of our web needs and recommended pinpoint solutions which has already paid dividends. We highly recommend Yariel for any company needing to expand their online presence and E-business growth by providing end-to-end results!',
			'name'   => 'Nestor Novo',
			'role'   => 'CEO, Quest Technology International',
			'source' => 'Google review',
		],
		[
			'quote'  => 'I had a great experience partnering with Yariel on the development of a custom payroll application. He brought strong technical knowledge, clear communication, and a practical understanding of how to build software that supports real business operations. He was reliable, detail-oriented, and a valuable development partner throughout the project.',
			'name'   => 'Aristides Gutierrez',
			'role'   => 'Founder & CEO, Dev FL',
			'source' => 'Google review',
		],
		[
			'quote'  => 'We had a very positive experience working with Yariel. He is knowledgeable, responsive, and understands the needs of product-based businesses. He helped us with our ecommerce website and technical setup, including important details related to products, customers, and business operations. If you need a reliable WooCommerce or ecommerce developer in Miami, Yariel is a great choice.',
			'name'   => 'Ramiro Domínguez',
			'role'   => 'Developer Lead, Belgium Ferro',
			'source' => 'Google review',
		],
	];
}

// ── DATA: WORK EXPERIENCE ────────────────────────────────────────────────────────
// From résumé. Stored as code so it deploys with the theme.

function yg_get_experience(): array {
	return [
		[
			'role'     => 'Lead PHP / WordPress Developer',
			'company'  => 'Thomas Bennett Group',
			'location' => 'Remote',
			'dates'    => 'Jan 2020 – Present',
			'current'  => true,
			'bullets'  => [
				'Lead plugin and theme development, and ongoing site maintenance for production WordPress systems.',
				'Build REST APIs and integrations with third-party services.',
				'WooCommerce consulting and development — custom flows built to match specific business requirements.',
				'Database and data modeling; UX/UI work with jQuery, Bootstrap, Vue.js, and SASS.',
			],
		],
		[
			'role'     => 'PHP / Laravel / WordPress Developer',
			'company'  => 'Quest Technology International',
			'location' => 'Medley, FL',
			'dates'    => 'Dec 2017 – Dec 2020',
			'current'  => false,
			'bullets'  => [
				'Built applications in Laravel and WordPress, including custom plugins and themes.',
				'Created RESTful APIs for communication between applications; managed SQL/MySQL databases.',
				'Delivered WooCommerce e-commerce solutions and monitored/troubleshot site performance.',
				'SAP Business One consulting and integration — Crystal Reports, Data Workbench, and custom queries.',
			],
		],
		[
			'role'     => 'PHP Developer',
			'company'  => 'Upwork (Freelance)',
			'location' => 'Remote',
			'dates'    => 'Dec 2016 – Dec 2017',
			'current'  => false,
			'bullets'  => [
				'Automated business processes with the Laravel framework.',
				'Built and deployed web applications with relational databases, authentication, and authorization.',
				'Implemented and maintained CMS platforms (primarily WordPress, some Drupal), including custom plugins and content types.',
				'Delivered upgrade/migration plans and custom themes for high-traffic WordPress sites.',
			],
		],
		[
			'role'     => 'Web Developer — Frontend & Backend',
			'company'  => 'Center for Government Development (CGD)',
			'location' => 'Artemisa, Cuba',
			'dates'    => 'Aug 2012 – Oct 2016',
			'current'  => false,
			'bullets'  => [
				'Part of a team building statistical systems for government entities.',
				'UI/UX design, content management systems, and single-page applications.',
				'Version control (Git), MVC/MVVM architecture, and REST API data integration (JSON/XML).',
			],
		],
		[
			'role'     => 'Web / Front-End Developer',
			'company'  => 'University of Computer Science',
			'location' => 'Havana, Cuba',
			'dates'    => 'Sep 2010 – Jul 2012',
			'current'  => false,
			'bullets'  => [
				'Developed web applications for process automation and data-warehouse management.',
				'Architecture definition, software analysis, and project planning.',
			],
		],
	];
}

// ── DATA: PROCESS ──────────────────────────────────────────────────────────────

function yg_get_process(): array {
	return [
		[ 'num' => '01', 'title' => 'Diagnose', 'desc' => 'Understand the real business problem, user flow, and technical constraints before touching code.' ],
		[ 'num' => '02', 'title' => 'Plan',     'desc' => 'Choose the cleanest technical approach, identify dependencies, and define what success looks like.' ],
		[ 'num' => '03', 'title' => 'Build',    'desc' => 'Create custom WordPress/PHP solutions, plugins, integrations, or automations using best practices.' ],
		[ 'num' => '04', 'title' => 'Test',     'desc' => 'Validate edge cases, staging behavior, logs, checkout flows, order statuses, and user roles.' ],
		[ 'num' => '05', 'title' => 'Deploy',   'desc' => 'Release carefully with rollback awareness, staging verification, and clear documentation.' ],
		[ 'num' => '06', 'title' => 'Improve',  'desc' => 'Monitor, optimize, and refine based on real usage, client feedback, and production behavior.' ],
	];
}

// ── DATA: OUTCOMES ─────────────────────────────────────────────────────────────

function yg_get_outcomes(): array {
	return [
		'Fewer manual workflows',
		'Cleaner checkout experiences',
		'Better product data accuracy',
		'More stable WooCommerce operations',
		'Faster admin workflows',
		'Better API reliability',
		'Improved subscription & payment recovery',
		'Clearer reporting and exports',
	];
}

// ── DATA: INSIGHTS ─────────────────────────────────────────────────────────────

function yg_get_insights(): array {
	return [
		[
			'title'   => 'WooCommerce stores need more than a nice homepage',
			'excerpt' => 'Most WooCommerce issues happen behind the scenes — in checkout, payment flows, product data, and backend performance. Design is only one layer.',
		],
		[
			'title'   => 'Why I focus on WooCommerce automation',
			'excerpt' => 'Manual work adds up. The right automation saves hours every week and reduces costly errors in order and inventory workflows.',
		],
		[
			'title'   => 'Page speed scores do not tell the full story',
			'excerpt' => 'A Lighthouse score is useful, but real performance means diagnosing slow queries, caching gaps, plugin conflicts, and admin-side slowness.',
		],
		[
			'title'   => 'Sometimes a custom plugin beats adding another plugin',
			'excerpt' => 'Off-the-shelf plugins solve 80% of problems. For the other 20%, a lightweight custom solution is cleaner, safer, and easier to maintain.',
		],
		[
			'title'   => 'API integrations turn stores into real business systems',
			'excerpt' => 'Connecting WooCommerce to shipping, inventory, payments, and reporting tools transforms a store into a reliable operational platform.',
		],
	];
}

// ── SOCIAL LINKS ───────────────────────────────────────────────────────────────
// Reads from Appearance → Customize → Portfolio Settings → Social Links.

function yg_social_links(): array {
	return [
		'linkedin' => get_theme_mod( 'yg_social_linkedin', 'https://linkedin.com/in/your-profile' ),
		'github'   => get_theme_mod( 'yg_social_github',   'https://github.com/your-profile' ),
		'contra'   => get_theme_mod( 'yg_social_contra',   'https://contra.com/your-profile' ),
		'email'    => get_theme_mod( 'yg_social_email',    'mailto:yariel.gordillo@gmail.com' ),
	];
}
