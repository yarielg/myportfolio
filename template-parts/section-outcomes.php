<?php
$outcomes = yg_get_outcomes();
?>
<section class="section section-light" id="outcomes" aria-labelledby="outcomes-heading">
	<div class="container">

		<div class="section-header">
			<span class="section-label">Business Outcomes</span>
			<h2 id="outcomes-heading">What I focus on delivering</h2>
			<p>
				Every project is measured against real business impact, not just
				code quality or feature delivery.
			</p>
		</div>

		<div class="outcomes-grid" role="list">
			<?php foreach ( $outcomes as $outcome ) : ?>
				<div class="outcome-card" role="listitem">
					<div class="outcome-icon" aria-hidden="true">
						<?php echo yg_icon( 'check' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
					</div>
					<p><?php echo esc_html( $outcome ); ?></p>
				</div>
			<?php endforeach; ?>
		</div>

	</div>
</section>
