<?php
$services = yg_get_services();
?>
<section class="section section-light" id="services" aria-labelledby="services-heading">
	<div class="container">

		<div class="section-header">
			<span class="section-label">Services</span>
			<h2 id="services-heading">What I help with</h2>
			<p>
				Focused services for WooCommerce stores and WordPress-based businesses
				that need real technical solutions, not just design changes.
			</p>
		</div>

		<div class="services-grid">
			<?php foreach ( $services as $service ) : ?>
				<article class="service-card">

					<div class="service-icon" aria-hidden="true">
						<?php echo yg_icon( $service['icon'] ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
					</div>

					<h3><?php echo wp_kses_post( $service['title'] ); ?></h3>
					<p><?php echo esc_html( $service['description'] ); ?></p>

					<?php if ( ! empty( $service['tags'] ) ) : ?>
						<div class="tag-list" aria-label="Skills used">
							<?php foreach ( $service['tags'] as $tag ) : ?>
								<span class="tag"><?php echo esc_html( $tag ); ?></span>
							<?php endforeach; ?>
						</div>
					<?php endif; ?>

				</article>
			<?php endforeach; ?>
		</div>

	</div>
</section>
