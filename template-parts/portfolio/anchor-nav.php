<?php
/**
 * Portfolio single — sticky anchor navigation bar.
 *
 * @var array $args Portfolio data array from single-portfolio.php.
 */
$p = $args;

$sections = [
	'pf-overview'        => 'Overview',
	'pf-challenge'       => 'Challenge',
	'pf-solution'        => 'Solution',
	'pf-business-value'  => 'Results',
	'pf-tech-highlights' => 'Tech',
	'pf-skills'          => 'Skills',
];

if ( ! empty( $p['gallery_urls'] ) ) {
	$sections['pf-gallery'] = 'Gallery';
}
?>
<nav class="pf-anchor-nav" id="pf-anchor-nav" aria-label="Project sections">
	<div class="container pf-anchor-nav-inner">

		<span class="pf-anchor-title" aria-hidden="true"><?php echo esc_html( $p['title'] ); ?></span>

		<ul class="pf-anchor-list" role="list">
			<?php foreach ( $sections as $id => $label ) : ?>
				<li>
					<a
						href="#<?php echo esc_attr( $id ); ?>"
						class="pf-anchor-link"
						data-section="<?php echo esc_attr( $id ); ?>"
					>
						<?php echo esc_html( $label ); ?>
					</a>
				</li>
			<?php endforeach; ?>
		</ul>

	</div>
</nav>
