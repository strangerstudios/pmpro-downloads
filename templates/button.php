<?php
/**
 * Button template for pmpro-downloads.
 *
 * Displays a download as a prominent CTA button with two-line text.
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
	<div class="pmpro pmpro_download_button">
		<a class="pmpro_btn pmpro_download_btn_cta" href="<?php echo esc_url( $template_vars['download_url'] ); ?>">
			<span class="pmpro_download_btn_icon">
				<?php echo pmpro_downloads_get_icon_svg( 'download', 20 ); ?>
			</span>
			<span class="pmpro_download_btn_text">
				<strong><?php esc_html_e( 'Download', 'pmpro-downloads' ); ?></strong>
				<small><?php echo esc_html( $template_vars['display_name'] ); ?></small>
			</span>
		</a>
	</div>

<?php else : ?>
	<div class="pmpro pmpro_download_button pmpro_download-locked">
		<a class="pmpro_btn pmpro_btn-cancel pmpro_download_btn_cta" href="<?php echo esc_url( $template_vars['no_access_url'] ); ?>">
			<span class="pmpro_download_btn_icon">
				<?php echo pmpro_downloads_get_icon_svg( 'lock', 20 ); ?>
			</span>
			<span class="pmpro_download_btn_text">
				<strong><?php echo esc_html( $template_vars['display_name'] ); ?></strong>
				<small>
					<?php
					/* translators: %s: membership level name(s) */
					printf( esc_html__( 'Requires %s membership', 'pmpro-downloads' ), esc_html( pmpro_implodeToEnglish( $template_vars['level_names'], 'or' ) ) );
					?>
				</small>
			</span>
		</a>
	</div>
<?php endif; ?>
