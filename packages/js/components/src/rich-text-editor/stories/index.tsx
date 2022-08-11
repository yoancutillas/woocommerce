/**
 * External dependencies
 */
import React from 'react';
import { createElement } from '@wordpress/element';

/**
 * Internal dependencies
 */
import { RichTextEditor } from '../';
import './style.scss';

export const Basic: React.FC = () => {
	return <RichTextEditor />;
};

export default {
	title: 'WooCommerce Admin/components/RichTextEditor',
	component: RichTextEditor,
};
