<?php
/**
 * Work Experience — résumé timeline. Data in yg_get_experience() (functions.php).
 */

$experience = yg_get_experience();
if ( empty( $experience ) ) {
	return;
}
?>
<section class="section section-light" id="experience" aria-labelledby="experience-heading">
	<div class="container">

		<div class="section-header">
			<span class="section-label">Experience</span>
			<h2 id="experience-heading">12+ years shipping production PHP &amp; WordPress</h2>
			<p>
				A track record across agencies, product businesses, and freelance work &mdash;
				WordPress, WooCommerce, Laravel, APIs, and enterprise integrations.
			</p>
		</div>

		<ol class="experience-list" role="list">
			<?php foreach ( $experience as $job ) : ?>
				<li class="experience-item">
					<span class="experience-dot<?php echo ! empty( $job['current'] ) ? ' is-current' : ''; ?>" aria-hidden="true"></span>
					<div class="experience-body">
						<div class="experience-head">
							<h3 class="experience-role"><?php echo esc_html( $job['role'] ); ?></h3>
							<span class="experience-dates">
								<?php echo esc_html( $job['dates'] ); ?>
								<?php if ( ! empty( $job['current'] ) ) : ?>
									<span class="experience-now">Current</span>
								<?php endif; ?>
							</span>
						</div>
						<p class="experience-company">
							<?php echo esc_html( $job['company'] ); ?>
							<?php if ( ! empty( $job['location'] ) ) : ?>
								<span class="experience-loc">&middot; <?php echo esc_html( $job['location'] ); ?></span>
							<?php endif; ?>
						</p>
						<?php if ( ! empty( $job['bullets'] ) ) : ?>
							<ul class="experience-bullets">
								<?php foreach ( $job['bullets'] as $bullet ) : ?>
									<li><?php echo esc_html( $bullet ); ?></li>
								<?php endforeach; ?>
							</ul>
						<?php endif; ?>
					</div>
				</li>
			<?php endforeach; ?>
		</ol>

		<div class="education-note">
			<span class="education-label">Education</span>
			<p>
				<strong>Bachelor&rsquo;s in Computer Science</strong> &mdash;
				University of Computer Sciences (UCI), Cuba &middot; 2008&ndash;2012
			</p>
			<p class="education-auth">Authorized to work in the US for any employer.</p>
		</div>

	</div>
</section>
