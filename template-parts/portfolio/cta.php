<?php
/**
 * Portfolio single — call to action section.
 *
 * @var array $args Portfolio data array from single-portfolio.php.
 */
$p = $args;

$cta_url   = $p['cta_url']   ?: home_url( '/#contact' );
$cta_label = $p['cta_label'] ?: 'Contact Me';
?>
<section class="pf-cta pf-section-dark" id="pf-cta" aria-labelledby="pf-cta-heading">
	<div class="container pf-cta-inner">

		<h2 id="pf-cta-heading">Have a similar challenge?</h2>

		<p class="pf-cta-sub">
			I help e-commerce teams solve the technical problems that cost them time and revenue.
			Let's talk about yours.
		</p>

		<div class="pf-cta-actions">
			<a href="<?php echo esc_url( $cta_url ); ?>" class="btn btn-primary">
				<?php echo esc_html( $cta_label ); ?>
				<?php echo yg_icon( 'arrow-right' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
			</a>
			<a href="<?php echo esc_url( home_url( '/#case-studies' ) ); ?>" class="btn btn-outline">
				View More Projects
			</a>
		</div>

	</div>
</section>
