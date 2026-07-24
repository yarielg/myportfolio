<?php
/**
 * Selected Work — real live client sites. Data in yg_get_selected_work() (functions.php).
 * Add an 'image' key to an ecommerce entry to show a screenshot instead of the placeholder.
 */

$work = yg_get_selected_work();
if ( empty( $work['ecommerce'] ) && empty( $work['websites'] ) ) {
	return;
}
?>
<section class="section section-light" id="selected-work" aria-labelledby="selected-work-heading">
	<div class="container">

		<div class="section-header">
			<span class="section-label">Selected Work</span>
			<h2 id="selected-work-heading">Real stores and sites, live in production</h2>
			<p>
				A selection of WooCommerce stores and websites I&rsquo;ve built or worked on &mdash;
				every one is live and one click away. This is a sample, not the full list.
			</p>
		</div>

		<?php if ( ! empty( $work['ecommerce'] ) ) : ?>
			<div class="work-grid">
				<?php foreach ( $work['ecommerce'] as $site ) : ?>
					<?php $accent = $site['accent'] ?? '#6366f1'; ?>
					<a class="work-card" href="<?php echo esc_url( $site['url'] ); ?>"
					   target="_blank" rel="noopener noreferrer"
					   style="--work-accent: <?php echo esc_attr( $accent ); ?>;">

						<span class="work-thumb">
							<?php if ( ! empty( $site['image'] ) ) : ?>
								<img src="<?php echo esc_url( $site['image'] ); ?>"
								     alt="<?php echo esc_attr( $site['name'] ); ?>"
								     class="work-thumb-img" loading="lazy">
							<?php else : ?>
								<span class="work-monogram" aria-hidden="true"><?php echo esc_html( strtoupper( mb_substr( $site['name'], 0, 1 ) ) ); ?></span>
							<?php endif; ?>
						</span>

						<span class="work-body">
							<span class="work-name">
								<?php echo esc_html( $site['name'] ); ?>
								<?php echo yg_icon( 'external', 'work-name-icon' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
							</span>
							<?php if ( ! empty( $site['tags'] ) ) : ?>
								<span class="work-tags">
									<?php foreach ( $site['tags'] as $tag ) : ?>
										<span class="work-tag"><?php echo esc_html( $tag ); ?></span>
									<?php endforeach; ?>
								</span>
							<?php endif; ?>
						</span>
					</a>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>

		<?php if ( ! empty( $work['websites'] ) ) : ?>
			<div class="work-more">
				<span class="work-more-label">Beyond e-commerce &mdash; websites &amp; web apps I&rsquo;ve built:</span>
				<span class="work-more-list">
					<?php
					$last = count( $work['websites'] ) - 1;
					foreach ( $work['websites'] as $i => $site ) : ?>
						<a href="<?php echo esc_url( $site['url'] ); ?>" target="_blank" rel="noopener noreferrer" class="work-more-link"><?php echo esc_html( $site['name'] ); ?></a><?php echo $i < $last ? '<span class="work-more-sep">·</span>' : ''; ?>
					<?php endforeach; ?>
				</span>
			</div>
		<?php endif; ?>

	</div>
</section>
