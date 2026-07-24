<?php
/**
 * Portfolio single — project snapshot metadata strip.
 *
 * @var array $args Portfolio data array from single-portfolio.php.
 */
$p = $args;

$display_client = $p['anonymous']
	? 'Anonymous Client'
	: ( $p['confidential'] ? 'Confidential' : $p['client'] );
?>
<section class="pf-snapshot pf-section-light" id="pf-snapshot" aria-label="Project snapshot">
	<div class="container">
		<div class="pf-snapshot-grid">

			<?php if ( $display_client ) : ?>
				<div class="pf-snapshot-item">
					<span class="pf-snapshot-label">Client</span>
					<span class="pf-snapshot-value"><?php echo esc_html( $display_client ); ?></span>
				</div>
			<?php endif; ?>

			<?php if ( $p['year'] ) : ?>
				<div class="pf-snapshot-item">
					<span class="pf-snapshot-label">Year</span>
					<span class="pf-snapshot-value"><?php echo esc_html( $p['year'] ); ?></span>
				</div>
			<?php endif; ?>

			<?php if ( $p['role'] ) : ?>
				<div class="pf-snapshot-item">
					<span class="pf-snapshot-label">My Role</span>
					<span class="pf-snapshot-value"><?php echo esc_html( $p['role'] ); ?></span>
				</div>
			<?php endif; ?>

			<?php if ( $p['url'] && ! $p['confidential'] ) : ?>
				<div class="pf-snapshot-item">
					<span class="pf-snapshot-label">Live Site</span>
					<a
						href="<?php echo esc_url( $p['url'] ); ?>"
						class="pf-snapshot-link"
						target="_blank"
						rel="noopener noreferrer"
					>
						Visit Site →
					</a>
				</div>
			<?php endif; ?>

			<?php if ( ! empty( $p['skills'] ) && ! is_wp_error( $p['skills'] ) ) : ?>
				<div class="pf-snapshot-item pf-snapshot-skills">
					<span class="pf-snapshot-label">Tech Stack</span>
					<div class="pf-skill-pills">
						<?php foreach ( $p['skills'] as $skill ) : ?>
							<span class="pf-skill-pill"><?php echo esc_html( $skill->name ); ?></span>
						<?php endforeach; ?>
					</div>
				</div>
			<?php endif; ?>

		</div>
	</div>
</section>
