/**
 * External dependencies
 */
import { registerPlugin } from '@wordpress/plugins';
import { Fill } from '@wordpress/components';

const MyHeaderItem = () => (
	<Fill name="woocommerce_tasklist_progress_header_item">
		<p>Header slotfill goes here!</p>
	</Fill>
);

registerPlugin( 'my-extension', {
	render: MyHeaderItem,
	// eslint-disable-next-line @typescript-eslint/ban-ts-comment
	// @ts-ignore
	scope: 'woocommerce-admin',
} );
