<?php
/**
 * Portfolio single — screenshot gallery section.
 * Only renders if gallery URLs have been entered.
 *
 * @var array $args Portfolio data array from single-portfolio.php.
 */
$p = $args;

if ( empty( $p['gallery_urls'] ) ) {
	return;
}
?>
<section class="pf-gallery pf-section-dark" id="pf-gallery" aria-labelledby="pf-gallery-heading">
	<div class="container">

		<div class="pf-section-label-group">
			<span class="pf-section-label">Project Screenshots</span>
			<h2 id="pf-gallery-heading">In the details</h2>
		</div>

		<div class="pf-gallery-grid">
			<?php foreach ( $p['gallery_urls'] as $url ) : ?>
				<figure class="pf-gallery-item">
					<img
						src="<?php echo esc_url( $url ); ?>"
						alt="<?php echo esc_attr( $p['title'] ); ?> screenshot"
						loading="lazy"
						decoding="async"
					>
				</figure>
			<?php endforeach; ?>
		</div>

	</div>
</section>
