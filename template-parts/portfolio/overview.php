<?php
/**
 * Portfolio single — project overview / summary section.
 *
 * @var array $args Portfolio data array from single-portfolio.php.
 */
$p = $args;

if ( ! $p['summary'] ) {
	return;
}
?>
<section class="pf-overview pf-section-light" id="pf-overview" aria-labelledby="pf-overview-label">
	<div class="container pf-overview-inner">
		<span class="pf-section-label" id="pf-overview-label">Project Overview</span>
		<p class="pf-overview-text"><?php echo esc_html( $p['summary'] ); ?></p>
	</div>
</section>
