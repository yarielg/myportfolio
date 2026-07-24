<?php
/**
 * Testimonials — real client reviews.
 * Content in yg_get_testimonials() (functions.php) so it deploys with the theme.
 */

$testimonials = yg_get_testimonials();
if ( empty( $testimonials ) ) {
	return;
}
?>
<section class="section section-light" id="testimonials" aria-labelledby="testimonials-heading">
	<div class="container">

		<div class="section-header">
			<span class="section-label">Testimonials</span>
			<h2 id="testimonials-heading">What clients say about working with me</h2>
			<p>
				Real feedback from business owners and teams I&rsquo;ve helped with WooCommerce,
				ecommerce, and custom development work.
			</p>
		</div>

		<div class="testimonials-grid">
			<?php foreach ( $testimonials as $t ) : ?>
				<figure class="testimonial-card">
					<span class="testimonial-quote-icon" aria-hidden="true">
						<?php echo yg_icon( 'quote' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
					</span>
					<blockquote class="testimonial-quote">
						<p><?php echo esc_html( $t['quote'] ); ?></p>
					</blockquote>
					<figcaption class="testimonial-author">
						<strong class="testimonial-name"><?php echo esc_html( $t['name'] ); ?></strong>
						<?php if ( ! empty( $t['role'] ) ) : ?>
							<span class="testimonial-role"><?php echo esc_html( $t['role'] ); ?></span>
						<?php endif; ?>
						<?php if ( ! empty( $t['source'] ) ) : ?>
							<span class="testimonial-source"><?php echo esc_html( $t['source'] ); ?></span>
						<?php endif; ?>
					</figcaption>
				</figure>
			<?php endforeach; ?>
		</div>

	</div>
</section>
