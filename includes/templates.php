<?php
/**
 * Template system for displaying downloads on the frontend.
 *
 * @since 1.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Get the path to a template file.
 *
 * @since 1.0
 *
 * @param string $template The template name (link, card, button).
 * @return string Full path to the template file, or empty string if not found.
 */
function pmpro_downloads_get_template_path( $template ) {
	$template = sanitize_file_name( $template );
	$path     = PMPRO_DOWNLOADS_DIR . '/templates/' . $template . '.php';

	if ( file_exists( $path ) ) {
		return $path;
	}

	return '';
}

/**
 * Render a template with the given variables.
 *
 * @since 1.0
 *
 * @param string $template      The template name (link, card, button).
 * @param array  $template_vars Associative array of variables available in the template as $template_vars.
 * @return string Rendered HTML output.
 */
function pmpro_downloads_render_template( $template, $template_vars ) {
	$template_path = pmpro_downloads_get_template_path( $template );

	if ( empty( $template_path ) ) {
		return '';
	}

	ob_start();
	include $template_path;
	return ob_get_clean();
}

/**
 * Get the file extension from a filename.
 *
 * @since 1.0
 *
 * @param string $filename The filename.
 * @return string Lowercase file extension, or empty string.
 */
function pmpro_downloads_get_file_extension( $filename ) {
	if ( empty( $filename ) ) {
		return '';
	}
	return strtolower( pathinfo( $filename, PATHINFO_EXTENSION ) );
}

/**
 * Get a human-readable file type label from a file extension.
 *
 * @since 1.0
 *
 * @param string $file_extension Lowercase file extension.
 * @return string Human-readable label (e.g. "PDF", "Word Document").
 */
function pmpro_downloads_get_file_type_label( $file_extension ) {
	$labels = array(
		'pdf'  => __( 'PDF', 'pmpro-downloads' ),
		'doc'  => __( 'Word Document', 'pmpro-downloads' ),
		'docx' => __( 'Word Document', 'pmpro-downloads' ),
		'xls'  => __( 'Spreadsheet', 'pmpro-downloads' ),
		'xlsx' => __( 'Spreadsheet', 'pmpro-downloads' ),
		'csv'  => __( 'Spreadsheet', 'pmpro-downloads' ),
		'ppt'  => __( 'Presentation', 'pmpro-downloads' ),
		'pptx' => __( 'Presentation', 'pmpro-downloads' ),
		'zip'  => __( 'Archive', 'pmpro-downloads' ),
		'rar'  => __( 'Archive', 'pmpro-downloads' ),
		'mp3'  => __( 'Audio', 'pmpro-downloads' ),
		'wav'  => __( 'Audio', 'pmpro-downloads' ),
		'mp4'  => __( 'Video', 'pmpro-downloads' ),
		'mov'  => __( 'Video', 'pmpro-downloads' ),
		'jpg'  => __( 'Image', 'pmpro-downloads' ),
		'jpeg' => __( 'Image', 'pmpro-downloads' ),
		'png'  => __( 'Image', 'pmpro-downloads' ),
		'gif'  => __( 'Image', 'pmpro-downloads' ),
		'svg'  => __( 'Image', 'pmpro-downloads' ),
		'txt'  => __( 'Text File', 'pmpro-downloads' ),
	);

	if ( isset( $labels[ $file_extension ] ) ) {
		return $labels[ $file_extension ];
	}

	// Fallback: uppercase the extension.
	if ( ! empty( $file_extension ) ) {
		return strtoupper( $file_extension );
	}

	return __( 'File', 'pmpro-downloads' );
}

/**
 * Get an inline SVG icon.
 *
 * Uses Feather icon style to match PMPro core.
 *
 * @since 1.0
 *
 * @param string $icon The icon name (lock, download, file-text, file).
 * @param int    $size Icon size in pixels. Default 24.
 * @return string SVG markup.
 */
function pmpro_downloads_get_icon_svg( $icon, $size = 24 ) {
	$size = absint( $size );

	$icons = array(
		'lock'      => '<svg xmlns="http://www.w3.org/2000/svg" width="' . $size . '" height="' . $size . '" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-lock" aria-hidden="true"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>',
		'download'  => '<svg xmlns="http://www.w3.org/2000/svg" width="' . $size . '" height="' . $size . '" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-download" aria-hidden="true"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>',
		'file-text' => '<svg xmlns="http://www.w3.org/2000/svg" width="' . $size . '" height="' . $size . '" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-file-text" aria-hidden="true"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>',
		'file'      => '<svg xmlns="http://www.w3.org/2000/svg" width="' . $size . '" height="' . $size . '" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-file" aria-hidden="true"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline></svg>',
	);

	if ( isset( $icons[ $icon ] ) ) {
		return $icons[ $icon ];
	}

	return isset( $icons['file'] ) ? $icons['file'] : '';
}

/**
 * Get the file type icon SVG for a given file extension.
 *
 * @since 1.0
 *
 * @param string $file_extension Lowercase file extension.
 * @param int    $size           Icon size in pixels. Default 24.
 * @return string SVG markup.
 */
function pmpro_downloads_get_file_icon( $file_extension, $size = 24 ) {
	$text_types = array( 'pdf', 'doc', 'docx', 'txt', 'rtf', 'odt', 'xls', 'xlsx', 'csv', 'ppt', 'pptx' );

	if ( in_array( $file_extension, $text_types, true ) ) {
		return pmpro_downloads_get_icon_svg( 'file-text', $size );
	}

	return pmpro_downloads_get_icon_svg( 'file', $size );
}

/**
 * Get the URL to direct non-members for a restricted download.
 *
 * If only one membership level has access, returns the checkout URL for that level.
 * If multiple levels have access, returns the membership levels page URL.
 *
 * @since 1.0
 *
 * @param array $level_ids Array of membership level IDs that have access.
 * @return string The URL, or empty string if PMPro pages are not configured.
 */
function pmpro_downloads_get_no_access_url( $level_ids ) {
	if ( ! function_exists( 'pmpro_url' ) ) {
		return '';
	}

	if ( count( $level_ids ) === 1 ) {
		return pmpro_url( 'checkout', '?pmpro_level=' . intval( current( $level_ids ) ) );
	}

	return pmpro_url( 'levels' );
}
