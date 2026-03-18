<?php
/**
 * Plugin Name: Paid Memberships Pro - Downloads Add On
 * Plugin URI: https://www.paidmembershipspro.com/add-ons/pmpro-downloads/
 * Description: Create restricted file downloads for members.
 * Version: 0.0.1
 * Author: Paid Memberships Pro
 * Author URI: https://www.paidmembershipspro.com
 * Text Domain: pmpro-downloads
 * Domain Path: /languages
 * License: GPLv2 or later
 */

defined( 'ABSPATH' ) || die( 'File cannot be accessed directly' );

define( 'PMPRO_DOWNLOADS_VERSION', '0.0.1' );
define( 'PMPRO_DOWNLOADS_DIR', dirname( __FILE__ ) );
define( 'PMPRO_DOWNLOADS_BASENAME', plugin_basename( __FILE__ ) );

// Includes.
require_once PMPRO_DOWNLOADS_DIR . '/includes/post-types.php';
require_once PMPRO_DOWNLOADS_DIR . '/includes/templates.php';
require_once PMPRO_DOWNLOADS_DIR . '/includes/shortcodes.php';
require_once PMPRO_DOWNLOADS_DIR . '/includes/shortcodes-library.php';

/**
 * Register block types for the block editor.
 *
 * @since 0.3
 */
function pmpro_downloads_register_block_types() {
	register_block_type( PMPRO_DOWNLOADS_DIR . '/blocks/build/download' );
	register_block_type( PMPRO_DOWNLOADS_DIR . '/blocks/build/downloads' );
}
add_action( 'init', 'pmpro_downloads_register_block_types' );

/**
 * Enqueue PMPro core and downloads frontend styles in the block editor iframe.
 *
 * These are the same CSS files loaded on the frontend, ensuring 1:1 rendering
 * of the download templates in the editor preview.
 *
 * @since 0.3
 */
function pmpro_downloads_enqueue_block_styles() {
	if ( defined( 'PMPRO_URL' ) && defined( 'PMPRO_VERSION' ) ) {
		wp_enqueue_style(
			'pmpro_frontend_base',
			PMPRO_URL . '/css/frontend/base.css',
			array(),
			PMPRO_VERSION
		);

		// Load the same style variation as the frontend.
		$variation = get_option( 'pmpro_style_variation', 'variation_1' );
		if ( 'variation_minimal' !== $variation ) {
			wp_enqueue_style(
				'pmpro_frontend_' . esc_attr( $variation ),
				PMPRO_URL . '/css/frontend/' . esc_attr( $variation ) . '.css',
				array(),
				PMPRO_VERSION
			);
		}
	}

	wp_enqueue_style(
		'pmpro-downloads-frontend',
		plugins_url( 'css/pmpro-downloads-frontend.css', __FILE__ ),
		array(),
		PMPRO_DOWNLOADS_VERSION
	);
}
add_action( 'enqueue_block_assets', 'pmpro_downloads_enqueue_block_styles' );

/**
 * Load text domain for translations.
 *
 * @since 0.1
 */
function pmpro_downloads_load_textdomain() {
	load_plugin_textdomain( 'pmpro-downloads', false, basename( dirname( __FILE__ ) ) . '/languages' );
}
add_action( 'plugins_loaded', 'pmpro_downloads_load_textdomain' );

/**
 * Add documentation and support links to the plugin row meta.
 *
 * @since 0.1
 *
 * @param array  $links Array of existing plugin row meta links.
 * @param string $file  Path to the plugin file relative to the plugins directory.
 * @return array Modified array of plugin row meta links.
 */
function pmpro_downloads_plugin_row_meta( $links, $file ) {
	if ( strpos( $file, 'pmpro-downloads.php' ) !== false ) {
		$new_links = array(
			'<a href="' . esc_url( 'https://www.paidmembershipspro.com/add-ons/pmpro-downloads/' ) . '" title="' . esc_attr__( 'View Documentation', 'pmpro-downloads' ) . '">' . esc_html__( 'Docs', 'pmpro-downloads' ) . '</a>',
			'<a href="' . esc_url( 'https://www.paidmembershipspro.com/support/' ) . '" title="' . esc_attr__( 'Visit Customer Support Forum', 'pmpro-downloads' ) . '">' . esc_html__( 'Support', 'pmpro-downloads' ) . '</a>',
		);
		$links = array_merge( $links, $new_links );
	}
	return $links;
}
add_filter( 'plugin_row_meta', 'pmpro_downloads_plugin_row_meta', 10, 2 );

/**
 * Enqueue frontend styles for download templates.
 *
 * @since 0.2
 */
function pmpro_downloads_enqueue_frontend_styles() {
	if ( is_admin() ) {
		return;
	}

	wp_enqueue_style(
		'pmpro-downloads-frontend',
		plugins_url( 'css/pmpro-downloads-frontend.css', __FILE__ ),
		array(),
		PMPRO_DOWNLOADS_VERSION
	);
}
add_action( 'wp_enqueue_scripts', 'pmpro_downloads_enqueue_frontend_styles' );
