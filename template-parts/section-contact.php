<?php
$social = yg_social_links();
?>
<section class="section" id="contact" aria-labelledby="contact-heading">
	<div class="container contact-inner">

		<div class="contact-intro">
			<span class="section-label">Contact</span>
			<h2 id="contact-heading">Let's talk</h2>
			<p>
				I&rsquo;m open to full-time and contract roles, and the occasional interesting
				project. Tell me what you have in mind &mdash; I&rsquo;ll get back to you.
			</p>

			<div class="contact-social" aria-label="Connect on social platforms">

				<a href="<?php echo esc_url( $social['linkedin'] ); ?>"
				   class="social-link"
				   target="_blank"
				   rel="noopener noreferrer"
				   aria-label="Connect on LinkedIn">
					<?php echo yg_icon( 'linkedin', 'social-icon' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
					LinkedIn
				</a>

				<a href="<?php echo esc_url( $social['github'] ); ?>"
				   class="social-link"
				   target="_blank"
				   rel="noopener noreferrer"
				   aria-label="View GitHub profile">
					<?php echo yg_icon( 'github', 'social-icon' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
					GitHub
				</a>

				<a href="<?php echo esc_url( $social['email'] ); ?>"
				   class="social-link"
				   aria-label="Send email">
					<?php echo yg_icon( 'mail', 'social-icon' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
					Email
				</a>

			</div>
		</div>

		<div class="contact-form-wrap">
			<form
				id="yg-contact-form"
				novalidate
				aria-label="Contact form"
			>
				<div class="form-row">
					<div class="form-group">
						<label for="contact-name">Name <span aria-hidden="true">*</span></label>
						<input
							type="text"
							id="contact-name"
							name="name"
							autocomplete="name"
							required
							placeholder="Your name"
						>
					</div>
					<div class="form-group">
						<label for="contact-email">Email <span aria-hidden="true">*</span></label>
						<input
							type="email"
							id="contact-email"
							name="email"
							autocomplete="email"
							required
							placeholder="your@email.com"
						>
					</div>
				</div>

				<div class="form-group">
					<label for="contact-type">What's this about?</label>
					<select id="contact-type" name="project_type">
						<option value="">Select one (optional)</option>
						<option>Full-time role</option>
						<option>Contract role</option>
						<option>Freelance project</option>
						<option>General inquiry</option>
						<option>Other</option>
					</select>
				</div>

				<div class="form-group">
					<label for="contact-message">Message <span aria-hidden="true">*</span></label>
					<textarea
						id="contact-message"
						name="message"
						rows="5"
						required
						placeholder="A few lines about the role or what you have in mind…"
					></textarea>
				</div>

				<button type="submit" class="btn btn-primary">
					Send Message
					<?php echo yg_icon( 'arrow-right' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
				</button>

				<div id="form-feedback" role="alert" aria-live="polite"></div>

				<p class="recaptcha-notice">
					This site is protected by reCAPTCHA.
					<a href="https://policies.google.com/privacy" target="_blank" rel="noopener noreferrer">Privacy Policy</a>
					&amp;
					<a href="https://policies.google.com/terms" target="_blank" rel="noopener noreferrer">Terms of Service</a> apply.
				</p>

			</form>
		</div>

	</div>
</section>
