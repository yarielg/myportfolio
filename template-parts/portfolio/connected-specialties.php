<?php
/**
 * Portfolio single — connected specialties section.
 * Shows all 5 specialties, highlighting those this project demonstrates.
 *
 * @var array $args Portfolio data array from single-portfolio.php.
 */
$p = $args;

if ( empty( $p['specialties'] ) ) {
	return;
}

$all_specialties = [
	'audit'       => [ 'title' => 'WooCommerce Store Audit & Optimization',     'icon' => 'audit' ],
	'plugin'      => [ 'title' => 'Custom WordPress Plugin Development',         'icon' => 'plugin' ],
	'api'         => [ 'title' => 'WooCommerce API Integrations',                'icon' => 'api' ],
	'performance' => [ 'title' => 'WooCommerce Performance Troubleshooting',     'icon' => 'performance' ],
	'automation'  => [ 'title' => 'E-commerce Automation Workflows',             'icon' => 'automation' ],
];
?>
<section class="pf-connected-specialties pf-section-dark" id="pf-specialties" aria-labelledby="pf-specialties-heading">
	<div class="container">

		<div class="pf-section-label-group">
			<span class="pf-section-label">Specialties Demonstrated</span>
			<h2 id="pf-specialties-heading">Skills applied to this project</h2>
		</div>

		<div class="pf-specialties-grid">
			<?php foreach ( $all_specialties as $key => $spec ) : ?>
				<?php $active = in_array( $key, $p['specialties'], true ); ?>
				<div class="pf-specialty-card <?php echo $active ? 'is-active' : 'is-dim'; ?>">
					<span class="pf-specialty-icon" aria-hidden="true">
						<?php echo yg_icon( $spec['icon'] ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
					</span>
					<span class="pf-specialty-name"><?php echo esc_html( $spec['title'] ); ?></span>
				</div>
			<?php endforeach; ?>
		</div>

	</div>
</section>
