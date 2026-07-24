<section class="section section-dark" id="ai-advantage" aria-labelledby="ai-heading">
	<div class="container">

		<span class="section-label" style="background:rgba(6,182,212,.1);color:var(--color-secondary);border-color:rgba(6,182,212,.2);">AI Advantage</span>

		<h2 id="ai-heading" style="max-width:760px; margin-bottom:1.25rem;">
			An AI-augmented developer who owns the outcome
		</h2>

		<p class="ai-intro">
			AI hasn&rsquo;t made developers less valuable &mdash; it has raised the bar on
			<strong>judgment</strong>. Boilerplate and first drafts are now cheap. The hard part
			&mdash; understanding the business, validating what AI produces, debugging messy
			production environments, and shipping something that actually holds up &mdash; is
			where I work. <strong>I use AI as a force multiplier and stay accountable for every
			line that reaches production.</strong>
		</p>

		<?php
		$ai_uses = [
			'Spec first — I define the requirement, edge cases, and success criteria before generating a single line.',
			'Generate and accelerate — AI drafts boilerplate, refactors, and explores approaches so I iterate in hours, not days.',
			'Review every line — I read, question, and correct AI output. I never ship code I can\'t explain or defend.',
			'Verify against reality — I test real WooCommerce flows, order states, and edge cases before anything goes live.',
		];

		$ai_limits = [
			'Understanding business context and stakeholder requirements',
			'Production debugging across messy, real-world plugin environments',
			'API architecture decisions and data-mapping strategy',
			'Security review, input validation, and safe data handling',
			'Testing real user flows, edge cases, and order scenarios',
			'Clear communication and translating tech for non-technical teams',
		];
		?>

		<div style="margin-bottom:1rem;" class="ai-grid-label ai-uses-label">
			How I actually work with AI
		</div>
		<div class="ai-cards" style="margin-bottom:3rem;">
			<?php foreach ( $ai_uses as $item ) : ?>
				<div class="ai-card">
					<span class="ai-card-dot cyan" aria-hidden="true"></span>
					<p><?php echo esc_html( $item ); ?></p>
				</div>
			<?php endforeach; ?>
		</div>

		<div style="margin-bottom:1rem;" class="ai-grid-label ai-limits-label">
			Where senior judgment still decides the result
		</div>
		<div class="ai-cards ai-limits-cards">
			<?php foreach ( $ai_limits as $item ) : ?>
				<div class="ai-card">
					<span class="ai-card-dot muted" aria-hidden="true"></span>
					<p><?php echo esc_html( $item ); ?></p>
				</div>
			<?php endforeach; ?>
		</div>

		<div class="ai-closing" role="note">
			<p>
				<strong>What you get when you hire me:</strong> a developer who ships faster with
				AI and owns the reliability of the result. This is the same approach that produced
				two plugins now live on WordPress.org and the production client systems in my case
				studies &mdash; AI for speed, 12+ years of WooCommerce/WordPress judgment for what
				actually works in production.
			</p>
		</div>

	</div>
</section>
