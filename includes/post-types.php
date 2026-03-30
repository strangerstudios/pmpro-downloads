<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register Custom Post Type for Downloads.
 *
 * @since 1.0
 */
function pmpro_downloads_cpt() {
	$labels = array(
		'name'                  => esc_html_x( 'Downloads', 'Post Type General Name', 'pmpro-downloads' ),
		'singular_name'         => esc_html_x( 'Download', 'Post Type Singular Name', 'pmpro-downloads' ),
		'menu_name'             => esc_html__( 'Downloads', 'pmpro-downloads' ),
		'name_admin_bar'        => esc_html__( 'Download', 'pmpro-downloads' ),
		'archives'              => esc_html__( 'Download Archives', 'pmpro-downloads' ),
		'attributes'            => esc_html__( 'Download Attributes', 'pmpro-downloads' ),
		'all_items'             => esc_html__( 'All Downloads', 'pmpro-downloads' ),
		'add_new_item'          => esc_html__( 'Add New Download', 'pmpro-downloads' ),
		'add_new'               => esc_html__( 'Add New Download', 'pmpro-downloads' ),
		'new_item'              => esc_html__( 'New Download', 'pmpro-downloads' ),
		'edit_item'             => esc_html__( 'Edit Download', 'pmpro-downloads' ),
		'update_item'           => esc_html__( 'Update Download', 'pmpro-downloads' ),
		'view_item'             => esc_html__( 'View Download', 'pmpro-downloads' ),
		'view_items'            => esc_html__( 'View Downloads', 'pmpro-downloads' ),
		'search_items'          => esc_html__( 'Search Downloads', 'pmpro-downloads' ),
		'not_found'             => esc_html__( 'Download not found', 'pmpro-downloads' ),
		'not_found_in_trash'    => esc_html__( 'Download not found in Trash', 'pmpro-downloads' ),
		'featured_image'        => esc_html__( 'Featured Image', 'pmpro-downloads' ),
		'set_featured_image'    => esc_html__( 'Set download featured image', 'pmpro-downloads' ),
		'remove_featured_image' => esc_html__( 'Remove featured image', 'pmpro-downloads' ),
		'use_featured_image'    => esc_html__( 'Use as download featured image', 'pmpro-downloads' ),
		'insert_into_item'      => esc_html__( 'Insert into download', 'pmpro-downloads' ),
		'uploaded_to_this_item' => esc_html__( 'Uploaded to this download', 'pmpro-downloads' ),
		'items_list'            => esc_html__( 'Downloads list', 'pmpro-downloads' ),
		'items_list_navigation' => esc_html__( 'Downloads list navigation', 'pmpro-downloads' ),
		'filter_items_list'     => esc_html__( 'Filter Downloads list', 'pmpro-downloads' ),
	);

	$args = array(
		'label'               => esc_html__( 'Download', 'pmpro-downloads' ),
		'description'         => esc_html__( 'Restricted file downloads for members.', 'pmpro-downloads' ),
		'labels'              => $labels,
		'supports'            => array( 'title', 'editor', 'custom-fields' ),
		'hierarchical'        => false,
		'public'              => false,
		'menu_icon'           => 'dashicons-download',
		'show_ui'             => true,
		'show_in_menu'        => true,
		'show_in_admin_bar'   => true,
		'show_in_nav_menus'   => false,
		'can_export'          => true,
		'has_archive'         => false,
		'exclude_from_search' => true,
		'publicly_queryable'  => false,
		'rewrite'             => false,
		'capability_type'     => 'page',
		'show_in_rest'        => true,
		'template'            => array(
			array( 'pmpro-downloads/file-manager', array() ),
		),
		'template_lock'       => 'all',
	);

	register_post_type( 'pmpro_download', $args );

	// Prevent themes/plugins from adding irrelevant UI to this CPT.
	remove_post_type_support( 'pmpro_download', 'thumbnail' );
	remove_post_type_support( 'pmpro_download', 'page-attributes' );
}
add_action( 'init', 'pmpro_downloads_cpt', 30 );

/**
 * Register post meta fields for downloads.
 *
 * @since 1.0
 */
function pmpro_downloads_register_meta() {
	// Publicly readable meta (exposed via REST for the block editor).
	register_post_meta( 'pmpro_download', '_pmpro_download_uploaded_filename', array(
		'show_in_rest'      => true,
		'single'            => true,
		'type'              => 'string',
		'default'           => '',
		'auth_callback'     => function() {
			return current_user_can( 'edit_posts' );
		},
		'sanitize_callback' => 'sanitize_text_field',
	) );

	register_post_meta( 'pmpro_download', '_pmpro_download_file_type', array(
		'show_in_rest'      => true,
		'single'            => true,
		'type'              => 'string',
		'default'           => '',
		'auth_callback'     => function() {
			return current_user_can( 'edit_posts' );
		},
		'sanitize_callback' => 'sanitize_text_field',
	) );

	register_post_meta( 'pmpro_download', '_pmpro_download_file_size', array(
		'show_in_rest'  => true,
		'single'        => true,
		'type'          => 'integer',
		'default'       => 0,
		'auth_callback' => function() {
			return current_user_can( 'edit_posts' );
		},
	) );

	register_post_meta( 'pmpro_download', '_pmpro_download_description', array(
		'show_in_rest'      => true,
		'single'            => true,
		'type'              => 'string',
		'default'           => '',
		'auth_callback'     => function() {
			return current_user_can( 'edit_posts' );
		},
		'sanitize_callback' => 'sanitize_textarea_field',
	) );

	// Internal meta — not exposed via REST.
	register_post_meta( 'pmpro_download', '_pmpro_download_stored_filename', array(
		'show_in_rest'      => false,
		'single'            => true,
		'type'              => 'string',
		'default'           => '',
		'sanitize_callback' => 'sanitize_file_name',
	) );

	register_post_meta( 'pmpro_download', '_pmpro_download_upload_error', array(
		'show_in_rest'      => false,
		'single'            => true,
		'type'              => 'string',
		'default'           => '',
		'sanitize_callback' => 'sanitize_text_field',
	) );
}
add_action( 'init', 'pmpro_downloads_register_meta' );

/**
 * Register a computed REST field that returns the download URL for the current user.
 *
 * Returns an empty string if the user does not have membership access,
 * so the block editor can conditionally show a "View File" link.
 *
 * @since 1.0
 */
function pmpro_downloads_register_rest_fields() {
	register_rest_field( 'pmpro_download', 'pmpro_download_url', array(
		'get_callback' => function( $post ) {
			$post_id = $post['id'];
			if ( ! function_exists( 'pmpro_has_membership_access' ) ) {
				return '';
			}
			$access     = pmpro_has_membership_access( $post_id, null, true );
			$has_access = is_array( $access ) ? $access[0] : (bool) $access;
			if ( ! $has_access ) {
				return '';
			}
			return pmpro_downloads_get_download_url( $post_id );
		},
		'update_callback' => null,
		'schema'          => array(
			'type'        => 'string',
			'description' => 'Download URL for the current user, or empty if access is denied.',
			'context'     => array( 'edit' ),
		),
	) );
}
add_action( 'rest_api_init', 'pmpro_downloads_register_rest_fields' );

/**
 * Register REST API routes for file management.
 *
 * @since 1.0
 */
function pmpro_downloads_register_rest_routes() {
	register_rest_route(
		'pmpro-downloads/v1',
		'/upload/(?P<id>\d+)',
		array(
			array(
				'methods'             => 'POST',
				'callback'            => 'pmpro_downloads_rest_upload_file',
				'permission_callback' => function( $request ) {
					return current_user_can( 'edit_post', (int) $request['id'] );
				},
				'args'                => array(
					'id' => array(
						'required'          => true,
						'validate_callback' => function( $param ) {
							return is_numeric( $param ) && intval( $param ) > 0;
						},
					),
				),
			),
			array(
				'methods'             => 'DELETE',
				'callback'            => 'pmpro_downloads_rest_delete_file',
				'permission_callback' => function( $request ) {
					return current_user_can( 'edit_post', (int) $request['id'] );
				},
				'args'                => array(
					'id' => array(
						'required'          => true,
						'validate_callback' => function( $param ) {
							return is_numeric( $param ) && intval( $param ) > 0;
						},
					),
				),
			),
		)
	);
}
add_action( 'rest_api_init', 'pmpro_downloads_register_rest_routes' );

/**
 * REST API callback: upload a file for a download post.
 *
 * @since 1.0
 *
 * @param WP_REST_Request $request The REST request.
 * @return WP_REST_Response|WP_Error
 */
function pmpro_downloads_rest_upload_file( WP_REST_Request $request ) {
	$post_id = intval( $request['id'] );

	if ( 'pmpro_download' !== get_post_type( $post_id ) ) {
		return new WP_Error( 'invalid_post_type', __( 'Invalid post type.', 'pmpro-downloads' ), array( 'status' => 400 ) );
	}

	$files = $request->get_file_params();
	if ( empty( $files['file'] ) || empty( $files['file']['name'] ) ) {
		return new WP_Error( 'no_file', __( 'No file was uploaded.', 'pmpro-downloads' ), array( 'status' => 400 ) );
	}

	if ( ! function_exists( 'wp_handle_upload' ) ) {
		require_once ABSPATH . 'wp-admin/includes/file.php';
	}

	if ( ! function_exists( 'pmpro_get_restricted_file_path' ) ) {
		return new WP_Error( 'pmpro_missing', __( 'Paid Memberships Pro is required to upload protected files.', 'pmpro-downloads' ), array( 'status' => 500 ) );
	}

	if ( function_exists( 'pmpro_set_up_restricted_files_directory' ) ) {
		pmpro_set_up_restricted_files_directory();
	}

	$restricted_path = pmpro_get_restricted_file_path( 'pmpro-downloads' );
	if ( empty( $restricted_path ) ) {
		return new WP_Error( 'upload_dir_error', __( 'Unable to resolve PMPro restricted uploads directory.', 'pmpro-downloads' ), array( 'status' => 500 ) );
	}

	pmpro_downloads_upload_target_path( $restricted_path );
	add_filter( 'upload_dir', 'pmpro_downloads_custom_upload_dir' );

	$uploaded_file = wp_handle_upload( $files['file'], array( 'test_form' => false ) );

	remove_filter( 'upload_dir', 'pmpro_downloads_custom_upload_dir' );
	pmpro_downloads_upload_target_path( '' );

	if ( ! empty( $uploaded_file['error'] ) || empty( $uploaded_file['file'] ) ) {
		$error_message = ! empty( $uploaded_file['error'] ) ? $uploaded_file['error'] : __( 'The file upload failed.', 'pmpro-downloads' );
		return new WP_Error( 'upload_error', $error_message, array( 'status' => 500 ) );
	}

	// Delete old file only after a successful upload.
	$old_stored_filename = get_post_meta( $post_id, '_pmpro_download_stored_filename', true );
	if ( ! empty( $old_stored_filename ) ) {
		pmpro_downloads_delete_file_by_filename( $old_stored_filename );
	}

	$stored_filename   = basename( $uploaded_file['file'] );
	$uploaded_filename = sanitize_text_field( wp_basename( wp_unslash( $files['file']['name'] ) ) );
	if ( empty( $uploaded_filename ) ) {
		$uploaded_filename = $stored_filename;
	}

	$file_type = $uploaded_file['type'];
	$file_size = filesize( $uploaded_file['file'] );

	update_post_meta( $post_id, '_pmpro_download_stored_filename', $stored_filename );
	update_post_meta( $post_id, '_pmpro_download_uploaded_filename', $uploaded_filename );
	update_post_meta( $post_id, '_pmpro_download_file_type', $file_type );
	update_post_meta( $post_id, '_pmpro_download_file_size', $file_size );

	return rest_ensure_response( array(
		'uploaded_filename' => $uploaded_filename,
		'file_type'         => $file_type,
		'file_size'         => $file_size,
	) );
}

/**
 * REST API callback: delete the file for a download post.
 *
 * @since 1.0
 *
 * @param WP_REST_Request $request The REST request.
 * @return WP_REST_Response|WP_Error
 */
function pmpro_downloads_rest_delete_file( WP_REST_Request $request ) {
	$post_id = intval( $request['id'] );

	if ( 'pmpro_download' !== get_post_type( $post_id ) ) {
		return new WP_Error( 'invalid_post_type', __( 'Invalid post type.', 'pmpro-downloads' ), array( 'status' => 400 ) );
	}

	pmpro_downloads_delete_file( $post_id );

	return rest_ensure_response( array( 'success' => true ) );
}

/**
 * Add the pmpro_download CPT to the list of PMPro restrictable post types.
 *
 * @since 1.0
 *
 * @param array $post_types Array of post types that PMPro can restrict.
 * @return array Modified array of restrictable post types.
 */
function pmpro_downloads_restrictable_post_types( $post_types ) {
	$post_types[] = 'pmpro_download';
	return array_unique( $post_types );
}
add_filter( 'pmpro_restrictable_post_types', 'pmpro_downloads_restrictable_post_types' );

/**
 * Add pmpro_download to the post types filtered by "Filter searches and archives".
 *
 * When the pmpro_filterqueries setting is enabled, this ensures that restricted
 * downloads are excluded from queries (including the download library).
 *
 * @since 1.0
 *
 * @param array $post_types Post types to filter.
 * @return array
 */
function pmpro_downloads_search_filter_post_types( $post_types ) {
	$post_types[] = 'pmpro_download';
	return array_unique( $post_types );
}
add_filter( 'pmpro_search_filter_post_types', 'pmpro_downloads_search_filter_post_types' );

/**
 * Store/retrieve the current upload target path for restricted file uploads.
 *
 * @since 1.0
 *
 * @param string|null $path Optional. Path to store. Pass an empty string to clear.
 * @return string
 */
function pmpro_downloads_upload_target_path( $path = null ) {
	static $upload_target_path = '';

	if ( null !== $path ) {
		$upload_target_path = empty( $path ) ? '' : untrailingslashit( $path );
	}

	return $upload_target_path;
}

/**
 * Filter the upload directory to point to the PMPro restricted files directory.
 *
 * @since 1.0
 *
 * @param array $uploads The upload directory data.
 * @return array Modified upload directory data.
 */
function pmpro_downloads_custom_upload_dir( $uploads ) {
	if ( ! empty( $uploads['error'] ) ) {
		return $uploads;
	}

	$restricted_path = pmpro_downloads_upload_target_path();
	if ( empty( $restricted_path ) ) {
		$uploads['error'] = __( 'Unable to determine PMPro restricted uploads directory.', 'pmpro-downloads' );
		return $uploads;
	}

	if ( ! file_exists( $restricted_path ) && ! wp_mkdir_p( $restricted_path ) ) {
		$uploads['error'] = __( 'Unable to create PMPro restricted downloads directory.', 'pmpro-downloads' );
		return $uploads;
	}

	$uploads['path']    = untrailingslashit( $restricted_path );
	$uploads['url']     = ''; // No public URL for restricted files.
	$uploads['subdir']  = '';
	$uploads['basedir'] = untrailingslashit( $restricted_path );
	$uploads['baseurl'] = '';
	$uploads['error']   = false;

	return $uploads;
}

/**
 * Delete a restricted file by filename.
 *
 * @since 1.0
 *
 * @param string $filename The stored filename.
 */
function pmpro_downloads_delete_file_by_filename( $filename ) {
	if ( empty( $filename ) ) {
		return;
	}

	if ( ! function_exists( 'pmpro_get_restricted_file_path' ) ) {
		return;
	}

	$file_path = pmpro_get_restricted_file_path( 'pmpro-downloads', $filename );
	if ( file_exists( $file_path ) ) {
		unlink( $file_path );
	}
}

/**
 * Delete the file associated with a download post.
 *
 * @since 1.0
 *
 * @param int $post_id The post ID.
 */
function pmpro_downloads_delete_file( $post_id ) {
	$stored_filename = get_post_meta( $post_id, '_pmpro_download_stored_filename', true );
	pmpro_downloads_delete_file_by_filename( $stored_filename );

	delete_post_meta( $post_id, '_pmpro_download_stored_filename' );
	delete_post_meta( $post_id, '_pmpro_download_uploaded_filename' );
	delete_post_meta( $post_id, '_pmpro_download_file_type' );
	delete_post_meta( $post_id, '_pmpro_download_file_size' );
}

/**
 * Delete the associated file when a download post is permanently deleted.
 *
 * @since 1.0
 *
 * @param int $post_id The post ID being deleted.
 */
function pmpro_downloads_before_delete_post( $post_id ) {
	if ( 'pmpro_download' !== get_post_type( $post_id ) ) {
		return;
	}

	pmpro_downloads_delete_file( $post_id );
}
add_action( 'before_delete_post', 'pmpro_downloads_before_delete_post' );

/**
 * Check if the current user can access a restricted download file.
 *
 * Hooks into PMPro's restricted files system to check membership access
 * for files in the pmpro-downloads directory.
 *
 * @since 1.0
 *
 * @param bool   $can_access Whether the user can access the file.
 * @param string $file_dir   Directory of the restricted file.
 * @param string $file       Name of the restricted file.
 * @return bool Whether the user can access the file.
 */
function pmpro_downloads_can_access_restricted_file( $can_access, $file_dir, $file ) {
	if ( 'pmpro-downloads' !== $file_dir ) {
		return $can_access;
	}

	if ( ! function_exists( 'pmpro_has_membership_access' ) ) {
		return false;
	}

	// Find the download post by stored filename.
	$posts = get_posts( array(
		'post_type'              => 'pmpro_download',
		'meta_key'               => '_pmpro_download_stored_filename',
		'meta_value'             => $file,
		'posts_per_page'         => 1,
		'post_status'            => 'publish',
		'fields'                 => 'ids',
		'no_found_rows'          => true,
		'cache_results'          => false,
		'update_post_meta_cache' => false,
		'update_post_term_cache' => false,
	) );

	if ( empty( $posts ) ) {
		return false;
	}

	return pmpro_has_membership_access( $posts[0] );
}
add_filter( 'pmpro_can_access_restricted_file', 'pmpro_downloads_can_access_restricted_file', 10, 3 );

/**
 * Get the download URL for a download post.
 *
 * @since 1.0
 *
 * @param int $post_id The download post ID.
 * @return string The download URL, or empty string if no file is attached.
 */
function pmpro_downloads_get_download_url( $post_id ) {
	$stored_filename = get_post_meta( $post_id, '_pmpro_download_stored_filename', true );
	if ( empty( $stored_filename ) ) {
		return '';
	}

	return add_query_arg( array(
		'pmpro_restricted_file'     => $stored_filename,
		'pmpro_restricted_file_dir' => 'pmpro-downloads',
	), home_url( '/' ) );
}
