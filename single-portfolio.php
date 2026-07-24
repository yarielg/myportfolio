<?php
/**
 * Single Portfolio template.
 * Accessed via /portfolio/project-slug/
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

while ( have_posts() ) :
	the_post();

	$post_id      = get_the_ID();
	$accent_color = get_post_meta( $post_id, '_portfolio_accent_color', true ) ?: '#6366f1';
	$accent_rgb   = yg_hex_to_rgba_parts( $accent_color );

	$portfolio = [
		'id'              => $post_id,
		'title'           => get_the_title(),
		'subtitle'        => get_post_meta( $post_id, '_portfolio_subtitle',    true ),
		'type'            => get_post_meta( $post_id, '_portfolio_type',        true ),
		'client'          => get_post_meta( $post_id, '_portfolio_client',      true ),
		'url'             => get_post_meta( $post_id, '_portfolio_url',         true ),
		'year'            => get_post_meta( $post_id, '_portfolio_year',        true ),
		'role'            => get_post_meta( $post_id, '_portfolio_role',        true ),
		'specialties'     => (array) ( get_post_meta( $post_id, '_portfolio_specialties', true ) ?: [] ),
		'accent_color'    => $accent_color,
		'accent_rgb'      => $accent_rgb,
		'summary'         => get_post_meta( $post_id, '_portfolio_summary',     true ),
		'problem'         => get_post_meta( $post_id, '_portfolio_problem',     true ),
		'solution'        => get_post_meta( $post_id, '_portfolio_solution',    true ),
		'value'           => get_post_meta( $post_id, '_portfolio_value',       true ),
		'outcomes'        => array_filter( array_map( 'trim', explode( "\n", get_post_meta( $post_id, '_portfolio_outcomes',        true ) ?: '' ) ) ),
		'tech_highlights' => array_filter( array_map( 'trim', explode( "\n", get_post_meta( $post_id, '_portfolio_tech_highlights', true ) ?: '' ) ) ),
		'gallery_urls'    => array_filter( array_map( 'trim', explode( "\n", get_post_meta( $post_id, '_portfolio_gallery_urls',    true ) ?: '' ) ) ),
		'confidential'    => (bool) get_post_meta( $post_id, '_portfolio_confidential', true ),
		'anonymous'       => (bool) get_post_meta( $post_id, '_portfolio_anonymous',    true ),
		'cta_label'       => get_post_meta( $post_id, '_portfolio_cta_label',   true ) ?: 'Contact Me',
		'cta_url'         => get_post_meta( $post_id, '_portfolio_cta_url',     true ) ?: home_url( '/#contact' ),
		'skills'          => get_the_terms( $post_id, 'portfolio_skill' ) ?: [],
		'thumbnail_url'   => get_the_post_thumbnail_url( $post_id, 'full' ) ?: '',
		'permalink'       => get_the_permalink(),
	];
	?>

	<main id="main-content" role="main">
		<article
			class="pf-wrap"
			style="--p-accent: <?php echo esc_attr( $accent_color ); ?>; --p-accent-rgb: <?php echo esc_attr( $accent_rgb ); ?>;"
		>
			<?php get_template_part( 'template-parts/portfolio/hero',                  null, $portfolio ); ?>
			<?php get_template_part( 'template-parts/portfolio/anchor-nav',            null, $portfolio ); ?>
			<?php get_template_part( 'template-parts/portfolio/snapshot',              null, $portfolio ); ?>
			<?php get_template_part( 'template-parts/portfolio/connected-specialties', null, $portfolio ); ?>
			<?php get_template_part( 'template-parts/portfolio/overview',              null, $portfolio ); ?>
			<?php get_template_part( 'template-parts/portfolio/challenge',             null, $portfolio ); ?>
			<?php get_template_part( 'template-parts/portfolio/solution',              null, $portfolio ); ?>
			<?php get_template_part( 'template-parts/portfolio/business-value',        null, $portfolio ); ?>
			<?php get_template_part( 'template-parts/portfolio/technical-highlights',  null, $portfolio ); ?>
			<?php get_template_part( 'template-parts/portfolio/gallery',               null, $portfolio ); ?>
			<?php get_template_part( 'template-parts/portfolio/process',               null, $portfolio ); ?>
			<?php get_template_part( 'template-parts/portfolio/skills',                null, $portfolio ); ?>
			<?php get_template_part( 'template-parts/portfolio/ai-value',              null, $portfolio ); ?>
			<?php get_template_part( 'template-parts/portfolio/cta',                   null, $portfolio ); ?>
			<?php get_template_part( 'template-parts/portfolio/related-projects',      null, $portfolio ); ?>
		</article>
	</main>

	<?php
endwhile;

get_footer();
