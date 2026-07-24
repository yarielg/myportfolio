<?php
/**
 * CONTENT PROVISIONER — portfolio case studies + skills (DB content, as code).
 *
 * This is how database content reaches the live site with a code-only deploy:
 * push the theme files, then load any wp-admin page once — this runs and
 * idempotently provisions the portfolio CPT and the Skills CPT/taxonomy so the
 * live database matches. No SQL dumps, no export/import.
 *
 * Re-run after changing content, either way:
 *   1. Bump YG_PROVISION_FLAG below (…_v5 → …_v6), or
 *   2. As a logged-in admin, load any admin page with ?yg_reprovision=1
 *
 * Idempotent and safe to run repeatedly. Keep this file — it is the deploy tool.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

const YG_PROVISION_FLAG = 'yg_portfolio_seeded_v6';

add_action( 'admin_init', 'yg_run_portfolio_seeder' );

function yg_run_portfolio_seeder(): void {
	// Manual re-run for admins: /wp-admin/?yg_reprovision=1
	if ( isset( $_GET['yg_reprovision'] ) && current_user_can( 'manage_options' ) ) {
		delete_option( YG_PROVISION_FLAG );
	}

	if ( get_option( YG_PROVISION_FLAG ) ) {
		return;
	}

	// ── portfolio_skill taxonomy terms ────────────────────────────────────────

	$skill_terms = [
		'WooCommerce', 'WordPress', 'PHP', 'MySQL', 'REST API', 'Webhooks',
		'Performance Optimization', 'Redis', 'Query Monitor', 'Caching',
		'Custom Plugin', 'WP CLI', 'Action Scheduler', 'CSV Import/Export',
		'Automation', 'ERP Integration', 'Inventory Sync', 'XML Feeds',
		'WooCommerce Subscriptions', 'Payment Recovery', 'Dunning Workflow',
		'Real-time Pricing', 'ACF', 'Google Analytics API', 'PageSpeed API',
		'JavaScript', 'AJAX', 'Slack API', 'AI Integration', 'Licensing',
		'SEO', 'Search Console', 'Data Analysis', 'KPI Reporting',
		'E-commerce Operations', 'Vue.js', 'PDF Generation', 'OOP',
	];

	foreach ( $skill_terms as $term ) {
		if ( ! term_exists( $term, 'portfolio_skill' ) ) {
			wp_insert_term( $term, 'portfolio_skill' );
		}
	}

	// ── Remove stale duplicates & retired projects ─────────────────────────────
	// Old v1 seeder used different slugs; the current canonical versions replaced
	// them, leaving duplicates. Growth/SEO dashboard was retired (SEO is now a skill).
	$remove_slugs = [
		'custom-dealer-csv-export-tool',       // dup of custom-dealer-csv
		'inventory-pricing-api-integration',   // dup of inventory-pricing-api
		'subscription-payment-recovery-flow',  // dup of subscription-payment-recovery
		'growth-seo-dashboard',                // retired — SEO reflected as a skill instead
	];
	foreach ( $remove_slugs as $slug ) {
		$stale = get_page_by_path( $slug, OBJECT, 'portfolio' );
		if ( $stale ) {
			wp_delete_post( $stale->ID, true );
		}
	}

	// ── Skills: groups + skills (idempotent, create-if-missing) ────────────────
	// Full skills provisioning so the Skills section reaches live via code.
	// Groups are created in this order (drives display order). SEO & Growth is
	// intentionally last (secondary skill).
	$skill_groups = [
		'E-commerce'      => [ 'WooCommerce', 'WooCommerce Subscriptions', 'Checkout Flows', 'Product Catalogs', 'Dealer / Wholesale Workflows', 'HPOS', 'Payment Workflows' ],
		'WordPress / PHP' => [ 'Custom Plugins', 'Custom Themes', 'Hooks & Filters', 'Shortcodes', 'CPTs', 'Admin Tools', 'PHP 8+', 'MySQL' ],
		'Integrations'    => [ 'REST APIs', 'Webhooks', 'Stripe', 'Square', 'ShipStation', 'Avalara', 'QuickBooks', 'ERP / CRM Systems', 'Inventory Systems' ],
		'Performance'     => [ 'Query Monitor', 'Caching Strategies', 'Cloudflare', 'NitroPack', 'Database Optimization', 'Cron / Scheduled Actions', 'Plugin Conflict Debugging' ],
		'Automation'      => [ 'CSV Exports', 'Scheduled Tasks', 'Reporting Workflows', 'AI-Assisted Workflows', 'Internal Admin Tools' ],
		'Frontend'        => [ 'HTML', 'CSS', 'JavaScript', 'Responsive Design', 'UX/UI Implementation', 'Accessibility Basics' ],
		'SEO & Growth'    => [ 'Technical SEO', 'Search Console', 'GA4 Analytics', 'KPI Dashboards', 'Conversion Optimization', 'Store Operations' ],
	];
	$skill_order = 0;
	foreach ( $skill_groups as $group_name => $group_skills ) {
		$group = term_exists( $group_name, 'skill_group' );
		if ( ! $group ) {
			$group = wp_insert_term( $group_name, 'skill_group' );
		}
		if ( is_wp_error( $group ) ) {
			continue;
		}
		$group_id = is_array( $group ) ? (int) $group['term_id'] : (int) $group;
		foreach ( $group_skills as $skill_name ) {
			$skill_order++;
			if ( get_page_by_path( sanitize_title( $skill_name ), OBJECT, 'yg_skill' ) ) {
				continue;
			}
			$sid = wp_insert_post( [
				'post_title'  => $skill_name,
				'post_type'   => 'yg_skill',
				'post_status' => 'publish',
				'menu_order'  => $skill_order,
			] );
			if ( $sid && ! is_wp_error( $sid ) ) {
				wp_set_object_terms( $sid, $group_id, 'skill_group' );
			}
		}
	}

	// ── Helper: insert or update project ─────────────────────────────────────

	$upsert = function ( array $p ): void {
		$existing = get_page_by_path( $p['slug'], OBJECT, 'portfolio' );

		$core = [
			'post_title'   => $p['title'],
			'post_name'    => $p['slug'],
			'post_type'    => 'portfolio',
			'post_status'  => 'publish',
			'menu_order'   => $p['order'] ?? 0,
			'post_excerpt' => $p['excerpt'] ?? '',
		];

		if ( $existing ) {
			// Update core fields too, so reframes (title, order, excerpt) take effect on re-run.
			$core['ID'] = $existing->ID;
			$pid = wp_update_post( $core, true );
		} else {
			$pid = wp_insert_post( $core, true );
		}

		if ( is_wp_error( $pid ) || ! $pid ) {
			return;
		}

		foreach ( $p['meta'] as $key => $val ) {
			update_post_meta( $pid, $key, $val );
		}

		if ( ! empty( $p['skills'] ) ) {
			wp_set_object_terms( $pid, $p['skills'], 'portfolio_skill' );
		}
	};

	// ── Projects ─────────────────────────────────────────────────────────────

	$projects = [

		// ── 1. Samurai Fireworks ─────────────────────────────────────────────
		[
			'title'   => 'Samurai Fireworks',
			'slug'    => 'samurai-fireworks',
			'order'   => 1,
			'excerpt' => 'WooCommerce performance audit and checkout optimization for a high-traffic fireworks retailer.',
			'skills'  => [
				'WooCommerce', 'PHP', 'MySQL', 'Performance Optimization',
				'Redis', 'Query Monitor', 'Caching', 'Action Scheduler',
			],
			'meta' => [
				'_portfolio_subtitle'        => 'WooCommerce Performance Audit & Checkout Optimization',
				'_portfolio_type'            => 'WooCommerce Store Audit & Optimization',
				'_portfolio_client'          => 'Samurai Fireworks',
				'_portfolio_year'            => '2024',
				'_portfolio_role'            => 'WooCommerce Developer',
				'_portfolio_url'             => 'https://samuraifireworks.com',
				'_portfolio_specialties'     => [ 'audit', 'performance' ],
				'_portfolio_accent_color'    => '#f59e0b',
				'_portfolio_confidential'    => '',
				'_portfolio_anonymous'       => '',
				'_portfolio_cta_label'       => 'Discuss Your Store',
				'_portfolio_cta_url'         => '',
				'_portfolio_gallery_urls'    => '',
				'_portfolio_summary'         => 'Diagnosed and resolved critical WooCommerce performance issues causing high checkout abandonment at a seasonal high-traffic fireworks retailer — cutting page load times from 8 seconds to under 2.',
				'_portfolio_problem'         => "Samurai Fireworks sees its highest traffic during the July 4th season, but checkout was painfully slow — sometimes 8–10 seconds to load the cart and payment pages. Customers were abandoning before completing purchase. The store ran dozens of plugins with no object caching, and the WooCommerce admin was unusably slow during peak days.",
				'_portfolio_solution'        => "I performed a full WooCommerce audit using Query Monitor and server logs. I found N+1 queries in the cart session handler, unoptimized product meta queries, and blocking external API calls during checkout. I implemented Redis object caching via wp-redis, rewrote the problematic cart hooks, deferred non-critical external calls to WooCommerce's action scheduler queue, and removed conflicting plugin combinations.",
				'_portfolio_value'           => "Checkout page load dropped from roughly 8 seconds to under 2, and completed orders improved noticeably in the first peak season after the fix. The WooCommerce admin became usable again and the team could manage products during peak traffic without timeouts.",
				'_portfolio_outcomes'        => "Checkout page load cut from ~8s to under 2s\nNoticeable lift in completed orders during the first post-fix peak season\nWooCommerce admin fully usable during peak traffic\nRedis object caching substantially reduced database query load\nEliminated timeout errors during July 4th peak days",
				'_portfolio_tech_highlights' => "Query Monitor profiling to identify N+1 query patterns\nRedis object caching with wp-redis drop-in\nCustom action scheduler jobs for post-checkout API calls\nHook-level deferral of non-critical checkout processes\nWooCommerce session handler and cart query optimization\nPlugin conflict audit and resolution",
			],
		],

		// ── 2. Our Tiny Garden ───────────────────────────────────────────────
		[
			'title'   => 'Our Tiny Garden',
			'slug'    => 'our-tiny-garden',
			'order'   => 2,
			'excerpt' => 'Custom WordPress plugin to automate inventory sync from supplier CSV feeds for a WooCommerce plant store.',
			'skills'  => [
				'WooCommerce', 'PHP', 'Custom Plugin', 'CSV Import/Export',
				'Automation', 'WP CLI', 'Action Scheduler',
			],
			'meta' => [
				'_portfolio_subtitle'        => 'Automated Inventory Sync from Supplier CSV Feeds',
				'_portfolio_type'            => 'E-commerce Automation Workflows',
				'_portfolio_client'          => 'Our Tiny Garden',
				'_portfolio_year'            => '2024',
				'_portfolio_role'            => 'WordPress Plugin Developer',
				'_portfolio_url'             => 'https://ourtinygarden.com',
				'_portfolio_specialties'     => [ 'plugin', 'automation' ],
				'_portfolio_accent_color'    => '#10b981',
				'_portfolio_confidential'    => '',
				'_portfolio_anonymous'       => '',
				'_portfolio_cta_label'       => 'Automate Your Store',
				'_portfolio_cta_url'         => '',
				'_portfolio_gallery_urls'    => '',
				'_portfolio_summary'         => 'Built a custom WooCommerce plugin that automatically syncs product inventory from supplier CSV feeds — eliminating hours of daily manual data entry and largely eliminating overselling.',
				'_portfolio_problem'         => "The Our Tiny Garden team received CSV files from their plant supplier each morning with updated stock counts and pricing. A staff member spent 2–3 hours every morning manually updating each product in WooCommerce. Errors were common — overselling out-of-stock plants was a regular occurrence, leading to customer complaints and refunds.",
				'_portfolio_solution'        => "I built a custom WordPress plugin that automatically fetches the supplier's CSV feed via FTP on a scheduled WP Cron job. The plugin parses the CSV, maps supplier SKUs to WooCommerce product IDs, updates stock quantities and prices, logs any unmapped SKUs for review, and sends a daily digest email to the store owner summarizing all changes made.",
				'_portfolio_value'           => "The daily manual process was fully automated. Staff no longer spend time on inventory data entry. Overselling dropped sharply. The store owner gets a daily email showing exactly what changed — and can override any sync result directly in the WooCommerce product editor.",
				'_portfolio_outcomes'        => "Hours of daily manual data entry eliminated\nOverselling incidents dropped sharply\nInventory accuracy improved substantially\nDaily digest email gives the owner full visibility into sync activity\nUnmapped-SKU detection prevents silent inventory gaps",
				'_portfolio_tech_highlights' => "Custom WordPress plugin with admin settings UI\nWP Cron job with configurable FTP source and run schedule\nCSV parser with SKU-to-product-ID mapping table\nChange detection: only updates stock and price when values differ\nWP action scheduler for background batch processing on large catalogs\nAdmin digest email with full per-product change log",
			],
		],

		// ── 3. WebReadyNow (my own product & platform) ───────────────────────
		[
			'title'   => 'WebReadyNow — My WooCommerce Product Platform',
			'slug'    => 'webready-now',
			'order'   => 9,
			'excerpt' => 'My own WooCommerce agency and product platform — where I design, build, license, update, and document the commercial WordPress plugins I ship.',
			'skills'  => [
				'WordPress', 'PHP', 'WooCommerce', 'REST API', 'Custom Plugin',
				'Licensing', 'WooCommerce Subscriptions', 'Automation',
			],
			'meta' => [
				'_portfolio_subtitle'        => 'A Self-Built Platform to License, Update & Document My Commercial Plugins',
				'_portfolio_type'            => 'Product Development & Platform Engineering',
				'_portfolio_client'          => 'WebReadyNow (my own product)',
				'_portfolio_year'            => '2023 – Present',
				'_portfolio_role'            => 'Founder & Lead Developer',
				'_portfolio_url'             => 'https://webreadynow.com',
				'_portfolio_specialties'     => [ 'plugin', 'api' ],
				'_portfolio_accent_color'    => '#3b82f6',
				'_portfolio_confidential'    => '',
				'_portfolio_anonymous'       => '',
				'_portfolio_cta_label'       => 'Build a WooCommerce Product',
				'_portfolio_cta_url'         => '',
				'_portfolio_gallery_urls'    => '',
				'_portfolio_summary'         => "WebReadyNow is my own WooCommerce agency and product platform. I designed and built the entire system that packages, licenses, updates, and documents the commercial WordPress plugins I sell — the same infrastructure now running two plugins published on WordPress.org.",
				'_portfolio_problem'         => "Selling commercial WordPress plugins takes far more than the plugin code. License keys have to be issued and validated, paid updates delivered securely through the native WordPress update screen, documentation published and searchable, and customer accounts tied to their subscriptions. Off-the-shelf licensing services were expensive, bloated, or a poor fit for how I wanted to run my own products — I needed a platform I fully controlled.",
				'_portfolio_solution'        => "I built WebReadyNow as an integrated WordPress/WooCommerce platform with my own licensing and delivery system (WRN Hub) at its core. It issues and validates license keys, delivers license-authenticated plugin updates through the standard WordPress updates screen, applies a grace period when the license server is briefly unreachable, and serves public, searchable product documentation. License status syncs with WooCommerce Subscriptions, and customers manage their keys and downloads from their own account area.",
				'_portfolio_value'           => "I own the full product pipeline end to end — from writing the plugin to selling, licensing, updating, documenting, and supporting it. This platform is what let me take products from code to publicly available, paid, and documented, and it runs my two WordPress.org plugins in production today.",
				'_portfolio_outcomes'        => "Two commercial plugins taken from code to published, licensed, and documented\nSelf-built license issuance with daily validation and a grace-period fallback\nLicense-authenticated updates delivered through the native WordPress update screen\nPublic, searchable product documentation for each plugin\nLicense status synced with WooCommerce Subscriptions and a customer account area\nRuns as production infrastructure I built and operate solo",
				'_portfolio_tech_highlights' => "Custom WordPress plugin (WRN Hub) for license issuance, validation, and update delivery\nLicense-authenticated update endpoints integrated with the native WP update system\nGrace-period handling when the license server is temporarily unreachable\nWooCommerce Subscriptions integration for the full license lifecycle\nPublic, searchable documentation system for every product\nCustomer account area for license keys and authenticated downloads",
			],
		],

		// ── 3b. WRN Store Monitor (my own published product) ─────────────────
		[
			'title'   => 'WRN Store Monitor',
			'slug'    => 'wrn-store-monitor',
			'order'   => 3,
			'excerpt' => 'A WooCommerce monitoring plugin I built and published on WordPress.org — detects operational issues across orders, payments, and checkout on one health dashboard.',
			'skills'  => [
				'WooCommerce', 'PHP', 'Custom Plugin', 'Action Scheduler',
				'REST API', 'Automation', 'Slack API', 'AI Integration', 'MySQL',
			],
			'meta' => [
				'_portfolio_subtitle'        => 'WooCommerce Operational Monitoring, Alerting & AI-Assisted Diagnosis',
				'_portfolio_type'            => 'Custom WordPress Plugin Development',
				'_portfolio_client'          => 'WebReadyNow (my own product)',
				'_portfolio_year'            => '2024 – Present',
				'_portfolio_role'            => 'Founder & Lead Developer',
				'_portfolio_url'             => 'https://wordpress.org/plugins/wrn-store-monitor-lite/',
				'_portfolio_specialties'     => [ 'plugin', 'automation' ],
				'_portfolio_accent_color'    => '#06b6d4',
				'_portfolio_confidential'    => '',
				'_portfolio_anonymous'       => '',
				'_portfolio_cta_label'       => 'Monitor Your Store',
				'_portfolio_cta_url'         => 'https://wordpress.org/plugins/wrn-store-monitor-lite/',
				'_portfolio_gallery_urls'    => '',
				'_portfolio_summary'         => "WRN Store Monitor is a WooCommerce monitoring plugin I designed, built, and published to WordPress.org. It helps store owners detect operational problems earlier — failed and stuck orders, payment failure patterns, checkout configuration issues, inventory gaps, and environment risks — and surfaces them on a single health dashboard with alerting and AI-assisted triage.",
				'_portfolio_problem'         => "Most WooCommerce problems happen silently in the background — a payment gateway misconfigured, orders stuck in processing, checkout throwing errors only customers see. Standard uptime monitors report that the homepage is \"up\" while the store is quietly losing orders, and owners often find out days later from customer complaints. There was no lightweight, WooCommerce-aware way to catch these operational signals early.",
				'_portfolio_solution'        => "I built WRN Store Monitor to run WooCommerce-aware health scans on a schedule. It checks order flow, payment failures, checkout and cart availability, WooCommerce AJAX health, order-received endpoints, inventory, and environment diagnostics (PHP/WooCommerce/WordPress versions, active gateways, HPOS). It logs diagnostic events with timestamps, stores a snapshot of evidence on every scan, sends email and Slack alerts when configured thresholds are crossed, and logs every alert delivery attempt so owners can verify what was sent. A Pro tier adds continuous checkout probing and AI-assisted triage using the store's own Anthropic key, with human verification always required.",
				'_portfolio_value'           => "Store owners get early, WooCommerce-aware visibility into operational issues instead of hearing about them from frustrated customers. Alerts and a diagnostic timeline reduce the time it takes to understand what changed and when. The Lite version is free and published on WordPress.org; the Pro version powers WebReadyNow's managed monitoring work.",
				'_portfolio_outcomes'        => "Published free on WordPress.org after passing plugin review\nDetects failed/stuck orders, payment failure patterns, and checkout/inventory issues from one dashboard\nEmail and Slack alerts fire when configured thresholds are crossed\nEvery alert delivery attempt logged so owners can verify what was sent and when\nIncident timeline records order status changes, payment events, checkout errors, and scan evidence\nPro tier adds continuous checkout probing and AI-assisted triage (human verification required)",
				'_portfolio_tech_highlights' => "Scheduled WooCommerce health scans via Action Scheduler\nCheckout, cart, and WooCommerce-AJAX availability probes\nEmail and Slack alerting with per-alert delivery logging in a custom DB table\nDiagnostic evidence layer: per-scan snapshot of severity, issue counts, and an environment fingerprint\nIncident timeline aggregating orders, payments, checkout errors, and alerts\nAI-assisted triage via the Anthropic API (bring-your-own key), human verification required\nHPOS-compatible; licensing and updates delivered through my own WRN Hub platform",
			],
		],

		// ── 3d. Custom Payroll Management App (Vue SPA + WP, behavioral clinic) ──
		[
			'title'   => 'Custom Payroll Management App',
			'slug'    => 'mhc-payroll-app',
			'order'   => 4,
			'excerpt' => 'A Vue.js single-page app on WordPress that manages patients, workers, and role-based rates, and generates detailed PDF pay stubs and payroll reports for a behavioral health clinic.',
			'skills'  => [
				'Vue.js', 'PHP', 'WordPress', 'MySQL', 'Custom Plugin',
				'OOP', 'AJAX', 'PDF Generation',
			],
			'meta' => [
				'_portfolio_subtitle'        => 'A Vue SPA + WordPress Payroll System for a Behavioral Health Clinic',
				'_portfolio_type'            => 'Custom WordPress Application',
				'_portfolio_client'          => 'Behavioral Health Clinic',
				'_portfolio_year'            => '2024',
				'_portfolio_role'            => 'Full-Stack WordPress Developer',
				'_portfolio_url'             => '',
				'_portfolio_specialties'     => [ 'plugin', 'api' ],
				'_portfolio_accent_color'    => '#0ea5e9',
				'_portfolio_confidential'    => '1',
				'_portfolio_anonymous'       => '1',
				'_portfolio_cta_label'       => 'Build a Custom App',
				'_portfolio_cta_url'         => '',
				'_portfolio_gallery_urls'    => '',
				'_portfolio_summary'         => "Designed and built a custom payroll management application for a behavioral health clinic — a Vue.js single-page app embedded in WordPress that manages patients, workers, and role-based rates, and generates detailed PDF pay stubs and payroll reports.",
				'_portfolio_problem'         => "A clinic serving children with autism pays technicians (RBTs), intermediates (BCaBAs), and analysts (BCBAs) based on hours worked with each patient — at rates that vary by role, by patient, and by activity (assessments, supervision), plus one-off bonuses and deductions. Payroll was a slow, error-prone spreadsheet process, and every mistake directly affected staff pay.",
				'_portfolio_solution'        => "I built a custom WordPress plugin with a Vue.js single-page-app frontend and an object-oriented PHP backend (PSR-4, MVC controllers and models, a custom database schema with versioned upgrades). Administrators manage patients, workers, roles, and general / per-patient / special rates, enter hours, and apply extra payments or deductions. The app calculates each worker's pay by category across a payroll cycle — handling edge cases like fixed-rate activities and workers who skip a cycle — then generates individual PDF pay stubs via mPDF plus a global payroll report for reconciliation with accounting. Every action runs through an AJAX API gated by nonces and capabilities, behind a custom role-based login so staff can use it securely from anywhere.",
				'_portfolio_value'           => "A manual, error-prone spreadsheet process became a few-clicks workflow. Pay calculations are consistent and auditable, PDF pay stubs are produced automatically with per-category breakdowns, and the global report lets the clinic reconcile payroll against accounting quickly — cutting manual work and the payroll mistakes that hit staff pay directly.",
				'_portfolio_outcomes'        => "Manual spreadsheet payroll replaced with a few-clicks web app\nPer-worker pay calculated automatically across roles, patients, and activity rates\nIndividual PDF pay stubs generated with detailed per-category breakdowns\nGlobal payroll report for fast reconciliation with accounting\nHandles edge cases: fixed-rate activities, per-patient rates, skipped cycles, bonuses and deductions\nRole-based access with a custom login for secure remote use",
				'_portfolio_tech_highlights' => "Vue.js single-page app embedded in WordPress via shortcodes (Vite build)\nObject-oriented PHP backend — PSR-4 autoloading, MVC controllers/models, custom DB schema with versioned upgrades\nAJAX API with nonce and capability checks on every action\nPayroll engine: role / patient / special rates, extra payments, deductions, and payroll segments\nPDF pay-stub and report generation with mPDF (UTF-8, logos, signatures)\nRole-based access control and a custom WordPress login flow",
			],
		],

		// ── 4. Beghelli USA ──────────────────────────────────────────────────
		[
			'title'   => 'Beghelli USA',
			'slug'    => 'beghelli-usa',
			'order'   => 5,
			'excerpt' => "Bi-directional ERP–WooCommerce sync for a commercial lighting manufacturer's B2B e-commerce store.",
			'skills'  => [
				'WooCommerce', 'PHP', 'REST API', 'ERP Integration',
				'Inventory Sync', 'XML Feeds', 'Automation', 'Custom Plugin',
			],
			'meta' => [
				'_portfolio_subtitle'        => 'ERP–WooCommerce Bi-Directional Inventory & Order Sync',
				'_portfolio_type'            => 'WooCommerce API Integrations',
				'_portfolio_client'          => 'Beghelli USA',
				'_portfolio_year'            => '2023',
				'_portfolio_role'            => 'WooCommerce Integration Developer',
				'_portfolio_url'             => '',
				'_portfolio_specialties'     => [ 'api', 'automation' ],
				'_portfolio_accent_color'    => '#d97706',
				'_portfolio_confidential'    => '',
				'_portfolio_anonymous'       => '',
				'_portfolio_cta_label'       => 'Discuss ERP Integration',
				'_portfolio_cta_url'         => '',
				'_portfolio_gallery_urls'    => '',
				'_portfolio_summary'         => "Built a reliable bi-directional sync between WooCommerce and Beghelli USA's ERP system — keeping product data, inventory counts, and order statuses accurate across both platforms without manual data entry.",
				'_portfolio_problem'         => "Beghelli USA runs a B2B WooCommerce store for commercial lighting products alongside their internal ERP system. Product data, pricing, and inventory lived in the ERP — but staff had to manually re-enter changes in WooCommerce. Orders placed in WooCommerce also needed manual ERP entry. The two systems were chronically out of sync, causing order errors and overselling.",
				'_portfolio_solution'        => "I built a custom WordPress plugin that acts as a sync bridge between their ERP's XML API and WooCommerce. Products and inventory flow from ERP to WooCommerce on a scheduled 15-minute sync. New orders flow from WooCommerce to ERP immediately via webhook. The plugin includes an admin log panel to review all sync events, flag errors, and manually trigger re-syncs for specific products.",
				'_portfolio_value'           => "Manual data entry between systems was fully eliminated. Product catalog accuracy improved dramatically. New orders appear in the ERP within seconds of being placed. The admin team now uses the WooCommerce dashboard as their primary interface with confidence that ERP data stays in sync.",
				'_portfolio_outcomes'        => "Manual product and order data entry between systems eliminated\nProduct catalog accuracy improved dramatically\nNew orders sync to ERP within seconds via webhook\nAdmin team adopted WooCommerce as their primary order-management interface\nSync errors became rare, handled automatically by retry logic",
				'_portfolio_tech_highlights' => "Custom WordPress sync plugin with admin log panel\nERP XML API integration with scheduled 15-minute pull\nWooCommerce order webhook for real-time ERP push\nConflict detection with manual override capability per product\nRetry queue for failed sync events with exponential backoff\nDelta sync — only changed records processed to minimize API load",
			],
		],

		// ── 5. Custom Dealer CSV ─────────────────────────────────────────────
		[
			'title'   => 'Custom Dealer Vehicle CSV Importer',
			'slug'    => 'custom-dealer-csv',
			'order'   => 6,
			'excerpt' => 'Automated daily vehicle inventory importer from dealer management system CSV feeds for a WooCommerce dealership site.',
			'skills'  => [
				'WooCommerce', 'PHP', 'CSV Import/Export', 'Custom Plugin',
				'Automation', 'WP CLI', 'Action Scheduler',
			],
			'meta' => [
				'_portfolio_subtitle'        => 'Automated Daily Vehicle Inventory Import from Dealer Feed CSV',
				'_portfolio_type'            => 'E-commerce Automation Workflows',
				'_portfolio_client'          => 'Private Auto Dealership',
				'_portfolio_year'            => '2023',
				'_portfolio_role'            => 'WordPress Plugin Developer',
				'_portfolio_url'             => '',
				'_portfolio_specialties'     => [ 'automation', 'plugin' ],
				'_portfolio_accent_color'    => '#14b8a6',
				'_portfolio_confidential'    => '1',
				'_portfolio_anonymous'       => '1',
				'_portfolio_cta_label'       => 'Automate Your Inventory',
				'_portfolio_cta_url'         => '',
				'_portfolio_gallery_urls'    => '',
				'_portfolio_summary'         => "Built an automated WooCommerce vehicle inventory importer that processes daily CSV feeds from the dealer management system — keeping the public listing site accurate without any manual effort.",
				'_portfolio_problem'         => "An auto dealership's WooCommerce website listed their vehicle inventory. The dealer management system (DMS) exported a full inventory CSV daily. Staff had to manually import, update, and remove vehicles — a 2-hour process every morning. Vehicles that sold were still showing online for days. New arrivals took days to appear after arriving on the lot.",
				'_portfolio_solution'        => "I built a custom WP plugin that fetches the daily CSV from a secure FTP server, parses vehicle data (VIN, year, make, model, trim, price, mileage, status), and performs a full diff against existing WooCommerce products. New vehicles are created, updated vehicles get refreshed metadata, and sold/unavailable vehicles are set to draft. Vehicle images are downloaded and attached to the product gallery on first import.",
				'_portfolio_value'           => "The manual morning process was fully replaced. The site now reflects accurate inventory by 7am daily, automatically. Sold vehicles come down within hours of status change in the DMS. The team can focus on selling rather than data entry.",
				'_portfolio_outcomes'        => "2 hours of daily manual work eliminated\nInventory accuracy improved from days-old to same-day\nSold vehicles removed from site automatically within hours\nNew arrivals appear online the same day they enter the DMS\nVIN-based duplicate detection prevents duplicate listings",
				'_portfolio_tech_highlights' => "WP CLI command for on-demand import runs during testing\nWP Cron scheduled at 6am daily with configurable time via settings\nFull diff engine: create / update / archive based on VIN comparison\nImage downloader with attachment creation and deduplication\nCSV column mapping UI for non-technical admin configuration\nImport log with per-vehicle status tracking and error reporting",
			],
		],

		// ── 6. Real-Time Inventory & Pricing API ─────────────────────────────
		[
			'title'   => 'Real-Time Inventory & Pricing API',
			'slug'    => 'inventory-pricing-api',
			'order'   => 7,
			'excerpt' => "Custom WooCommerce REST API endpoints for real-time inventory and pricing from a wholesale distributor's ERP.",
			'skills'  => [
				'WooCommerce', 'REST API', 'PHP', 'Webhooks',
				'Real-time Pricing', 'ERP Integration', 'Custom Plugin', 'JavaScript', 'AJAX',
			],
			'meta' => [
				'_portfolio_subtitle'        => 'Custom REST API for Real-Time Pricing & Inventory from ERP',
				'_portfolio_type'            => 'WooCommerce API Integrations',
				'_portfolio_client'          => 'Wholesale Distributor',
				'_portfolio_year'            => '2024',
				'_portfolio_role'            => 'WooCommerce API Developer',
				'_portfolio_url'             => '',
				'_portfolio_specialties'     => [ 'api' ],
				'_portfolio_accent_color'    => '#8b5cf6',
				'_portfolio_confidential'    => '1',
				'_portfolio_anonymous'       => '1',
				'_portfolio_cta_label'       => 'Discuss API Integration',
				'_portfolio_cta_url'         => '',
				'_portfolio_gallery_urls'    => '',
				'_portfolio_summary'         => "Built custom WooCommerce REST API endpoints that fetch live pricing and inventory from a wholesale ERP — enabling real-time product data on the storefront without cron-based sync delays.",
				'_portfolio_problem'         => "A wholesale distributor's WooCommerce B2B store had prices and stock levels that changed constantly — sometimes multiple times per day based on supplier costs and warehouse counts. A daily sync wasn't sufficient; customers were adding out-of-stock items to cart or getting prices that had changed by the time they checked out, eroding trust with their B2B buyers.",
				'_portfolio_solution'        => "I built a custom REST API layer in WordPress that proxies pricing and inventory requests to the ERP's internal API. On the WooCommerce storefront, I replaced the static price display and stock status with AJAX calls to these new endpoints. Prices and inventory update live in the browser before checkout. Responses are cached in transients with a 5-minute TTL to avoid hammering the ERP while still showing near-real-time data.",
				'_portfolio_value'           => "Customers now see accurate prices and stock levels at all times. Cart abandonment due to price surprises at checkout dropped significantly. B2B buyer trust and order completion rate improved. Order error rates fell after removing stale pricing from the checkout flow.",
				'_portfolio_outcomes'        => "Price discrepancies at checkout became rare\nOut-of-stock cart additions largely eliminated\nB2B buyer trust and order completion improved\n5-minute price cache balances accuracy and ERP API load\nNo manual pricing intervention needed during supply-cost fluctuations",
				'_portfolio_tech_highlights' => "Custom WP REST API endpoints via register_rest_route()\nERP API proxy with authentication header management and error handling\nTransient caching with 5-minute TTL per SKU\nFront-end AJAX price injection replacing static WooCommerce output\nFallback to WooCommerce stored price on ERP API timeout\nRole-based pricing: different price tiers per WooCommerce customer group",
			],
		],

		// ── 7. Subscription Payment Recovery ────────────────────────────────
		[
			'title'   => 'Subscription Payment Recovery System',
			'slug'    => 'subscription-payment-recovery',
			'order'   => 8,
			'excerpt' => 'Custom WooCommerce dunning workflow for subscription payment failures, reducing involuntary churn by 34%.',
			'skills'  => [
				'WooCommerce', 'WooCommerce Subscriptions', 'PHP',
				'Payment Recovery', 'Dunning Workflow', 'Action Scheduler',
				'Custom Plugin', 'Automation',
			],
			'meta' => [
				'_portfolio_subtitle'        => 'Custom Dunning Workflow for WooCommerce Subscription Renewals',
				'_portfolio_type'            => 'Custom WordPress Plugin Development',
				'_portfolio_client'          => 'SaaS / Membership Business',
				'_portfolio_year'            => '2024',
				'_portfolio_role'            => 'WooCommerce Subscriptions Developer',
				'_portfolio_url'             => '',
				'_portfolio_specialties'     => [ 'plugin', 'automation' ],
				'_portfolio_accent_color'    => '#f43f5e',
				'_portfolio_confidential'    => '1',
				'_portfolio_anonymous'       => '1',
				'_portfolio_cta_label'       => 'Reduce Your Churn',
				'_portfolio_cta_url'         => '',
				'_portfolio_gallery_urls'    => '',
				'_portfolio_summary'         => "Built a custom dunning and payment-recovery workflow for WooCommerce Subscriptions that significantly reduced involuntary churn — through smart retry logic, customer communication sequences, and graceful access management.",
				'_portfolio_problem'         => "A membership site using WooCommerce Subscriptions was losing a meaningful share of its subscribers each month due to failed renewal payments — expired cards, insufficient funds, and temporary bank blocks. WooCommerce's default behavior sent one failure email and then cancelled the subscription. Many of these were recoverable with a smarter retry strategy and customer communication flow.",
				'_portfolio_solution'        => "I built a custom dunning plugin that intercepts WooCommerce Subscriptions' payment failure flow and replaces it with a configurable multi-step recovery workflow. Step 1: silent retry after 24 hours. Step 2: friendly card-update email + retry after 3 days. Step 3: urgency email + retry after 7 days. Step 4: suspend access (not cancel). Step 5: final notice, then cancellation after 14 days. All steps are logged and the admin can override at any step per subscriber.",
				'_portfolio_value'           => "Involuntary churn from failed payments dropped significantly in the first full quarter, recovering meaningful monthly recurring revenue. Subscribers appreciated the grace period, and many updated their payment methods within the first retry window.",
				'_portfolio_outcomes'        => "Involuntary churn from failed payments reduced significantly\nRecovered meaningful monthly recurring revenue\nMost recoveries happened within the first retry window\nMajority of failed payments recovered before access suspension\nFull admin visibility into dunning status per subscriber",
				'_portfolio_tech_highlights' => "Custom dunning plugin built on WooCommerce Subscriptions hooks\nWP Action Scheduler for retry queue management and scheduling\nConfigurable workflow steps with time delays and email templates\nSubscription access management: suspend vs. cancel separation\nAdmin dunning dashboard with per-subscriber status and override controls\nWebhook support for payment gateway retry response handling",
			],
		],
	];

	foreach ( $projects as $project ) {
		$upsert( $project );
	}

	update_option( YG_PROVISION_FLAG, true );
}
