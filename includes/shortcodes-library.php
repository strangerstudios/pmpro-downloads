<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Shortcode to display a library of downloads.
 *
 * Usage: [pmpro_download_library template="card" layout="grid" columns="2"]
 *
 * @since 1.0
 *
 * @param array $atts Shortcode attributes.
 * @return string Shortcode output.
 */
function pmpro_download_library_shortcode( $atts ) {
	// Bail if PMPro is not active.
	if ( ! function_exists( 'pmpro_has_membership_access' ) ) {
		return '';
	}

	$atts = shortcode_atts( array(
		'template' => 'link',
		'layout'   => 'list',
		'columns'  => 2,
		'label'    => 'title',
		'limit'    => -1,
		'orderby'  => 'title',
		'order'    => 'asc',
	), $atts, 'pmpro_download_library' );

	// Validate attributes.
	$allowed_templates = array( 'link', 'card', 'button' );
	$template          = in_array( $atts['template'], $allowed_templates, true ) ? $atts['template'] : 'link';

	$allowed_layouts = array( 'list', 'grid' );
	$layout          = in_array( $atts['layout'], $allowed_layouts, true ) ? $atts['layout'] : 'list';

	$columns = in_array( intval( $atts['columns'] ), array( 2, 3 ), true ) ? intval( $atts['columns'] ) : 2;

	$allowed_labels = array( 'title', 'filename' );
	$label          = in_array( $atts['label'], $allowed_labels, true ) ? $atts['label'] : 'title';

	$limit = intval( $atts['limit'] );

	$allowed_orderby = array( 'title', 'date' );
	$orderby         = in_array( $atts['orderby'], $allowed_orderby, true ) ? $atts['orderby'] : 'title';

	$allowed_order = array( 'asc', 'desc' );
	$order         = in_array( strtolower( $atts['order'] ), $allowed_order, true ) ? strtoupper( $atts['order'] ) : 'ASC';

	// Query downloads. Uses WP_Query so that PMPro's pmpro_search_filter
	// can exclude restricted downloads when "Filter searches and archives" is enabled.
	$query = new WP_Query( array(
		'post_type'      => 'pmpro_download',
		'post_status'    => 'publish',
		'posts_per_page' => $limit,
		'orderby'        => $orderby,
		'order'          => $order,
	) );

	$downloads = $query->posts;

	if ( empty( $downloads ) ) {
		return '';
	}

	// Render each download using the single download shortcode.
	$items = '';
	foreach ( $downloads as $download ) {
		$items .= pmpro_downloads_shortcode( array(
			'id'       => $download->ID,
			'template' => $template,
			'label'    => $label,
		) );
	}

	if ( empty( $items ) ) {
		return '';
	}

	// Build container CSS classes.
	$classes = array(
		'pmpro_download_library',
		'pmpro_download_library-' . $layout,
	);

	if ( 'grid' === $layout ) {
		$classes[] = 'pmpro_download_library-columns-' . $columns;
	}

	return '<div class="' . esc_attr( implode( ' ', $classes ) ) . '">' . $items . '</div>';
}
add_shortcode( 'pmpro_download_library', 'pmpro_download_library_shortcode' );
