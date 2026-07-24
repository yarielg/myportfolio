<?php
/**
 * Portfolio single — skills & technologies used section.
 *
 * @var array $args Portfolio data array from single-portfolio.php.
 */
$p = $args;

if ( empty( $p['skills'] ) || is_wp_error( $p['skills'] ) ) {
	return;
}
?>
<section class="pf-skills pf-section-dark" id="pf-skills" aria-labelledby="pf-skills-heading">
	<div class="container">

		<div class="pf-section-label-group">
			<span class="pf-section-label">Skills & Tools</span>
			<h2 id="pf-skills-heading">Tech used in this project</h2>
		</div>

		<div class="pf-skills-pills" role="list" aria-label="Skills and technologies used">
			<?php foreach ( $p['skills'] as $skill ) : ?>
				<span class="pf-skill-pill pf-skill-pill-lg" role="listitem">
					<?php echo esc_html( $skill->name ); ?>
				</span>
			<?php endforeach; ?>
		</div>

	</div>
</section>
