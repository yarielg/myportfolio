<?php
/**
 * Portfolio single — my 6-step process section.
 *
 * @var array $args Portfolio data array from single-portfolio.php.
 */
$p     = $args;
$steps = yg_get_process();

if ( empty( $steps ) ) {
	return;
}
?>
<section class="pf-process pf-section-light" id="pf-process" aria-labelledby="pf-process-heading">
	<div class="container">

		<div class="pf-section-label-group">
			<span class="pf-section-label">How I Work</span>
			<h2 id="pf-process-heading">My process applied to this project</h2>
		</div>

		<ol class="pf-process-steps" role="list">
			<?php foreach ( $steps as $step ) : ?>
				<li class="pf-process-step">
					<span class="pf-process-num" aria-hidden="true"><?php echo esc_html( $step['num'] ); ?></span>
					<div class="pf-process-content">
						<strong class="pf-process-title"><?php echo esc_html( $step['title'] ); ?></strong>
						<p class="pf-process-desc"><?php echo esc_html( $step['desc'] ); ?></p>
					</div>
				</li>
			<?php endforeach; ?>
		</ol>

	</div>
</section>
