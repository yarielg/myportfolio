<?php
$projects = new WP_Query( [
	'post_type'           => 'portfolio',
	'posts_per_page'      => -1,
	'orderby'             => [ 'menu_order' => 'ASC', 'date' => 'DESC' ],
	'no_found_rows'       => true,
	'ignore_sticky_posts' => true,
] );
?>
<section class="section" id="case-studies" aria-labelledby="case-studies-heading">
	<div class="container">

		<div class="section-header">
			<span class="section-label">Case Studies</span>
			<h2 id="case-studies-heading">Projects that solved real problems</h2>
			<p>
				Each project represents a real business challenge solved with
				WordPress, WooCommerce, or PHP — not just a visual redesign.
			</p>
		</div>

		<?php if ( $projects->have_posts() ) : ?>
			<div class="case-studies-grid">
				<?php while ( $projects->have_posts() ) : $projects->the_post(); ?>
					<?php
					$post_id  = get_the_ID();
					$subtitle = get_post_meta( $post_id, '_portfolio_subtitle', true );
					$type     = get_post_meta( $post_id, '_portfolio_type',     true );
					$accent   = get_post_meta( $post_id, '_portfolio_accent_color', true ) ?: '#6366f1';
					$summary  = get_post_meta( $post_id, '_portfolio_summary', true );
					if ( ! $summary ) {
						$summary = get_post_meta( $post_id, '_portfolio_problem', true );
					}
					$skills   = get_the_terms( $post_id, 'portfolio_skill' );
					?>
					<article class="case-card">

						<?php if ( has_post_thumbnail() ) : ?>
							<div class="case-img-wrap">
								<a href="<?php the_permalink(); ?>" tabindex="-1" aria-hidden="true">
									<?php the_post_thumbnail( 'medium_large', [
										'class'   => 'case-img',
										'loading' => 'lazy',
										'alt'     => get_the_title(),
									] ); ?>
								</a>
							</div>
						<?php else : ?>
							<a href="<?php the_permalink(); ?>" class="case-img-wrap case-img-placeholder"
							   tabindex="-1" aria-hidden="true"
							   style="--case-accent: <?php echo esc_attr( $accent ); ?>;">
								<span class="case-monogram"><?php echo esc_html( strtoupper( mb_substr( get_the_title(), 0, 1 ) ) ); ?></span>
								<?php if ( $type ) : ?>
									<span class="case-placeholder-type"><?php echo esc_html( $type ); ?></span>
								<?php endif; ?>
							</a>
						<?php endif; ?>

						<?php if ( $type ) : ?>
							<span class="case-type-badge"><?php echo esc_html( $type ); ?></span>
						<?php endif; ?>

						<h3>
							<a href="<?php the_permalink(); ?>" class="case-title-link">
								<?php the_title(); ?>
							</a>
						</h3>

						<?php if ( $subtitle ) : ?>
							<p class="case-subtitle"><?php echo esc_html( $subtitle ); ?></p>
						<?php endif; ?>

						<?php if ( $summary ) : ?>
							<p class="case-summary"><?php echo esc_html( $summary ); ?></p>
						<?php endif; ?>

						<?php if ( $skills && ! is_wp_error( $skills ) ) : ?>
							<div class="tag-list" aria-label="Skills used">
								<?php foreach ( $skills as $skill ) : ?>
									<span class="tag tag-cyan"><?php echo esc_html( $skill->name ); ?></span>
								<?php endforeach; ?>
							</div>
						<?php endif; ?>

						<div class="case-card-footer">
							<a href="<?php the_permalink(); ?>" class="case-read-more">
								View Full Case Study
								<?php echo yg_icon( 'arrow-right' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
							</a>
						</div>

					</article>
				<?php endwhile; ?>
				<?php wp_reset_postdata(); ?>
			</div>

		<?php else : ?>
			<p style="color:var(--color-muted); text-align:center; padding:3rem 0;">
				No portfolio projects found. Go to <strong>Portfolio &rarr; Add New Project</strong> in the WordPress admin to add your first project.
			</p>
		<?php endif; ?>

		<div style="text-align:center; margin-top:2.5rem;">
			<a href="#contact" class="btn btn-outline-dark">
				Get in touch
				<?php echo yg_icon( 'arrow-right' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
			</a>
		</div>

	</div>
</section>
