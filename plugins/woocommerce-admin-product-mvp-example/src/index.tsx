/**
 * External dependencies
 */

// @ts-expect-error
import { __ } from '@wordpress/i18n';
import { createElement } from '@wordpress/element';
import { Card, CardBody } from '@wordpress/components';
import { ONBOARDING_STORE_NAME } from '@woocommerce/data';
import { registerPlugin } from '@wordpress/plugins';
import { useDispatch } from '@wordpress/data';
import { WooProductFieldItem } from '@woocommerce/components';

type TaskProps = {
	onComplete: () => void;
};
const Task: React.FC< TaskProps > = ( { onComplete } ) => {
	const { actionTask } = useDispatch( ONBOARDING_STORE_NAME );

	return (
		<Card className="woocommerce-task-card">
			<CardBody>
				{ __(
					"This task's completion status is dependent on being actioned. The action button below will action this task, while the complete button will optimistically complete the task in the task list and redirect back to the task list. Note that in this example, the task must be actioned for completion to persist.",
					'plugin-domain'
				) }{ ' ' }
				<br />
				<br />
				{ __( 'Task actioned status: ', 'plugin-domain' ) } <br />
				<br />
				<div>
					<button
						onClick={ () => {
							actionTask( 'my-task' );
						} }
					>
						{ __( 'Action task', 'plugin-domain' ) }
					</button>
					<button onClick={ onComplete }>
						{ __( 'Complete', 'plugin-domain' ) }
					</button>
				</div>
			</CardBody>
		</Card>
	);
};

registerPlugin( 'add-task-content', {
	render: () => (
		<WooProductFieldItem
			fieldName="name"
			categoryName="Product Details"
			location="after"
		>
			<div>Test</div>
		</WooProductFieldItem>
	),
	// @ts-expect-error;
	scope: 'woocommerce-tasks',
} );
