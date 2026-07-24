<?php
/**
 * Portfolio single — challenge / problem section.
 *
 * @var array $args Portfolio data array from single-portfolio.php.
 */
$p = $args;

if ( ! $p['problem'] ) {
	return;
}
?>
<section class="pf-challenge pf-section-dark" id="pf-challenge" aria-labelledby="pf-challenge-heading">
	<div class="container">
		<div class="pf-narrative-block">

			<div class="pf-narrative-label">
				<span class="pf-section-label">The Challenge</span>
			</div>

			<div class="pf-narrative-content">
				<h2 id="pf-challenge-heading" class="pf-narrative-heading">What was the problem?</h2>
				<p class="pf-narrative-text"><?php echo nl2br( esc_html( $p['problem'] ) ); ?></p>
			</div>

		</div>
	</div>
</section>
