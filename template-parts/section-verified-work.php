<?php
/**
 * Verified Work — published WordPress.org plugins as clickable, verifiable proof.
 * Content lives in yg_get_published_plugins() (functions.php) so it deploys with the theme.
 */

$plugins = yg_get_published_plugins();
?>
<section class="section section-light" id="verified-work" aria-labelledby="verified-heading">
	<div class="container">

		<div class="section-header">
			<span class="section-label">Verified Work</span>
			<h2 id="verified-heading">Published, reviewed, and running in production</h2>
			<p>
				Not just claims &mdash; plugins I built and shipped to the public WordPress.org
				repository, where they passed review and are used on live stores today.
				Every item here is one click to verify.
			</p>
		</div>

		<div class="plugins-grid">
			<?php foreach ( $plugins as $plugin ) : ?>
				<article class="plugin-card">

					<div class="plugin-card-top">
						<span class="plugin-badge">
							<?php echo yg_icon( 'wordpress', 'plugin-badge-icon' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
							WordPress.org
						</span>
					</div>

					<h3 class="plugin-name"><?php echo esc_html( $plugin['name'] ); ?></h3>
					<p class="plugin-tagline"><?php echo esc_html( $plugin['tagline'] ); ?></p>

					<?php if ( ! empty( $plugin['stats'] ) ) : ?>
						<ul class="plugin-stats" role="list">
							<?php foreach ( $plugin['stats'] as $stat ) : ?>
								<li class="plugin-stat">
									<?php echo yg_icon( $stat['icon'], 'plugin-stat-icon' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
									<span><?php echo esc_html( $stat['label'] ); ?></span>
								</li>
							<?php endforeach; ?>
						</ul>
					<?php endif; ?>

					<?php if ( ! empty( $plugin['tags'] ) ) : ?>
						<div class="tag-list" aria-label="Technologies">
							<?php foreach ( $plugin['tags'] as $tag ) : ?>
								<span class="tag"><?php echo esc_html( $tag ); ?></span>
							<?php endforeach; ?>
						</div>
					<?php endif; ?>

					<div class="plugin-card-footer">
						<a href="<?php echo esc_url( $plugin['url'] ); ?>"
						   class="plugin-link"
						   target="_blank" rel="noopener noreferrer">
							View on WordPress.org
							<?php echo yg_icon( 'external', 'plugin-link-icon' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
						</a>
					</div>

				</article>
			<?php endforeach; ?>
		</div>

	</div>
</section>
