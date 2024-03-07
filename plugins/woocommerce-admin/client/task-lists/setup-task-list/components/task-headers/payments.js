/**
 * External dependencies
 */
import { Button } from '@wordpress/components';
import { __ } from '@wordpress/i18n';

/**
 * Internal dependencies
 */
import TimerImage from './timer.svg';
import { WC_ASSET_URL } from '../../../../utils/admin-settings';

const PaymentsHeader = ( { task, goToTask } ) => {
	return (
		<div className="woocommerce-task-header__contents-container">
			<img
				alt={ __( 'Payment illustration', 'woocommerce' ) }
				src={
					WC_ASSET_URL + 'images/task_list/payment-illustration.svg'
				}
				className="svg-background"
			/>
			<div className="woocommerce-task-header__contents">
				<h1>{ __( 'It’s time to get paid', 'woocommerce' ) }</h1>
				<p>
					{ __(
						'Give your customers an easy and convenient way to pay! Set up one (or more!) of our fast and secure online or in person payment methods.',
						'woocommerce'
					) }
				</p>
				<Button
					isSecondary={ task.isComplete }
					isPrimary={ ! task.isComplete }
					onClick={ goToTask }
				>
					{ __( 'Get paid', 'woocommerce' ) }
				</Button>
				<p className="woocommerce-task-header__timer">
					<img src={ TimerImage } alt="Timer" />{ ' ' }
					<span>{ task.time }</span>
				</p>
			</div>
		</div>
	);
};

export default PaymentsHeader;
