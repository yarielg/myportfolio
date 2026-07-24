<?php
/**
 * Hero — senior positioning, full breadth, AI-era framing, and clear "hire me" signals.
 * Headline/sub/support stay editable via Customizer theme-mods (defaults below deploy in code).
 * Résumé button appears once yg_resume_url is set (site-specific media URL — set per environment).
 */

$headline = get_theme_mod( 'yg_hero_headline', 'Senior WooCommerce &amp; PHP Developer' );
$sub      = get_theme_mod( 'yg_hero_sub',      'I build and maintain the WooCommerce, WordPress, and PHP systems e-commerce businesses run on — checkout, integrations, performance, and the automation that keeps operations moving.' );
$support  = get_theme_mod( 'yg_hero_support',  '12+ years building production WordPress, WooCommerce, and PHP/Laravel systems. Two plugins published on WordPress.org. Now pairing that senior judgment with AI-assisted development to ship reliable solutions faster.' );

$resume   = get_theme_mod( 'yg_resume_url', '' );
$open     = get_theme_mod( 'yg_open_to_work', true );

// Hero portrait: Customizer photo first, else a committed theme asset (deploys as code).
$hero_photo = get_theme_mod( 'yg_hero_photo', '' );
if ( ! $hero_photo && file_exists( get_template_directory() . '/assets/images/hero.jpg' ) ) {
	$hero_photo = get_template_directory_uri() . '/assets/images/hero.jpg';
}
?>
<section class="hero" id="hero" aria-labelledby="hero-heading">
	<div class="container hero-inner<?php echo $hero_photo ? ' has-visual' : ''; ?>">

		<div class="hero-content">

		<?php if ( $open ) : ?>
			<span class="hero-availability">
				<span class="availability-dot" aria-hidden="true"></span>
				Open to full-time &amp; contract roles
			</span>
		<?php endif; ?>

		<span class="hero-eyebrow">Ecommerce &middot; WooCommerce &middot; WordPress &middot; PHP / Laravel</span>

		<h1 id="hero-heading"><?php echo esc_html( html_entity_decode( $headline, ENT_QUOTES, 'UTF-8' ) ); ?></h1>

		<p class="hero-sub"><?php echo esc_html( $sub ); ?></p>

		<p class="hero-support"><?php echo esc_html( $support ); ?></p>

		<div class="hero-actions">
			<a href="#case-studies" class="btn btn-primary">View Case Studies</a>
			<a href="#verified-work" class="btn btn-outline">See Published Work</a>
			<?php if ( $resume ) : ?>
				<a href="<?php echo esc_url( $resume ); ?>" class="btn btn-outline" target="_blank" rel="noopener noreferrer">
					Download Résumé
					<?php echo yg_icon( 'download', 'btn-icon' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
				</a>
			<?php endif; ?>
		</div>

		<div class="trust-badges" aria-label="Quick facts">
			<span class="trust-badge">12+ Years Experience</span>
			<span class="trust-badge">2 Published WordPress.org Plugins</span>
			<span class="trust-badge">WooCommerce + WordPress</span>
			<span class="trust-badge">PHP / Laravel + MySQL + REST APIs</span>
			<span class="trust-badge">Automation + AI-Assisted</span>
			<span class="trust-badge">US Work Authorized</span>
			<span class="trust-badge">Miami, FL &middot; Remote</span>
		</div>

		</div><!-- .hero-content -->

		<?php if ( $hero_photo ) : ?>
			<div class="hero-visual">
				<img
					src="<?php echo esc_url( $hero_photo ); ?>"
					alt="<?php echo esc_attr( get_theme_mod( 'yg_display_name', 'Yariel Gordillo' ) ); ?>"
					class="hero-portrait"
					width="420"
					height="520"
					loading="eager"
				>
			</div>
		<?php endif; ?>

	</div>
</section>
