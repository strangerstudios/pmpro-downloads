/**
 * WordPress dependencies
 */
import { registerBlockType } from '@wordpress/blocks';

/**
 * Internal dependencies
 */
import Edit from './edit';
import metadata from './block.json';
import './editor.css';

const icon = {
	src: 'download'
};

registerBlockType( metadata.name, {
	icon,
	edit: Edit,
	save: () => null,
} );
