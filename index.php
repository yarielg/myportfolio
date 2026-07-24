<?php
/**
 * Fallback template — WordPress requires index.php to exist.
 * This portfolio site uses front-page.php as its primary template.
 * Set a static front page in Settings > Reading to activate it.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>
<main style="text-align:center; padding:6rem 2rem; font-family:system-ui,sans-serif;">
	<h1 style="font-size:1.5rem; color:#1a1f36;">Portfolio</h1>
	<p style="color:#6b7280; margin-top:1rem;">
		Go to <strong>Settings &rarr; Reading</strong> and set a static front page
		to display the portfolio.
	</p>
</main>
<?php
get_footer();
