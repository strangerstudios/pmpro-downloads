<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register Custom Post Type for Downloads.
 *
 * @since 0.1
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
		'supports'            => array( 'title', 'editor' ),
		'hierarchical'        => false,
		'public'              => true,
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
	);

	register_post_type( 'pmpro_download', $args );

	// Ensure page attributes UI is not available for downloads.
	remove_post_type_support( 'pmpro_download', 'page-attributes' );
}
add_action( 'init', 'pmpro_downloads_cpt', 30 );

/**
 * Add the pmpro_download CPT to the list of PMPro restrictable post types.
 *
 * @since 0.1
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
 * Force the classic editor for the pmpro_download post type.
 *
 * @since 0.1
 *
 * @param bool   $use_block_editor Whether to use the block editor.
 * @param string $post_type        The post type.
 * @return bool
 */
function pmpro_downloads_use_block_editor( $use_block_editor, $post_type ) {
	if ( 'pmpro_download' === $post_type ) {
		return false;
	}
	return $use_block_editor;
}
add_filter( 'use_block_editor_for_post_type', 'pmpro_downloads_use_block_editor', 10, 2 );

/**
 * Add enctype to the post edit form for file uploads.
 *
 * @since 0.1
 */
function pmpro_downloads_post_edit_form_tag() {
	if ( 'pmpro_download' === get_post_type() ) {
		echo ' enctype="multipart/form-data"';
	}
}
add_action( 'post_edit_form_tag', 'pmpro_downloads_post_edit_form_tag' );

/**
 * Register the "Download File" meta box.
 *
 * @since 0.1
 */
function pmpro_downloads_add_meta_boxes() {
	add_meta_box(
		'pmpro_downloads_file',
		esc_html__( 'Protected Download File', 'pmpro-downloads' ),
		'pmpro_downloads_file_meta_box',
		'pmpro_download',
		'normal',
		'high'
	);
}
add_action( 'add_meta_boxes', 'pmpro_downloads_add_meta_boxes' );

/**
 * Remove the attributes metabox for downloads.
 *
 * Run late to ensure core/other plugins have already added default metaboxes.
 *
 * @since 0.1
 */
function pmpro_downloads_remove_attributes_metabox() {
	remove_meta_box( 'pageparentdiv', 'pmpro_download', 'side' );
}
add_action( 'add_meta_boxes_pmpro_download', 'pmpro_downloads_remove_attributes_metabox', 100 );

/**
 * Render the "Download File" meta box.
 *
 * @since 0.1
 *
 * @param WP_Post $post The current post object.
 */
function pmpro_downloads_file_meta_box( $post ) {
	wp_nonce_field( 'pmpro_downloads_save_file', 'pmpro_downloads_file_nonce' );

	$uploaded_filename = get_post_meta( $post->ID, '_pmpro_download_uploaded_filename', true );
	$stored_filename   = get_post_meta( $post->ID, '_pmpro_download_stored_filename', true );
	$file_type         = get_post_meta( $post->ID, '_pmpro_download_file_type', true );
	$file_size         = get_post_meta( $post->ID, '_pmpro_download_file_size', true );
	$upload_error      = get_post_meta( $post->ID, '_pmpro_download_upload_error', true );
	$should_clear_error = ! empty( $upload_error );

	if ( empty( $uploaded_filename ) ) {
		$uploaded_filename = $stored_filename;
	}

	if ( ! empty( $upload_error ) ) {
		?>
		<p class="notice notice-error inline">
			<strong><?php esc_html_e( 'Upload Error:', 'pmpro-downloads' ); ?></strong>
			<?php echo esc_html( $upload_error ); ?>
		</p>
		<?php
	}

	if ( ! empty( $uploaded_filename ) ) {
		?>
		<div class="pmpro_downloads_file_info">
			<p>
				<strong><?php esc_html_e( 'Current File:', 'pmpro-downloads' ); ?></strong>
				<?php echo esc_html( $uploaded_filename ); ?>
			</p>
			<?php if ( ! empty( $stored_filename ) && $stored_filename !== $uploaded_filename ) { ?>
				<p>
					<strong><?php esc_html_e( 'Stored Filename:', 'pmpro-downloads' ); ?></strong>
					<?php echo esc_html( $stored_filename ); ?>
				</p>
			<?php } ?>
			<?php if ( ! empty( $file_type ) ) { ?>
				<p>
					<strong><?php esc_html_e( 'Type:', 'pmpro-downloads' ); ?></strong>
					<?php echo esc_html( $file_type ); ?>
				</p>
			<?php } ?>
			<?php if ( ! empty( $file_size ) ) { ?>
				<p>
					<strong><?php esc_html_e( 'Size:', 'pmpro-downloads' ); ?></strong>
					<?php echo esc_html( size_format( $file_size ) ); ?>
				</p>
			<?php } ?>
			<p>
				<label>
					<input type="checkbox" name="pmpro_downloads_remove_file" value="1" />
					<?php esc_html_e( 'Remove file', 'pmpro-downloads' ); ?>
				</label>
			</p>
			<hr />
			<p>
				<strong><?php esc_html_e( 'Replace File:', 'pmpro-downloads' ); ?></strong>
			</p>
		</div>
		<?php
	}
	?>
	<p>
		<input type="file" name="pmpro_download_file" id="pmpro_download_file" />
	</p>
	<?php if ( empty( $uploaded_filename ) ) { ?>
		<p class="description">
			<?php esc_html_e( 'Upload a file for this download.', 'pmpro-downloads' ); ?>
		</p>
	<?php } ?>
	<?php

	if ( $should_clear_error ) {
		delete_post_meta( $post->ID, '_pmpro_download_upload_error' );
	}
}

/**
 * Save an upload error message for display in the file metabox.
 *
 * @since 0.1
 *
 * @param int    $post_id The post ID.
 * @param string $message The upload error message.
 */
function pmpro_downloads_set_upload_error( $post_id, $message ) {
	if ( empty( $message ) ) {
		delete_post_meta( $post_id, '_pmpro_download_upload_error' );
		return;
	}

	update_post_meta( $post_id, '_pmpro_download_upload_error', sanitize_text_field( $message ) );
}

/**
 * Store/retrieve the current upload target path for restricted file uploads.
 *
 * @since 0.1
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
 * Save the download file on post save.
 *
 * @since 0.1
 *
 * @param int     $post_id The post ID.
 * @param WP_Post $post    The post object.
 * @param bool    $update  Whether this is an existing post being updated.
 */
function pmpro_downloads_save_file( $post_id, $post, $update ) {
	// Verify nonce.
	if ( empty( $_POST['pmpro_downloads_file_nonce'] ) || ! wp_verify_nonce( $_POST['pmpro_downloads_file_nonce'], 'pmpro_downloads_save_file' ) ) {
		return;
	}

	// Check capabilities.
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	// Skip autosave.
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

	pmpro_downloads_set_upload_error( $post_id, '' );

	// Handle file removal.
	if ( ! empty( $_POST['pmpro_downloads_remove_file'] ) ) {
		pmpro_downloads_delete_file( $post_id );
		return;
	}

	// Handle file upload.
	if ( ! empty( $_FILES['pmpro_download_file'] ) && ! empty( $_FILES['pmpro_download_file']['name'] ) ) {
		if ( ! function_exists( 'wp_handle_upload' ) ) {
			require_once ABSPATH . 'wp-admin/includes/file.php';
		}

		if ( ! function_exists( 'pmpro_get_restricted_file_path' ) ) {
			pmpro_downloads_set_upload_error( $post_id, __( 'Paid Memberships Pro is required to upload protected files.', 'pmpro-downloads' ) );
			return;
		}

		if ( function_exists( 'pmpro_set_up_restricted_files_directory' ) ) {
			pmpro_set_up_restricted_files_directory();
		}

		$restricted_path = pmpro_get_restricted_file_path( 'pmpro-downloads' );
		if ( empty( $restricted_path ) ) {
			pmpro_downloads_set_upload_error( $post_id, __( 'Unable to resolve PMPro restricted uploads directory.', 'pmpro-downloads' ) );
			return;
		}

		pmpro_downloads_upload_target_path( $restricted_path );

		// Temporarily filter upload_dir to point to the restricted files directory.
		add_filter( 'upload_dir', 'pmpro_downloads_custom_upload_dir' );

		// Use wp_handle_upload to process the file.
		$uploaded_file = wp_handle_upload( $_FILES['pmpro_download_file'], array( 'test_form' => false ) );

		// Remove the filter.
		remove_filter( 'upload_dir', 'pmpro_downloads_custom_upload_dir' );
		pmpro_downloads_upload_target_path( '' );

		if ( ! empty( $uploaded_file['error'] ) || empty( $uploaded_file['file'] ) ) {
			$error_message = ! empty( $uploaded_file['error'] ) ? $uploaded_file['error'] : __( 'The file upload failed.', 'pmpro-downloads' );
			pmpro_downloads_set_upload_error( $post_id, $error_message );
			return;
		}

		// Delete old file only after a successful replacement upload.
		$old_stored_filename = get_post_meta( $post_id, '_pmpro_download_stored_filename', true );
		if ( ! empty( $old_stored_filename ) ) {
			pmpro_downloads_delete_file_by_filename( $old_stored_filename );
		}

		// Save file metadata as post meta.
		$stored_filename   = basename( $uploaded_file['file'] );
		$uploaded_filename = sanitize_text_field( wp_basename( wp_unslash( $_FILES['pmpro_download_file']['name'] ) ) );
		if ( empty( $uploaded_filename ) ) {
			$uploaded_filename = $stored_filename;
		}

		update_post_meta( $post_id, '_pmpro_download_stored_filename', $stored_filename );
		update_post_meta( $post_id, '_pmpro_download_uploaded_filename', $uploaded_filename );
		update_post_meta( $post_id, '_pmpro_download_file_type', $uploaded_file['type'] );
		update_post_meta( $post_id, '_pmpro_download_file_size', filesize( $uploaded_file['file'] ) );
	}
}
add_action( 'save_post_pmpro_download', 'pmpro_downloads_save_file', 20, 3 );

/**
 * Filter the upload directory to point to the PMPro restricted files directory.
 *
 * @since 0.1
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
 * @since 0.1
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
 * @since 0.1
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
	delete_post_meta( $post_id, '_pmpro_download_upload_error' );
}

/**
 * Delete the associated file when a download post is permanently deleted.
 *
 * @since 0.1
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
 * @since 0.1
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
 * @since 0.1
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
