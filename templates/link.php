<?php
/**
 * Link template for pmpro-downloads.
 *
 * Displays a download as a simple text link with file metadata.
 *
 * Available variables: see template variable contract in includes/templates.php.
 *
 * @since 0.2
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( $has_access ) : ?>
	<div class="pmpro_download">
		<a href="<?php echo esc_url( $download_url ); ?>" class="pmpro_download_link">
			<?php echo pmpro_downloads_get_file_icon( $file_extension, 16 ); ?>
			<?php echo esc_html( $display_name ); ?>
		</a>
	</div>
<?php else : ?>
	<div class="pmpro_download pmpro_download-locked">
		<a href="<?php echo esc_url( $no_access_url ); ?>" class="pmpro_download_link">
			<?php echo pmpro_downloads_get_icon_svg( 'lock', 16 ); ?>
			<?php echo esc_html( $display_name ); ?>
		</a>
		<span class="pmpro_download_meta">
			<?php
			/* translators: %s: membership level name(s) */
			printf( esc_html__( 'Requires %s membership', 'pmpro-downloads' ), esc_html( pmpro_implodeToEnglish( $level_names, 'or' ) ) );
			?>
		</span>
	</div>
<?php endif; ?>
