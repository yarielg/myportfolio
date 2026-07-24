<?php
/**
 * Portfolio single — technical highlights section.
 *
 * @var array $args Portfolio data array from single-portfolio.php.
 */
$p = $args;

if ( empty( $p['tech_highlights'] ) ) {
	return;
}
?>
<section class="pf-tech-highlights pf-section-light" id="pf-tech-highlights" aria-labelledby="pf-tech-heading">
	<div class="container">

		<div class="pf-section-label-group">
			<span class="pf-section-label">Under the Hood</span>
			<h2 id="pf-tech-heading">Technical highlights</h2>
		</div>

		<ul class="pf-tech-list" role="list">
			<?php foreach ( $p['tech_highlights'] as $highlight ) : ?>
				<li class="pf-tech-item"><?php echo esc_html( $highlight ); ?></li>
			<?php endforeach; ?>
		</ul>

	</div>
</section>
