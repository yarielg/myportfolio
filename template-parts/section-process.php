<?php
$steps = yg_get_process();
?>
<section class="section" id="process" aria-labelledby="process-heading">
	<div class="container">

		<div class="section-header">
			<span class="section-label">Process</span>
			<h2 id="process-heading">How I approach projects</h2>
			<p>
				A structured approach from diagnosis to deployment means fewer surprises,
				better results, and solutions that hold up in production.
			</p>
		</div>

		<div class="process-grid" role="list">
			<?php foreach ( $steps as $step ) : ?>
				<div class="process-step" role="listitem">
					<div class="process-num" aria-hidden="true"><?php echo esc_html( $step['num'] ); ?></div>
					<div class="process-body">
						<h3><?php echo esc_html( $step['title'] ); ?></h3>
						<p><?php echo esc_html( $step['desc'] ); ?></p>
					</div>
				</div>
			<?php endforeach; ?>
		</div>

	</div>
</section>
