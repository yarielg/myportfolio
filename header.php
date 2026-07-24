<?php
$profile_photo = get_theme_mod( 'yg_profile_photo', '' );
$display_name  = get_theme_mod( 'yg_display_name', 'Yariel Gordillo' );
$display_role  = get_theme_mod( 'yg_display_role', 'WooCommerce &amp; WordPress Developer' );
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<a href="#main-content" class="skip-link">Skip to main content</a>

<header class="site-header" id="site-header" role="banner">
	<div class="container header-inner">

		<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="site-logo" aria-label="<?php echo esc_attr( $display_name ); ?> — home">
			<?php if ( $profile_photo ) : ?>
				<img
					src="<?php echo esc_url( $profile_photo ); ?>"
					alt="<?php echo esc_attr( $display_name ); ?>"
					class="profile-photo"
					width="40"
					height="40"
					loading="eager"
				>
			<?php endif; ?>
			<div class="logo-text">
				<span class="logo-name"><?php echo esc_html( $display_name ); ?></span>
				<span class="logo-role"><?php echo esc_html( html_entity_decode( $display_role, ENT_QUOTES, 'UTF-8' ) ); ?></span>
			</div>
		</a>

		<nav class="site-nav" id="site-nav" role="navigation" aria-label="Primary">
			<ul class="nav-list" role="list">
				<li><a href="<?php echo esc_url( home_url( '/#experience' ) ); ?>">Experience</a></li>
				<li><a href="<?php echo esc_url( home_url( '/#skills' ) ); ?>">Skills</a></li>
				<li><a href="<?php echo esc_url( home_url( '/#case-studies' ) ); ?>">Work</a></li>
				<li><a href="<?php echo esc_url( home_url( '/#selected-work' ) ); ?>">Live Sites</a></li>
				<li><a href="<?php echo esc_url( home_url( '/#about' ) ); ?>">About</a></li>
				<li><a href="<?php echo esc_url( home_url( '/#contact' ) ); ?>">Contact</a></li>
			</ul>
		</nav>

		<a href="<?php echo esc_url( home_url( '/#contact' ) ); ?>" class="btn btn-primary btn-sm nav-cta" aria-label="Let's Connect — go to contact section">
			Let's Connect
		</a>

		<button
			class="nav-toggle"
			id="nav-toggle"
			aria-expanded="false"
			aria-controls="site-nav"
			aria-label="Open navigation menu"
		>
			<span class="nav-toggle-icon nav-toggle-open" aria-hidden="true"><?php echo yg_icon( 'menu' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
			<span class="nav-toggle-icon nav-toggle-close" aria-hidden="true"><?php echo yg_icon( 'close' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
		</button>

	</div>
</header>
