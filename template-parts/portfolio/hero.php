<?php
/**
 * Portfolio single — Hero section.
 *
 * @var array $args Portfolio data array from single-portfolio.php.
 */
$p = $args;
?>
<section class="pf-hero" id="pf-hero">

	<div class="pf-hero-bg">
		<?php if ( $p['thumbnail_url'] ) : ?>
			<img
				src="<?php echo esc_url( $p['thumbnail_url'] ); ?>"
				alt="<?php echo esc_attr( $p['title'] ); ?>"
				class="pf-hero-img"
				loading="eager"
				decoding="async"
			>
		<?php endif; ?>
		<div class="pf-hero-overlay" aria-hidden="true"></div>
	</div>

	<div class="container pf-hero-inner">

		<a href="<?php echo esc_url( home_url( '/#case-studies' ) ); ?>" class="pf-back">
			<?php echo yg_icon( 'arrow-right', 'pf-back-icon' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
			Back to Portfolio
		</a>

		<div class="pf-hero-content">
			<?php if ( $p['type'] ) : ?>
				<span class="pf-type-badge"><?php echo esc_html( $p['type'] ); ?></span>
			<?php endif; ?>

			<h1 class="pf-title"><?php echo esc_html( $p['title'] ); ?></h1>

			<?php if ( $p['subtitle'] ) : ?>
				<p class="pf-subtitle"><?php echo esc_html( $p['subtitle'] ); ?></p>
			<?php endif; ?>
		</div>

	</div>

	<div class="pf-hero-accent-bar" aria-hidden="true"></div>

</section>
