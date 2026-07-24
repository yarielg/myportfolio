<?php
$groups = get_terms( [
	'taxonomy'   => 'skill_group',
	'hide_empty' => true,
	'orderby'    => 'term_order',
	'order'      => 'ASC',
] );
?>
<section class="section section-light" id="skills" aria-labelledby="skills-heading">
	<div class="container">

		<div class="section-header">
			<span class="section-label">Skills</span>
			<h2 id="skills-heading">Technical toolkit</h2>
			<p>
				12+ years of hands-on production experience across WooCommerce,
				WordPress, PHP/Laravel, APIs, performance, and automation.
			</p>
		</div>

		<div class="skills-grid">
			<?php if ( ! empty( $groups ) && ! is_wp_error( $groups ) ) : ?>

				<?php foreach ( $groups as $group ) : ?>
					<?php
					$skills = get_posts( [
						'post_type'      => 'yg_skill',
						'posts_per_page' => -1,
						'orderby'        => 'menu_order title',
						'order'          => 'ASC',
						'no_found_rows'  => true,
						'tax_query'      => [ [
							'taxonomy' => 'skill_group',
							'field'    => 'term_id',
							'terms'    => $group->term_id,
						] ],
					] );
					if ( empty( $skills ) ) continue;
					?>
					<div class="skill-group">
						<div class="skill-group-title"><?php echo esc_html( $group->name ); ?></div>
						<div class="skill-pills">
							<?php foreach ( $skills as $skill ) : ?>
								<span class="skill-pill"><?php echo esc_html( $skill->post_title ); ?></span>
							<?php endforeach; ?>
						</div>
					</div>
				<?php endforeach; ?>

			<?php else : ?>
				<p style="color:var(--color-muted); grid-column:1/-1; text-align:center; padding:2rem 0;">
					No skills found. Add skills at <strong>Skills &rarr; Add New Skill</strong> in the WordPress admin
					and assign each one to a Skill Group.
				</p>
			<?php endif; ?>
		</div>

	</div>
</section>
