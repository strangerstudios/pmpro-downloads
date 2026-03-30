/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { registerPlugin } from '@wordpress/plugins';
import { PluginDocumentSettingPanel } from '@wordpress/editor';
import { TextareaControl } from '@wordpress/components';
import { useSelect } from '@wordpress/data';
import { useEntityProp } from '@wordpress/core-data';

/**
 * Description sidebar panel for the pmpro_download CPT block editor.
 *
 * Renders a PluginDocumentSettingPanel with a textarea for the optional
 * download description, which is stored in post meta and displayed with
 * the card template.
 *
 * @return {WPElement|null} Panel, or null if not on the pmpro_download post type.
 */
function DownloadDescriptionPanel() {
	const postType = useSelect(
		( select ) => select( 'core/editor' ).getCurrentPostType(),
		[]
	);

	const [ meta, setMeta ] = useEntityProp( 'postType', 'pmpro_download', 'meta' );

	if ( postType !== 'pmpro_download' ) {
		return null;
	}

	const description = meta?._pmpro_download_description || '';

	return (
		<PluginDocumentSettingPanel
			name="pmpro-download-description"
			title={ __( 'Description', 'pmpro-downloads' ) }
			initialOpen={ false }
		>
			<TextareaControl
				label={ __( 'Description', 'pmpro-downloads' ) }
				hideLabelFromVision={ true }
				value={ description }
				onChange={ ( value ) =>
					setMeta( { ...meta, _pmpro_download_description: value } )
				}
				rows={ 4 }
				help={ __( 'An optional brief description that appears alongside the download in supported displays.', 'pmpro-downloads' ) }
			/>
		</PluginDocumentSettingPanel>
	);
}

registerPlugin( 'pmpro-downloads-editor', {
	render: DownloadDescriptionPanel,
} );
