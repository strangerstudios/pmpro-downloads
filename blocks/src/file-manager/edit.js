/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { useBlockProps } from '@wordpress/block-editor';
import { Button, Spinner, Notice } from '@wordpress/components';
import { useSelect } from '@wordpress/data';
import { useEntityProp } from '@wordpress/core-data';
import { useState, useRef } from '@wordpress/element';
import apiFetch from '@wordpress/api-fetch';

/**
 * Feather-style download icon — matches the SVG used in the frontend templates.
 *
 * @param {Object} props
 * @param {number} props.size Icon size in pixels.
 * @return {WPElement}
 */
function DownloadIcon( { size = 32 } ) {
	return (
		<svg
			xmlns="http://www.w3.org/2000/svg"
			width={ size }
			height={ size }
			viewBox="0 0 24 24"
			fill="none"
			stroke="currentColor"
			strokeWidth="2"
			strokeLinecap="round"
			strokeLinejoin="round"
			aria-hidden="true"
		>
			<path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
			<polyline points="7 10 12 15 17 10" />
			<line x1="12" y1="15" x2="12" y2="3" />
		</svg>
	);
}

/**
 * Feather-style file icon — matches the SVG used in the frontend templates.
 *
 * @param {Object} props
 * @param {number} props.size Icon size in pixels.
 * @return {WPElement}
 */
function FileIcon( { size = 20 } ) {
	return (
		<svg
			xmlns="http://www.w3.org/2000/svg"
			width={ size }
			height={ size }
			viewBox="0 0 24 24"
			fill="none"
			stroke="currentColor"
			strokeWidth="2"
			strokeLinecap="round"
			strokeLinejoin="round"
			aria-hidden="true"
		>
			<path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
			<polyline points="14 2 14 8 20 8" />
		</svg>
	);
}

/**
 * Format bytes as a human-readable file size string.
 *
 * @param {number} bytes
 * @return {string}
 */
function formatFileSize( bytes ) {
	bytes = parseInt( bytes, 10 );
	if ( ! bytes ) return '';
	if ( bytes < 1024 ) return bytes + ' B';
	if ( bytes < 1048576 ) return ( bytes / 1024 ).toFixed( 1 ) + ' KB';
	return ( bytes / 1048576 ).toFixed( 1 ) + ' MB';
}

/**
 * Edit component for the Download File block.
 *
 * Renders the file upload/replace/remove UI directly in the block editor canvas.
 * All file operations go through the /pmpro-downloads/v1/upload/{id} REST endpoint,
 * which stores the file in PMPro's restricted files directory.
 *
 * @return {WPElement} Element to render.
 */
export default function Edit() {
	const blockProps = useBlockProps( { className: 'pmpro-download-file-manager' } );

	const postId = useSelect(
		( select ) => select( 'core/editor' ).getCurrentPostId(),
		[]
	);

	const [ meta, setMeta ] = useEntityProp( 'postType', 'pmpro_download', 'meta' );

	const downloadUrl = useSelect(
		( select ) =>
			select( 'core' ).getEntityRecord( 'postType', 'pmpro_download', postId )
				?.pmpro_download_url || '',
		[ postId ]
	);

	const [ isUploading, setIsUploading ] = useState( false );
	const [ uploadError, setUploadError ] = useState( '' );
	const fileInputRef = useRef( null );

	const uploadedFilename = meta?._pmpro_download_uploaded_filename || '';
	const fileType         = meta?._pmpro_download_file_type || '';
	const fileSize         = meta?._pmpro_download_file_size || 0;

	const fileMeta = [ fileType, formatFileSize( fileSize ) ]
		.filter( Boolean )
		.join( ' · ' );

	const handleFileChange = async ( event ) => {
		const file = event.target.files[ 0 ];
		if ( ! file ) return;

		setIsUploading( true );
		setUploadError( '' );

		const formData = new FormData();
		formData.append( 'file', file );

		try {
			const response = await apiFetch( {
				path: `/pmpro-downloads/v1/upload/${ postId }`,
				method: 'POST',
				body: formData,
			} );

			setMeta( {
				...meta,
				_pmpro_download_uploaded_filename: response.uploaded_filename,
				_pmpro_download_file_type: response.file_type,
				_pmpro_download_file_size: response.file_size,
			} );
		} catch ( error ) {
			setUploadError(
				error.message || __( 'Upload failed. Please try again.', 'pmpro-downloads' )
			);
		}

		setIsUploading( false );
		if ( fileInputRef.current ) {
			fileInputRef.current.value = '';
		}
	};

	const handleRemove = async () => {
		setIsUploading( true );
		setUploadError( '' );

		try {
			await apiFetch( {
				path: `/pmpro-downloads/v1/upload/${ postId }`,
				method: 'DELETE',
			} );

			setMeta( {
				...meta,
				_pmpro_download_uploaded_filename: '',
				_pmpro_download_file_type: '',
				_pmpro_download_file_size: 0,
			} );
		} catch ( error ) {
			setUploadError(
				error.message ||
					__( 'Could not remove file. Please try again.', 'pmpro-downloads' )
			);
		}

		setIsUploading( false );
	};

	return (
		<div { ...blockProps }>
			{ uploadError && (
				<Notice
					status="error"
					isDismissible={ true }
					onRemove={ () => setUploadError( '' ) }
				>
					{ uploadError }
				</Notice>
			) }

			{ isUploading && (
				<div className="pmpro-download-file-manager__loading">
					<Spinner />
					<span>{ __( 'Uploading…', 'pmpro-downloads' ) }</span>
				</div>
			) }

			{ ! isUploading && uploadedFilename && (
				<div className="pmpro-download-file-manager__file">
					<div className="pmpro-download-file-manager__file-info">
						<FileIcon size={ 20 } />
						{ downloadUrl ? (
							<a
								className="pmpro-download-file-manager__filename"
								href={ downloadUrl }
								target="_blank"
								rel="noreferrer"
							>
								{ uploadedFilename }
							</a>
						) : (
							<span className="pmpro-download-file-manager__filename">
								{ uploadedFilename }
							</span>
						) }
						{ fileMeta && (
							<span className="pmpro-download-file-manager__file-meta">
								{ fileMeta }
							</span>
						) }
					</div>
					<div className="pmpro-download-file-manager__actions">
						<Button
							variant="secondary"
							onClick={ () => fileInputRef.current?.click() }
						>
							{ __( 'Replace File', 'pmpro-downloads' ) }
						</Button>
						<Button variant="link" isDestructive onClick={ handleRemove }>
							{ __( 'Remove', 'pmpro-downloads' ) }
						</Button>
					</div>
					<input
						type="file"
						ref={ fileInputRef }
						onChange={ handleFileChange }
						style={ { display: 'none' } }
					/>
				</div>
			) }

			{ ! isUploading && ! uploadedFilename && (
				<div className="pmpro-download-file-manager__empty">
					<DownloadIcon size={ 32 } />
					<p>{ __( 'No file uploaded yet.', 'pmpro-downloads' ) }</p>
					<Button
						variant="primary"
						onClick={ () => fileInputRef.current?.click() }
					>
						{ __( 'Upload File', 'pmpro-downloads' ) }
					</Button>
					<input
						type="file"
						ref={ fileInputRef }
						onChange={ handleFileChange }
						style={ { display: 'none' } }
					/>
				</div>
			) }
		</div>
	);
}
