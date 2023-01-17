/**
 * External dependencies
 */
import { registerPlugin } from '@wordpress/plugins';
import { Fill } from '@wordpress/components';

const MyHeaderItem = () => (
	<Fill name="woocommerce_tasklist_progress_title_item">
		<h1 className="woocommerce-task-progress-header__title">
			Title slotfill goes here
		</h1>
	</Fill>
);

registerPlugin( 'my-extension-2', {
	render: MyHeaderItem,
	// eslint-disable-next-line @typescript-eslint/ban-ts-comment
	// @ts-ignore
	scope: 'woocommerce-admin',
} );
