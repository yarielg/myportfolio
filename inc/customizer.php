<?php
/**
 * Customizer settings — Portfolio Settings panel.
 * Appearance → Customize → Portfolio Settings
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function yg_customizer_register( WP_Customize_Manager $wp_customize ): void {

	// ── PANEL ──────────────────────────────────────────────────────────────────
	$wp_customize->add_panel( 'yg_portfolio', [
		'title'    => 'Portfolio Settings',
		'priority' => 30,
	] );

	// ── SECTION: PROFILE ───────────────────────────────────────────────────────
	$wp_customize->add_section( 'yg_profile', [
		'title'    => 'Profile',
		'panel'    => 'yg_portfolio',
		'priority' => 10,
	] );

	$wp_customize->add_setting( 'yg_profile_photo', [
		'default'           => '',
		'sanitize_callback' => 'esc_url_raw',
	] );
	$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'yg_profile_photo', [
		'label'       => 'Profile Photo',
		'description' => 'Appears next to your name in the header. Recommended: square, at least 120×120 px.',
		'section'     => 'yg_profile',
	] ) );

	$wp_customize->add_setting( 'yg_display_name', [
		'default'           => 'Yariel Gordillo',
		'sanitize_callback' => 'sanitize_text_field',
		'transport'         => 'postMessage',
	] );
	$wp_customize->add_control( 'yg_display_name', [
		'label'   => 'Display Name',
		'section' => 'yg_profile',
		'type'    => 'text',
	] );

	$wp_customize->add_setting( 'yg_display_role', [
		'default'           => 'WooCommerce & WordPress Developer',
		'sanitize_callback' => 'sanitize_text_field',
		'transport'         => 'postMessage',
	] );
	$wp_customize->add_control( 'yg_display_role', [
		'label'       => 'Role / Title',
		'description' => 'Short line shown under your name in the header.',
		'section'     => 'yg_profile',
		'type'        => 'text',
	] );

	// ── SECTION: SOCIAL LINKS ──────────────────────────────────────────────────
	$wp_customize->add_section( 'yg_social', [
		'title'       => 'Social Links',
		'description' => 'These appear in the header CTA, contact section, and footer.',
		'panel'       => 'yg_portfolio',
		'priority'    => 20,
	] );

	$social_fields = [
		'yg_social_linkedin' => [
			'label'   => 'LinkedIn URL',
			'default' => 'https://linkedin.com/in/your-profile',
		],
		'yg_social_github'   => [
			'label'   => 'GitHub URL',
			'default' => 'https://github.com/yarielg',
		],
		'yg_wporg_profile'   => [
			'label'       => 'WordPress.org Profile URL',
			'default'     => 'https://profiles.wordpress.org/yariko0529/',
			'description' => 'Shown in the Verified Work section as proof of published plugins.',
		],
		'yg_social_email'    => [
			'label'       => 'Email (include mailto:)',
			'default'     => 'mailto:yariel.gordillo@gmail.com',
			'description' => 'Format: mailto:you@example.com',
		],
	];

	foreach ( $social_fields as $key => $args ) {
		$wp_customize->add_setting( $key, [
			'default'           => $args['default'],
			'sanitize_callback' => 'esc_url_raw',
		] );
		$wp_customize->add_control( $key, [
			'label'       => $args['label'],
			'description' => $args['description'] ?? '',
			'section'     => 'yg_social',
			'type'        => 'url',
		] );
	}

	// ── SECTION: HERO ──────────────────────────────────────────────────────────
	$wp_customize->add_section( 'yg_hero', [
		'title'    => 'Hero Section',
		'panel'    => 'yg_portfolio',
		'priority' => 30,
	] );

	$wp_customize->add_setting( 'yg_open_to_work', [
		'default'           => true,
		'sanitize_callback' => 'wp_validate_boolean',
	] );
	$wp_customize->add_control( 'yg_open_to_work', [
		'label'       => 'Show "Open to work" badge',
		'description' => 'Displays a green availability badge at the top of the hero. Turn off once you land a role.',
		'section'     => 'yg_hero',
		'type'        => 'checkbox',
	] );

	$wp_customize->add_setting( 'yg_hero_headline', [
		'default'           => 'Senior WooCommerce & PHP Developer',
		'sanitize_callback' => 'sanitize_text_field',
		'transport'         => 'postMessage',
	] );
	$wp_customize->add_control( 'yg_hero_headline', [
		'label'   => 'Headline',
		'section' => 'yg_hero',
		'type'    => 'text',
	] );

	$wp_customize->add_setting( 'yg_hero_sub', [
		'default'           => 'I build and maintain the WooCommerce, WordPress, and PHP systems e-commerce businesses run on — checkout, integrations, performance, and the automation that keeps operations moving.',
		'sanitize_callback' => 'sanitize_textarea_field',
		'transport'         => 'postMessage',
	] );
	$wp_customize->add_control( 'yg_hero_sub', [
		'label'   => 'Subheadline',
		'section' => 'yg_hero',
		'type'    => 'textarea',
	] );

	$wp_customize->add_setting( 'yg_hero_support', [
		'default'           => '12+ years building production WordPress, WooCommerce, and PHP/Laravel systems. Two plugins published on WordPress.org. Now pairing that senior judgment with AI-assisted development to ship reliable solutions faster.',
		'sanitize_callback' => 'sanitize_textarea_field',
		'transport'         => 'postMessage',
	] );
	$wp_customize->add_control( 'yg_hero_support', [
		'label'   => 'Supporting Text',
		'section' => 'yg_hero',
		'type'    => 'textarea',
	] );

	$wp_customize->add_setting( 'yg_resume_url', [
		'default'           => '',
		'sanitize_callback' => 'esc_url_raw',
	] );
	$wp_customize->add_control( 'yg_resume_url', [
		'label'       => 'Résumé / CV URL',
		'description' => 'Upload your résumé PDF to Media Library, then paste its URL here. A "Download Résumé" button appears in the hero once set.',
		'section'     => 'yg_hero',
		'type'        => 'url',
	] );

	$wp_customize->add_setting( 'yg_hero_photo', [
		'default'           => '',
		'sanitize_callback' => 'esc_url_raw',
	] );
	$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'yg_hero_photo', [
		'label'       => 'Hero Portrait',
		'description' => 'Optional large photo shown beside the hero text. Recommended: portrait ~4:5 (e.g. 840×1050 px). The hero switches to a two-column layout when set. (Or commit the file to the theme at assets/images/hero.jpg to deploy it as code.)',
		'section'     => 'yg_hero',
	] ) );
	// ── SECTION: RECAPTCHA ────────────────────────────────────────────────────────
	$wp_customize->add_section( 'yg_recaptcha', [
		'title'       => 'reCAPTCHA v3',
		'description' => 'Google reCAPTCHA v3 protects the contact form. Get your keys at <a href="https://www.google.com/recaptcha/admin" target="_blank" rel="noopener">google.com/recaptcha/admin</a> — create a v3 site and add your domain.',
		'panel'       => 'yg_portfolio',
		'priority'    => 40,
	] );

	$wp_customize->add_setting( 'yg_recaptcha_site_key', [
		'default'           => '',
		'sanitize_callback' => 'sanitize_text_field',
	] );
	$wp_customize->add_control( 'yg_recaptcha_site_key', [
		'label'       => 'Site Key (public)',
		'description' => 'Paste the Site Key from your reCAPTCHA dashboard.',
		'section'     => 'yg_recaptcha',
		'type'        => 'text',
	] );

	$wp_customize->add_setting( 'yg_recaptcha_secret_key', [
		'default'           => '',
		'sanitize_callback' => 'sanitize_text_field',
	] );
	$wp_customize->add_control( 'yg_recaptcha_secret_key', [
		'label'       => 'Secret Key (private)',
		'description' => 'Paste the Secret Key from your reCAPTCHA dashboard. Stored in the database — do not share.',
		'section'     => 'yg_recaptcha',
		'type'        => 'text',
	] );
}
add_action( 'customize_register', 'yg_customizer_register' );
