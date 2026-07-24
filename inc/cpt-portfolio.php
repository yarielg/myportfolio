<?php
/**
 * Portfolio CPT — register, meta boxes, taxonomy.
 *
 * Admin path: Portfolio → Add New Project
 * Taxonomy: portfolio_skill (flat tags, shared across projects)
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// ── CPT ────────────────────────────────────────────────────────────────────────

function yg_register_portfolio_cpt(): void {
	register_post_type( 'portfolio', [
		'labels' => [
			'name'               => 'Portfolio',
			'singular_name'      => 'Project',
			'add_new'            => 'Add New',
			'add_new_item'       => 'Add New Project',
			'edit_item'          => 'Edit Project',
			'view_item'          => 'View Project',
			'search_items'       => 'Search Projects',
			'not_found'          => 'No projects found.',
			'not_found_in_trash' => 'No projects in trash.',
			'all_items'          => 'All Projects',
			'menu_name'          => 'Portfolio',
		],
		'public'            => true,
		'has_archive'       => false,
		'show_in_rest'      => true,
		'menu_icon'         => 'dashicons-portfolio',
		'menu_position'     => 5,
		'supports'          => [ 'title', 'thumbnail', 'excerpt', 'page-attributes', 'editor', 'revisions' ],
		'rewrite'           => [ 'slug' => 'portfolio', 'with_front' => false ],
		'show_in_nav_menus' => false,
	] );
}
add_action( 'init', 'yg_register_portfolio_cpt' );

// ── DISABLE BLOCK EDITOR FOR PORTFOLIO ────────────────────────────────────────

add_filter( 'use_block_editor_for_post_type', static function ( bool $use, string $post_type ): bool {
	return 'portfolio' === $post_type ? false : $use;
}, 10, 2 );

// ── TAXONOMY: portfolio_skill ──────────────────────────────────────────────────

function yg_register_portfolio_skill_taxonomy(): void {
	register_taxonomy( 'portfolio_skill', 'portfolio', [
		'labels' => [
			'name'                  => 'Skills Used',
			'singular_name'         => 'Skill',
			'add_new_item'          => 'Add Skill',
			'search_items'          => 'Search Skills',
			'all_items'             => 'All Skills',
			'edit_item'             => 'Edit Skill',
			'update_item'           => 'Update Skill',
			'add_or_remove_items'   => 'Add or remove skills',
			'choose_from_most_used' => 'Choose from most used',
		],
		'hierarchical'      => false,
		'show_in_rest'      => true,
		'show_admin_column' => true,
		'rewrite'           => false,
	] );
}
add_action( 'init', 'yg_register_portfolio_skill_taxonomy' );

// ── META BOXES ─────────────────────────────────────────────────────────────────

function yg_portfolio_add_meta_boxes(): void {
	$boxes = [
		[ 'yg_portfolio_overview', 'Project Overview',     'yg_portfolio_mb_overview' ],
		[ 'yg_portfolio_story',    'Project Story',        'yg_portfolio_mb_story' ],
		[ 'yg_portfolio_results',  'Results & Highlights', 'yg_portfolio_mb_results' ],
		[ 'yg_portfolio_display',  'Display & CTA',        'yg_portfolio_mb_display' ],
		[ 'yg_portfolio_gallery',  'Gallery',              'yg_portfolio_mb_gallery' ],
	];
	foreach ( $boxes as [ $id, $title, $cb ] ) {
		add_meta_box( $id, $title, $cb, 'portfolio', 'normal', 'high' );
	}
}
add_action( 'add_meta_boxes', 'yg_portfolio_add_meta_boxes' );

// ── SHARED META BOX STYLES ────────────────────────────────────────────────────

function yg_portfolio_mb_styles(): void {
	static $printed = false;
	if ( $printed ) {
		return;
	}
	$printed = true;
	echo '<style>
		.yg-meta-field { margin-bottom: 1.25rem; }
		.yg-meta-field label { display: block; font-weight: 600; margin-bottom: 4px; font-size: 13px; }
		.yg-meta-field input[type="text"],
		.yg-meta-field input[type="url"],
		.yg-meta-field input[type="color"],
		.yg-meta-field select,
		.yg-meta-field textarea {
			width: 100%; padding: 8px 10px; border: 1px solid #dcdcde;
			border-radius: 4px; font-size: 13px; font-family: inherit;
			background: #fff; color: #1d2327;
		}
		.yg-meta-field input[type="color"] { padding: 2px; height: 36px; width: 60px; }
		.yg-meta-field input:focus,
		.yg-meta-field select:focus,
		.yg-meta-field textarea:focus { border-color: #2271b1; box-shadow: 0 0 0 1px #2271b1; outline: none; }
		.yg-meta-field textarea { resize: vertical; min-height: 80px; }
		.yg-meta-hint { font-size: 11px; color: #8c8f94; margin-top: 3px; }
		.yg-meta-row { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }
		.yg-meta-row-3 { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 1rem; }
		.yg-meta-checks label { font-weight: 400; display: flex; align-items: center; gap: 6px; cursor: pointer; }
		.yg-meta-checks input[type="checkbox"] { width: auto; }
		.yg-meta-checks-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 8px; }
		.yg-color-row { display: flex; align-items: center; gap: 10px; }
		.yg-color-row input[type="text"] { width: 110px !important; }
	</style>';
}

// ── RENDER: Project Overview ───────────────────────────────────────────────────

function yg_portfolio_mb_overview( WP_Post $post ): void {
	wp_nonce_field( 'yg_portfolio_save_meta', 'yg_portfolio_nonce' );
	yg_portfolio_mb_styles();

	$subtitle = get_post_meta( $post->ID, '_portfolio_subtitle', true );
	$type     = get_post_meta( $post->ID, '_portfolio_type',     true );
	$client   = get_post_meta( $post->ID, '_portfolio_client',   true );
	$url      = get_post_meta( $post->ID, '_portfolio_url',      true );
	$year     = get_post_meta( $post->ID, '_portfolio_year',     true );
	$role     = get_post_meta( $post->ID, '_portfolio_role',     true );
	?>
	<p style="margin-bottom:1.5rem; color:#50575e; font-size:13px;">Basic project metadata shown in the case-study card and project header.</p>
	<div class="yg-meta-row">
		<div class="yg-meta-field">
			<label for="_portfolio_subtitle">Subtitle</label>
			<input type="text" id="_portfolio_subtitle" name="_portfolio_subtitle" value="<?php echo esc_attr( $subtitle ); ?>" placeholder="e.g. WooCommerce Performance & Shopping Flow">
		</div>
		<div class="yg-meta-field">
			<label for="_portfolio_type">Project Type</label>
			<input type="text" id="_portfolio_type" name="_portfolio_type" value="<?php echo esc_attr( $type ); ?>" placeholder="e.g. WooCommerce E-commerce Optimization">
		</div>
	</div>
	<div class="yg-meta-row-3">
		<div class="yg-meta-field">
			<label for="_portfolio_client">Client</label>
			<input type="text" id="_portfolio_client" name="_portfolio_client" value="<?php echo esc_attr( $client ); ?>" placeholder="Client or company name">
		</div>
		<div class="yg-meta-field">
			<label for="_portfolio_year">Year</label>
			<input type="text" id="_portfolio_year" name="_portfolio_year" value="<?php echo esc_attr( $year ); ?>" placeholder="e.g. 2024">
		</div>
		<div class="yg-meta-field">
			<label for="_portfolio_role">My Role</label>
			<input type="text" id="_portfolio_role" name="_portfolio_role" value="<?php echo esc_attr( $role ); ?>" placeholder="e.g. WooCommerce Developer">
		</div>
	</div>
	<div class="yg-meta-field">
		<label for="_portfolio_url">Live Project URL</label>
		<input type="url" id="_portfolio_url" name="_portfolio_url" value="<?php echo esc_attr( $url ); ?>" placeholder="https://example.com">
		<p class="yg-meta-hint">Leave empty if confidential or not public.</p>
	</div>
	<?php
}

// ── RENDER: Project Story ─────────────────────────────────────────────────────

function yg_portfolio_mb_story( WP_Post $post ): void {
	yg_portfolio_mb_styles();

	$summary  = get_post_meta( $post->ID, '_portfolio_summary',  true );
	$problem  = get_post_meta( $post->ID, '_portfolio_problem',  true );
	$solution = get_post_meta( $post->ID, '_portfolio_solution', true );
	$value    = get_post_meta( $post->ID, '_portfolio_value',    true );
	?>
	<div class="yg-meta-field">
		<label for="_portfolio_summary">Project Summary</label>
		<textarea id="_portfolio_summary" name="_portfolio_summary" placeholder="1–2 sentence overview for the project hero..."><?php echo esc_textarea( $summary ); ?></textarea>
	</div>
	<div class="yg-meta-field">
		<label for="_portfolio_problem">Challenge / Problem</label>
		<textarea id="_portfolio_problem" name="_portfolio_problem" style="min-height:100px;" placeholder="Describe the business problem or technical challenge..."><?php echo esc_textarea( $problem ); ?></textarea>
	</div>
	<div class="yg-meta-field">
		<label for="_portfolio_solution">Solution</label>
		<textarea id="_portfolio_solution" name="_portfolio_solution" style="min-height:100px;" placeholder="Describe your approach and what you built..."><?php echo esc_textarea( $solution ); ?></textarea>
	</div>
	<div class="yg-meta-field">
		<label for="_portfolio_value">Business Value</label>
		<textarea id="_portfolio_value" name="_portfolio_value" style="min-height:100px;" placeholder="Describe the measurable outcome for the client..."><?php echo esc_textarea( $value ); ?></textarea>
	</div>
	<?php
}

// ── RENDER: Results & Highlights ─────────────────────────────────────────────

function yg_portfolio_mb_results( WP_Post $post ): void {
	yg_portfolio_mb_styles();

	$outcomes        = get_post_meta( $post->ID, '_portfolio_outcomes',        true );
	$tech_highlights = get_post_meta( $post->ID, '_portfolio_tech_highlights', true );
	?>
	<div class="yg-meta-field">
		<label for="_portfolio_outcomes">Outcomes / Results</label>
		<textarea id="_portfolio_outcomes" name="_portfolio_outcomes" style="min-height:120px;" placeholder="One outcome per line:&#10;Reduced checkout abandonment by 23%&#10;Automated 40+ hours of manual work per month"><?php echo esc_textarea( $outcomes ); ?></textarea>
		<p class="yg-meta-hint">One result per line — rendered as a bullet list on the project page.</p>
	</div>
	<div class="yg-meta-field">
		<label for="_portfolio_tech_highlights">Technical Highlights</label>
		<textarea id="_portfolio_tech_highlights" name="_portfolio_tech_highlights" style="min-height:120px;" placeholder="One highlight per line:&#10;Custom WooCommerce action scheduler integration&#10;REST API endpoint with rate limiting"><?php echo esc_textarea( $tech_highlights ); ?></textarea>
		<p class="yg-meta-hint">One highlight per line — rendered as a bullet list under the technical section.</p>
	</div>
	<?php
}

// ── RENDER: Display & CTA ────────────────────────────────────────────────────

function yg_portfolio_mb_display( WP_Post $post ): void {
	yg_portfolio_mb_styles();

	$specialties  = get_post_meta( $post->ID, '_portfolio_specialties',  true ) ?: [];
	$accent_color = get_post_meta( $post->ID, '_portfolio_accent_color', true ) ?: '#6366f1';
	$confidential = get_post_meta( $post->ID, '_portfolio_confidential', true );
	$anonymous    = get_post_meta( $post->ID, '_portfolio_anonymous',    true );
	$cta_label    = get_post_meta( $post->ID, '_portfolio_cta_label',    true ) ?: 'Contact Me';
	$cta_url      = get_post_meta( $post->ID, '_portfolio_cta_url',      true ) ?: '';

	$all_specialties = [
		'audit'       => 'WooCommerce Store Audit & Optimization',
		'plugin'      => 'Custom WordPress Plugin Development',
		'api'         => 'WooCommerce API Integrations',
		'performance' => 'WooCommerce Performance Troubleshooting',
		'automation'  => 'E-commerce Automation Workflows',
	];
	?>
	<div class="yg-meta-field">
		<label>Specialties Demonstrated (check all that apply)</label>
		<div class="yg-meta-checks yg-meta-checks-grid">
			<?php foreach ( $all_specialties as $key => $label ) : ?>
				<label>
					<input type="checkbox" name="_portfolio_specialties[]" value="<?php echo esc_attr( $key ); ?>"
						<?php checked( in_array( $key, (array) $specialties, true ) ); ?>>
					<?php echo esc_html( $label ); ?>
				</label>
			<?php endforeach; ?>
		</div>
		<p class="yg-meta-hint">Shown in the "Connected Specialties" section on the project page.</p>
	</div>
	<div class="yg-meta-row">
		<div class="yg-meta-field">
			<label for="_portfolio_accent_color">Accent Color</label>
			<div class="yg-color-row">
				<input type="color" id="_portfolio_accent_color" name="_portfolio_accent_color" value="<?php echo esc_attr( $accent_color ); ?>">
				<input type="text" id="_portfolio_accent_color_text" value="<?php echo esc_attr( $accent_color ); ?>" placeholder="#6366f1">
			</div>
			<p class="yg-meta-hint">Hex color used as the project's accent theme.</p>
		</div>
		<div class="yg-meta-field">
			<label>Visibility</label>
			<div class="yg-meta-checks" style="display:flex; flex-direction:column; gap:8px; margin-top:4px;">
				<label>
					<input type="checkbox" name="_portfolio_confidential" value="1" <?php checked( $confidential, '1' ); ?>>
					Mark as Confidential (hides client name &amp; URL)
				</label>
				<label>
					<input type="checkbox" name="_portfolio_anonymous" value="1" <?php checked( $anonymous, '1' ); ?>>
					Show as Anonymous Client
				</label>
			</div>
		</div>
	</div>
	<div class="yg-meta-row">
		<div class="yg-meta-field">
			<label for="_portfolio_cta_label">CTA Button Label</label>
			<input type="text" id="_portfolio_cta_label" name="_portfolio_cta_label" value="<?php echo esc_attr( $cta_label ); ?>" placeholder="Contact Me">
		</div>
		<div class="yg-meta-field">
			<label for="_portfolio_cta_url">CTA Button URL</label>
			<input type="url" id="_portfolio_cta_url" name="_portfolio_cta_url" value="<?php echo esc_attr( $cta_url ); ?>" placeholder="https://...">
			<p class="yg-meta-hint">Leave empty to default to the contact section.</p>
		</div>
	</div>
	<script>
	(function () {
		var picker = document.getElementById('_portfolio_accent_color');
		var text   = document.getElementById('_portfolio_accent_color_text');
		if (!picker || !text) return;
		picker.addEventListener('input', function () {
			text.value = picker.value;
			// Write the chosen hex back to the real input
			picker.name = '_portfolio_accent_color';
		});
		text.addEventListener('input', function () {
			if (/^#[0-9a-fA-F]{6}$/.test(text.value)) {
				picker.value = text.value;
				picker.name  = '_portfolio_accent_color';
			}
		});
	})();
	</script>
	<?php
}

// ── RENDER: Gallery ───────────────────────────────────────────────────────────

function yg_portfolio_mb_gallery( WP_Post $post ): void {
	yg_portfolio_mb_styles();

	$gallery_urls = get_post_meta( $post->ID, '_portfolio_gallery_urls', true );
	?>
	<div class="yg-meta-field">
		<label for="_portfolio_gallery_urls">Gallery Image URLs</label>
		<textarea id="_portfolio_gallery_urls" name="_portfolio_gallery_urls" style="min-height:120px;" placeholder="One URL per line:&#10;https://example.com/img1.jpg&#10;https://example.com/img2.jpg"><?php echo esc_textarea( $gallery_urls ); ?></textarea>
		<p class="yg-meta-hint">One image URL per line. Upload images to the Media Library and paste the URLs here. The featured image is the project hero — gallery images appear below the technical section.</p>
	</div>
	<?php
}

// ── SAVE ──────────────────────────────────────────────────────────────────────

function yg_portfolio_save_meta( int $post_id ): void {
	if ( ! isset( $_POST['yg_portfolio_nonce'] ) ) {
		return;
	}
	if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['yg_portfolio_nonce'] ) ), 'yg_portfolio_save_meta' ) ) {
		return;
	}
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	$text_fields = [
		'_portfolio_subtitle', '_portfolio_type', '_portfolio_client',
		'_portfolio_year', '_portfolio_role', '_portfolio_cta_label',
	];
	$url_fields      = [ '_portfolio_url', '_portfolio_cta_url' ];
	$textarea_fields = [
		'_portfolio_summary', '_portfolio_problem', '_portfolio_solution',
		'_portfolio_value', '_portfolio_outcomes', '_portfolio_tech_highlights',
		'_portfolio_gallery_urls',
	];

	foreach ( $text_fields as $field ) {
		if ( array_key_exists( $field, $_POST ) ) {
			update_post_meta( $post_id, $field, sanitize_text_field( wp_unslash( $_POST[ $field ] ) ) );
		}
	}

	foreach ( $url_fields as $field ) {
		if ( array_key_exists( $field, $_POST ) ) {
			update_post_meta( $post_id, $field, esc_url_raw( wp_unslash( $_POST[ $field ] ) ) );
		}
	}

	foreach ( $textarea_fields as $field ) {
		if ( array_key_exists( $field, $_POST ) ) {
			update_post_meta( $post_id, $field, sanitize_textarea_field( wp_unslash( $_POST[ $field ] ) ) );
		}
	}

	// Accent color (hex only)
	if ( ! empty( $_POST['_portfolio_accent_color'] ) ) {
		$hex = sanitize_hex_color( wp_unslash( $_POST['_portfolio_accent_color'] ) );
		if ( $hex ) {
			update_post_meta( $post_id, '_portfolio_accent_color', $hex );
		}
	}

	// Checkboxes
	update_post_meta( $post_id, '_portfolio_confidential', isset( $_POST['_portfolio_confidential'] ) ? '1' : '' );
	update_post_meta( $post_id, '_portfolio_anonymous',    isset( $_POST['_portfolio_anonymous'] )    ? '1' : '' );

	// Specialties array — whitelist allowed values
	$allowed_specialties = [ 'audit', 'plugin', 'api', 'performance', 'automation' ];
	$selected            = [];
	if ( ! empty( $_POST['_portfolio_specialties'] ) && is_array( $_POST['_portfolio_specialties'] ) ) {
		foreach ( $_POST['_portfolio_specialties'] as $s ) {
			$s = sanitize_key( $s );
			if ( in_array( $s, $allowed_specialties, true ) ) {
				$selected[] = $s;
			}
		}
	}
	update_post_meta( $post_id, '_portfolio_specialties', $selected );
}
add_action( 'save_post_portfolio', 'yg_portfolio_save_meta' );
