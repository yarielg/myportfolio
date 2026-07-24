<?php
$about_photo = get_theme_mod( 'yg_profile_photo', '' );
$about_name  = get_theme_mod( 'yg_display_name', 'Yariel Gordillo' );
?>
<section class="section section-dark" id="about" aria-labelledby="about-heading">
	<div class="container about-inner">

		<div class="about-text">
			<span class="section-label" style="background:rgba(6,182,212,.1);color:var(--color-secondary);border-color:rgba(6,182,212,.2);">About</span>

			<div class="about-identity">
				<?php if ( $about_photo ) : ?>
					<img
						src="<?php echo esc_url( $about_photo ); ?>"
						alt="<?php echo esc_attr( $about_name ); ?>"
						class="about-avatar"
						width="96"
						height="96"
						loading="lazy"
					>
				<?php else : ?>
					<span class="about-avatar about-avatar-placeholder" aria-hidden="true">
						<?php echo esc_html( strtoupper( mb_substr( $about_name, 0, 1 ) ) ); ?>
					</span>
				<?php endif; ?>
				<div class="about-identity-text">
					<h2 id="about-heading"><?php echo esc_html( $about_name ); ?></h2>
					<p class="about-identity-meta">Senior WooCommerce &amp; PHP Developer &middot; Miami, FL</p>
				</div>
			</div>

			<p>
				I am a Lead WordPress, WooCommerce, and PHP/Laravel developer with 12+ years of
				experience building websites, custom plugins, themes, web applications, and
				business-focused e-commerce systems. My work is focused on practical technical
				solutions that help businesses operate better: checkout improvements, performance
				troubleshooting, third-party and enterprise integrations, custom workflows,
				automation, and reliable backend tools.
			</p>

			<p>
				Over the years, I have worked on real production websites where the challenge is
				not only writing code, but understanding the business process, debugging messy
				plugin environments, safely deploying changes, and making systems easier for teams
				and customers to use.
			</p>

			<p>
				Today, I use AI-assisted development tools to move faster — but my core value is
				knowing <em>what</em> to build, <em>how</em> to test it, and <em>how</em> to make
				it work reliably in a real business environment.
			</p>

			<div class="about-actions">
				<a href="#contact" class="btn btn-primary">Work Together</a>
				<?php
				$social = yg_social_links();
				?>
				<a href="<?php echo esc_url( $social['linkedin'] ); ?>" class="btn btn-outline" target="_blank" rel="noopener noreferrer">
					LinkedIn
					<?php echo yg_icon( 'linkedin', 'btn-icon' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
				</a>
			</div>
		</div>

		<div class="about-highlights">

			<div class="about-highlight">
				<h4>Experience</h4>
				<p>12+ years across WordPress, WooCommerce, PHP/Laravel, MySQL, REST APIs, and SAP Business One integrations on real production systems.</p>
			</div>

			<div class="about-highlight">
				<h4>Focus</h4>
				<p>Business-critical WooCommerce systems: checkout, subscriptions, integrations, automation, and backend reliability.</p>
			</div>

			<div class="about-highlight">
				<h4>Approach</h4>
				<p>Diagnose first. Plan the cleanest solution. Build it properly. Test edge cases. Deploy carefully.</p>
			</div>

			<div class="about-highlight">
				<h4>AI-Era Positioning</h4>
				<p>Using AI as a productivity layer while applying senior judgment to validate, test, and ship reliable solutions.</p>
			</div>

		</div>

	</div>
</section>
