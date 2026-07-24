<?php
/**
 * Front Page Template
 * WordPress uses this file automatically when a static front page is set.
 * Settings > Reading > "A static page" > Front page: any page
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>

<main id="main-content" role="main">
	<?php
	/*
	 * Order is recruiter-first: who/level → verifiable proof → work history →
	 * skills → depth → breadth → modern edge → social proof → the person → contact.
	 * Client-brochure sections (value, services, process, outcomes, insights) are
	 * intentionally not rendered here — the template files still exist if needed.
	 */
	get_template_part( 'template-parts/section', 'hero' );
	get_template_part( 'template-parts/section', 'verified-work' );
	get_template_part( 'template-parts/section', 'experience' );
	get_template_part( 'template-parts/section', 'skills' );
	get_template_part( 'template-parts/section', 'case-studies' );
	get_template_part( 'template-parts/section', 'selected-work' );
	get_template_part( 'template-parts/section', 'ai-advantage' );
	get_template_part( 'template-parts/section', 'testimonials' );
	get_template_part( 'template-parts/section', 'about' );
	get_template_part( 'template-parts/section', 'contact' );
	?>
</main>

<?php
get_footer();
