<?php
/**
 * Card template for pmpro-downloads.
 *
 * Displays a download as a PMPro card with title, description,
 * and action button.
 *
 * Available variables: see template variable contract in includes/templates.php.
 *
 * @since 0.2
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>

<?php if ( $has_access ) : ?>
	<div class="pmpro">
		<div class="pmpro_card pmpro_download_card">
			<h2 class="pmpro_card_title pmpro_font-large">
				<?php echo pmpro_downloads_get_file_icon( $file_extension, 24 ); ?>
				<?php echo esc_html( $display_name ); ?>
			</h2>
			<?php if ( ! empty( $description ) ) : ?>
				<div class="pmpro_card_content">
					<?php echo wp_kses_post( $description ); ?>
				</div>
			<?php endif; ?>
			<div class="pmpro_card_actions">
				<a class="pmpro_btn" href="<?php echo esc_url( $download_url ); ?>">
					<?php echo pmpro_downloads_get_icon_svg( 'download', 16 ); ?>
					<?php esc_html_e( 'Download', 'pmpro-downloads' ); ?>
				</a>
			</div>
		</div>
	</div>

<?php else : ?>
	<div class="pmpro">
		<div class="pmpro_card pmpro_download_card pmpro_download-locked">
			<h2 class="pmpro_card_title pmpro_font-large">
				<?php echo pmpro_downloads_get_icon_svg( 'lock', 24 ); ?>
				<?php echo esc_html( $display_name ); ?>
			</h2>
			<div class="pmpro_card_content">
				<?php if ( ! empty( $description ) ) : ?>
					<?php echo wp_kses_post( $description ); ?>
				<?php endif; ?>
				<p class="pmpro_font-medium">
					<?php
					/* translators: %s: membership level name(s) */
					printf( esc_html__( 'Requires %s membership', 'pmpro-downloads' ), esc_html( pmpro_implodeToEnglish( $level_names, 'or' ) ) );
					?>
				</p>
			</div>
			<div class="pmpro_card_actions">
				<a class="pmpro_btn" href="<?php echo esc_url( $no_access_url ); ?>">
					<?php echo pmpro_downloads_get_icon_svg( 'lock', 16 ); ?>
					<?php
					if ( count( $level_ids ) === 1 ) {
						esc_html_e( 'Join Now', 'pmpro-downloads' );
					} else {
						esc_html_e( 'View Membership Options', 'pmpro-downloads' );
					}
					?>
				</a>
			</div>
		</div>
	</div>
<?php endif; ?>
