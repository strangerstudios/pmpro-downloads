<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register the Download Categories taxonomy for the pmpro_download CPT.
 *
 * Hierarchical (category-like) so downloads can be grouped and, more importantly,
 * so each category term can be protected by membership level exactly like core
 * PMPro categories.
 *
 * @since TBD
 */
function pmpro_downloads_register_taxonomies() {
	$labels = array(
		'name'                  => esc_html_x( 'Download Categories', 'Taxonomy General Name', 'pmpro-downloads' ),
		'singular_name'         => esc_html_x( 'Download Category', 'Taxonomy Singular Name', 'pmpro-downloads' ),
		'menu_name'             => esc_html__( 'Categories', 'pmpro-downloads' ),
		'all_items'             => esc_html__( 'All Categories', 'pmpro-downloads' ),
		'parent_item'           => esc_html__( 'Parent Category', 'pmpro-downloads' ),
		'parent_item_colon'     => esc_html__( 'Parent Category:', 'pmpro-downloads' ),
		'new_item_name'         => esc_html__( 'New Category Name', 'pmpro-downloads' ),
		'add_new_item'          => esc_html__( 'Add New Category', 'pmpro-downloads' ),
		'edit_item'             => esc_html__( 'Edit Category', 'pmpro-downloads' ),
		'update_item'           => esc_html__( 'Update Category', 'pmpro-downloads' ),
		'view_item'             => esc_html__( 'View Category', 'pmpro-downloads' ),
		'search_items'          => esc_html__( 'Search Categories', 'pmpro-downloads' ),
		'not_found'             => esc_html__( 'Not Found', 'pmpro-downloads' ),
		'no_terms'              => esc_html__( 'No categories', 'pmpro-downloads' ),
		'items_list'            => esc_html__( 'Categories list', 'pmpro-downloads' ),
		'items_list_navigation' => esc_html__( 'Categories list navigation', 'pmpro-downloads' ),
	);

	$args = array(
		'labels'             => $labels,
		'hierarchical'       => true,
		'public'             => false,
		'show_ui'            => true,
		'show_admin_column'  => true,
		'show_in_nav_menus'  => false,
		'show_tagcloud'      => false,
		// Exposed in REST so the category panel appears in the block editor sidebar.
		'show_in_rest'       => true,
	);

	register_taxonomy( 'pmpro_download_category', array( 'pmpro_download' ), $args );
}
add_action( 'init', 'pmpro_downloads_register_taxonomies', 30 );

/**
 * Declare Download Categories as restrictable by membership level.
 *
 * PMPro core (3.8+) handles everything from here: the "Require Membership"
 * checkboxes on the term add/edit screens, access checks in
 * pmpro_has_membership_access(), search/archive filtering, the checklist on
 * the edit level admin page, and cleanup when a term is deleted.
 *
 * @since TBD
 *
 * @param array $taxonomies Taxonomies that PMPro can restrict.
 * @return array Modified array of restrictable taxonomies.
 */
function pmpro_downloads_restrictable_taxonomies( $taxonomies ) {
	$taxonomies[] = 'pmpro_download_category';
	return array_unique( $taxonomies );
}
add_filter( 'pmpro_restrictable_taxonomies', 'pmpro_downloads_restrictable_taxonomies' );
