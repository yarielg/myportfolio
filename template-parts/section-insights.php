<?php
$insights = yg_get_insights();
?>
<section class="section section-light" id="insights" aria-labelledby="insights-heading">
	<div class="container">

		<div class="section-header">
			<span class="section-label">Insights</span>
			<h2 id="insights-heading">Thoughts on WooCommerce &amp; WordPress</h2>
			<p>
				Perspectives from years of working on production WooCommerce stores,
				custom plugins, and real business systems.
			</p>
		</div>

		<div class="insights-grid">
			<?php foreach ( $insights as $insight ) : ?>
				<article class="insight-card">
					<div class="insight-label">Insight</div>
					<h3><?php echo esc_html( $insight['title'] ); ?></h3>
					<p><?php echo esc_html( $insight['excerpt'] ); ?></p>
				</article>
			<?php endforeach; ?>
		</div>

	</div>
</section>
