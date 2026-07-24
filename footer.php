<?php
$social = yg_social_links();
$year   = gmdate( 'Y' );
?>

<footer class="site-footer" role="contentinfo">
	<div class="container footer-inner">

		<div class="footer-brand">
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="footer-logo" aria-label="Back to top">
				Yariel Gordillo
			</a>
			<p class="footer-tagline">WooCommerce &amp; WordPress Systems Developer</p>
			<p class="footer-tagline-sub">Building e-commerce systems that work reliably in production.</p>
		</div>

		<nav class="footer-nav" aria-label="Footer navigation">
			<a href="#experience">Experience</a>
			<a href="#skills">Skills</a>
			<a href="#case-studies">Case Studies</a>
			<a href="#selected-work">Live Sites</a>
			<a href="#about">About</a>
			<a href="#contact">Contact</a>
		</nav>

		<div class="footer-social" aria-label="Social links">
			<a href="<?php echo esc_url( $social['linkedin'] ); ?>" target="_blank" rel="noopener noreferrer" aria-label="LinkedIn profile">
				<?php echo yg_icon( 'linkedin', 'social-icon' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
				LinkedIn
			</a>
			<a href="<?php echo esc_url( $social['github'] ); ?>" target="_blank" rel="noopener noreferrer" aria-label="GitHub profile">
				<?php echo yg_icon( 'github', 'social-icon' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
				GitHub
			</a>
			<a href="<?php echo esc_url( $social['email'] ); ?>" aria-label="Send email">
				<?php echo yg_icon( 'mail', 'social-icon' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
				Email
			</a>
		</div>

		<p class="footer-copy">
			&copy; <?php echo esc_html( $year ); ?> Yariel Gordillo &mdash; All rights reserved.
		</p>

	</div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
