<?php
/**
 * Portfolio single — business value & outcomes section.
 *
 * @var array $args Portfolio data array from single-portfolio.php.
 */
$p = $args;

if ( ! $p['value'] && empty( $p['outcomes'] ) ) {
	return;
}
?>
<section class="pf-business-value pf-section-dark" id="pf-business-value" aria-labelledby="pf-bv-heading">
	<div class="container">

		<div class="pf-section-label-group">
			<span class="pf-section-label">Business Value</span>
			<h2 id="pf-bv-heading">Measurable results</h2>
		</div>

		<div class="pf-bv-inner">

			<?php if ( $p['value'] ) : ?>
				<p class="pf-bv-summary"><?php echo esc_html( $p['value'] ); ?></p>
			<?php endif; ?>

			<?php if ( ! empty( $p['outcomes'] ) ) : ?>
				<ul class="pf-outcomes-list" role="list">
					<?php foreach ( $p['outcomes'] as $outcome ) : ?>
						<li class="pf-outcome-item">
							<span class="pf-outcome-check" aria-hidden="true">
								<?php echo yg_icon( 'check' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
							</span>
							<span><?php echo esc_html( $outcome ); ?></span>
						</li>
					<?php endforeach; ?>
				</ul>
			<?php endif; ?>

		</div>
	</div>
</section>
