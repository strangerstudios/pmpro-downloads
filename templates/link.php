<?php
/**
 * Link template for pmpro-downloads.
 *
 * Displays a download as a simple text link with file metadata.
 *
 * @since 0.2
 *
 * @var array $template_vars Template variables passed from the shortcode.
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( $template_vars['has_access'] ) : ?>
	<div class="pmpro_download">
		<a href="<?php echo esc_url( $template_vars['download_url'] ); ?>" class="pmpro_download_link">
			<?php echo pmpro_downloads_get_file_icon( $template_vars['file_extension'], 16 ); ?>
			<?php echo esc_html( $template_vars['display_name'] ); ?>
		</a>
	</div>
<?php else : ?>
	<div class="pmpro_download pmpro_download-locked">
		<a href="<?php echo esc_url( $template_vars['no_access_url'] ); ?>" class="pmpro_download_link">
			<?php echo pmpro_downloads_get_icon_svg( 'lock', 16 ); ?>
			<?php echo esc_html( $template_vars['display_name'] ); ?>
		</a>
		<span class="pmpro_download_meta">
			<?php
			/* translators: %s: membership level name(s) */
			printf( esc_html__( 'Requires %s membership', 'pmpro-downloads' ), esc_html( pmpro_implodeToEnglish( $template_vars['level_names'], 'or' ) ) );
			?>
		</span>
	</div>
<?php endif; ?>
