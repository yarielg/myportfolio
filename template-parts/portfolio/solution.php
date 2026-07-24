<?php
/**
 * Portfolio single — solution section.
 *
 * @var array $args Portfolio data array from single-portfolio.php.
 */
$p = $args;

if ( ! $p['solution'] ) {
	return;
}
?>
<section class="pf-solution pf-section-light" id="pf-solution" aria-labelledby="pf-solution-heading">
	<div class="container">
		<div class="pf-narrative-block">

			<div class="pf-narrative-label">
				<span class="pf-section-label">The Solution</span>
			</div>

			<div class="pf-narrative-content">
				<h2 id="pf-solution-heading" class="pf-narrative-heading">How I solved it</h2>
				<p class="pf-narrative-text"><?php echo nl2br( esc_html( $p['solution'] ) ); ?></p>
			</div>

		</div>
	</div>
</section>
