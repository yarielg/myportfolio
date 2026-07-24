<?php
/**
 * Skills CPT — register, taxonomy, seed data.
 *
 * Admin path: Skills → Add New Skill
 * Taxonomy:   skill_group (hierarchical, for category grouping)
 *
 * Each skill = one post with a title.
 * Group  = skill_group taxonomy term (E-commerce, WordPress/PHP, etc.)
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// ── CPT ────────────────────────────────────────────────────────────────────────

function yg_register_skill_cpt(): void {
	register_post_type( 'yg_skill', [
		'labels' => [
			'name'               => 'Skills',
			'singular_name'      => 'Skill',
			'add_new'            => 'Add New',
			'add_new_item'       => 'Add New Skill',
			'edit_item'          => 'Edit Skill',
			'search_items'       => 'Search Skills',
			'not_found'          => 'No skills found.',
			'not_found_in_trash' => 'No skills in trash.',
			'all_items'          => 'All Skills',
			'menu_name'          => 'Skills',
		],
		'public'        => false,
		'show_ui'       => true,
		'show_in_menu'  => true,
		'show_in_rest'  => true,
		'menu_icon'     => 'dashicons-star-filled',
		'menu_position' => 6,
		'supports'      => [ 'title', 'page-attributes' ],
	] );
}
add_action( 'init', 'yg_register_skill_cpt' );

// ── TAXONOMY: skill_group ──────────────────────────────────────────────────────

function yg_register_skill_group_taxonomy(): void {
	register_taxonomy( 'skill_group', 'yg_skill', [
		'labels' => [
			'name'              => 'Skill Groups',
			'singular_name'     => 'Skill Group',
			'add_new_item'      => 'Add New Group',
			'edit_item'         => 'Edit Group',
			'update_item'       => 'Update Group',
			'search_items'      => 'Search Groups',
			'all_items'         => 'All Groups',
		],
		'hierarchical'      => true,
		'show_in_rest'      => true,
		'show_admin_column' => true,
		'rewrite'           => false,
	] );
}
add_action( 'init', 'yg_register_skill_group_taxonomy' );

// ── ADMIN COLUMNS: show group ──────────────────────────────────────────────────

function yg_skill_admin_columns( array $columns ): array {
	$columns['skill_group'] = 'Group';
	return $columns;
}
add_filter( 'manage_yg_skill_posts_columns', 'yg_skill_admin_columns' );

function yg_skill_admin_column_content( string $column, int $post_id ): void {
	if ( 'skill_group' !== $column ) {
		return;
	}
	$terms = get_the_terms( $post_id, 'skill_group' );
	if ( $terms && ! is_wp_error( $terms ) ) {
		echo esc_html( implode( ', ', wp_list_pluck( $terms, 'name' ) ) );
	} else {
		echo '<span style="color:#aaa;">—</span>';
	}
}
add_action( 'manage_yg_skill_posts_custom_column', 'yg_skill_admin_column_content', 10, 2 );

