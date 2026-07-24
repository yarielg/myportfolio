<?php
/**
 * Portfolio single — related projects section.
 * Pulls up to 3 other published portfolio posts (excluding current).
 *
 * @var array $args Portfolio data array from single-portfolio.php.
 */
$p = $args;

$related = new WP_Query( [
	'post_type'      => 'portfolio',
	'posts_per_page' => 3,
	'post_status'    => 'publish',
	'post__not_in'   => [ $p['id'] ],
	'orderby'        => 'menu_order',
	'order'          => 'ASC',
	'no_found_rows'  => true,
] );

if ( ! $related->have_posts() ) {
	return;
}
?>
<section class="pf-related pf-section-light" id="pf-related" aria-labelledby="pf-related-heading">
	<div class="container">

		<div class="pf-section-label-group">
			<span class="pf-section-label">More Work</span>
			<h2 id="pf-related-heading">Related projects</h2>
		</div>

		<div class="pf-related-grid">
			<?php while ( $related->have_posts() ) : $related->the_post(); ?>
				<?php
				$rid    = get_the_ID();
				$racc   = get_post_meta( $rid, '_portfolio_accent_color', true ) ?: '#6366f1';
				$rtype  = get_post_meta( $rid, '_portfolio_type',         true );
				$rsub   = get_post_meta( $rid, '_portfolio_subtitle',     true );
				$rthumb = get_the_post_thumbnail_url( $rid, 'medium_large' );
				?>
				<article
					class="pf-related-card"
					style="--p-accent: <?php echo esc_attr( $racc ); ?>;"
				>
					<?php if ( $rthumb ) : ?>
						<div class="pf-related-img">
							<img
								src="<?php echo esc_url( $rthumb ); ?>"
								alt="<?php echo esc_attr( get_the_title() ); ?>"
								loading="lazy"
								decoding="async"
							>
						</div>
					<?php endif; ?>

					<div class="pf-related-body">
						<?php if ( $rtype ) : ?>
							<span class="pf-related-type"><?php echo esc_html( $rtype ); ?></span>
						<?php endif; ?>

						<h3 class="pf-related-title">
							<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
						</h3>

						<?php if ( $rsub ) : ?>
							<p class="pf-related-sub"><?php echo esc_html( $rsub ); ?></p>
						<?php endif; ?>

						<a href="<?php the_permalink(); ?>" class="pf-related-link" aria-label="View <?php echo esc_attr( get_the_title() ); ?>">
							View Project →
						</a>
					</div>
				</article>
			<?php endwhile; ?>
			<?php wp_reset_postdata(); ?>
		</div>

	</div>
</section>
