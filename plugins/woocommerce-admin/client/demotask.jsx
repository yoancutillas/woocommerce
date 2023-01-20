/**
 * External dependencies
 */
import { registerPlugin } from '@wordpress/plugins';
import { WooOnboardingTaskListHeader } from '@woocommerce/onboarding';
import { Button } from '@wordpress/components';

/**
 * Internal dependencies
 */
import { WC_ASSET_URL } from '~/utils/admin-settings';

const DemoTaskHeader = () => {
	return (
		<WooOnboardingTaskListHeader id="demo">
			{ ( { task, goToTask } ) => {
				return (
					<div className="woocommerce-task-header__contents-container">
						<img
							alt="Demo"
							src={
								WC_ASSET_URL +
								'images/task_list/payment-illustration.png'
							}
							className="svg-background"
						/>
						<div className="woocommerce-task-header__contents">
							<h1>Demo</h1>
							<p>Something</p>
							<Button
								isSecondary={ task.isComplete }
								isPrimary={ ! task.isComplete }
								onClick={ goToTask }
							>
								Onwards!
							</Button>
							<p className="woocommerce-task-header__timer">
								<span>{ task.time }</span>
							</p>
						</div>
					</div>
				);
			} }
		</WooOnboardingTaskListHeader>
	);
};

registerPlugin( 'my-tasklist-completion-extension', {
	render: DemoTaskHeader,
	// eslint-disable-next-line @typescript-eslint/ban-ts-comment
	// @ts-ignore
	scope: 'woocommerce-tasks',
} );
