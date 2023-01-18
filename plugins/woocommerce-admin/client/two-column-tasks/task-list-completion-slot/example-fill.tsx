// eslint-disable-next-line @typescript-eslint/ban-ts-comment
// @ts-nocheck
/**
 * External dependencies
 */
import { registerPlugin } from '@wordpress/plugins';
import { Fill } from '@wordpress/components';

/**
 * Internal dependencies
 */
import './example-fill.scss';

const MyTaskListCompletionItem = () => (
	<Fill name="woocommerce_experimental_task_list_completion">
		{ ( { hideTasks, keepTasks, customerEffortScore } ) => {
			return (
				<div className="woocommerce-experiments-placeholder-tasklist-completion-slotfill">
					<div className="placeholder-tasklist-completion-slotfill-content">
						<div>Task List Completion Slotfill goes in here!</div>
						<div>
							<button onClick={ hideTasks }>hideTasks()</button>
							<button onClick={ keepTasks }>keepTasks()</button>
						</div>
					</div>
				</div>
			);
		} }
	</Fill>
);

registerPlugin( 'my-tasklist-completion-extension', {
	render: MyTaskListCompletionItem,
	// eslint-disable-next-line @typescript-eslint/ban-ts-comment
	// @ts-ignore
	scope: 'woocommerce-admin',
} );
