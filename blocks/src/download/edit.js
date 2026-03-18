/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { useBlockProps, InspectorControls } from '@wordpress/block-editor';
import { PanelBody, SelectControl, Placeholder, Spinner } from '@wordpress/components';
import ServerSideRender from '@wordpress/server-side-render';
import { useSelect } from '@wordpress/data';

/**
 * Render the PMPro Download block in the editor.
 *
 * @param {Object} props Block props.
 * @return {WPElement} Element to render.
 */
export default function Edit( { attributes, setAttributes } ) {
	const blockProps = useBlockProps();
	const { id, template, label } = attributes;

	// Fetch all published pmpro_download posts.
	const { downloads, isLoading } = useSelect( ( select ) => {
		const { getEntityRecords, isResolving } = select( 'core' );
		return {
			downloads: getEntityRecords( 'postType', 'pmpro_download', {
				per_page: 100,
				orderby: 'title',
				order: 'asc',
				status: 'publish',
			} ) || [],
			isLoading: isResolving( 'getEntityRecords', [ 'postType', 'pmpro_download', {
				per_page: 100,
				orderby: 'title',
				order: 'asc',
				status: 'publish',
			} ] ),
		};
	}, [] );

	// Build options for the download selector.
	const downloadOptions = [
		{ value: 0, label: __( '— Select a Download —', 'pmpro-downloads' ) },
		...downloads.map( ( download ) => ( {
			value: download.id,
			label: download.title.rendered,
		} ) ),
	];

	// Template options.
	const templateOptions = [
		{ value: 'link', label: __( 'Link', 'pmpro-downloads' ) },
		{ value: 'card', label: __( 'Card', 'pmpro-downloads' ) },
		{ value: 'button', label: __( 'Button', 'pmpro-downloads' ) },
	];

	// Label options.
	const labelOptions = [
		{ value: 'title', label: __( 'Title', 'pmpro-downloads' ) },
		{ value: 'filename', label: __( 'Filename', 'pmpro-downloads' ) },
	];

	// If no download selected, show placeholder.
	if ( ! id ) {
		return (
			<div { ...blockProps }>
				<Placeholder
					icon="download"
					label={ __( 'PMPro Download', 'pmpro-downloads' ) }
					instructions={ __( 'Select a download to display.', 'pmpro-downloads' ) }
				>
					{ isLoading ? (
						<Spinner />
					) : (
						<SelectControl
							label={ __( 'Download', 'pmpro-downloads' ) }
							value={ id }
							options={ downloadOptions }
							onChange={ ( value ) => setAttributes( { id: parseInt( value, 10 ) } ) }
						/>
					) }
				</Placeholder>
			</div>
		);
	}

	return (
		<>
			<InspectorControls>
				<PanelBody title={ __( 'Download Settings', 'pmpro-downloads' ) }>
					<SelectControl
						label={ __( 'Download', 'pmpro-downloads' ) }
						value={ id }
						options={ downloadOptions }
						onChange={ ( value ) => setAttributes( { id: parseInt( value, 10 ) } ) }
					/>
					<SelectControl
						label={ __( 'Template', 'pmpro-downloads' ) }
						help={ __( 'Choose how the download is displayed.', 'pmpro-downloads' ) }
						value={ template }
						options={ templateOptions }
						onChange={ ( value ) => setAttributes( { template: value } ) }
					/>
					<SelectControl
						label={ __( 'Label', 'pmpro-downloads' ) }
						help={ __( 'Display the download title or the filename.', 'pmpro-downloads' ) }
						value={ label }
						options={ labelOptions }
						onChange={ ( value ) => setAttributes( { label: value } ) }
					/>
				</PanelBody>
			</InspectorControls>
			<div { ...blockProps }>
				<ServerSideRender
					block="pmpro-downloads/download"
					attributes={ attributes }
				/>
			</div>
		</>
	);
}
