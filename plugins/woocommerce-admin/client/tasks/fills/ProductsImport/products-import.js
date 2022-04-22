/**
 * External dependencies
 */
import { Fragment } from '@wordpress/element';
import { registerPlugin } from '@wordpress/plugins';
import { WooOnboardingTask } from '@woocommerce/onboarding';

const ProductsImport = () => {
	return <Fragment>Hello</Fragment>;
};

registerPlugin( 'wc-admin-onboarding-task-products-import', {
	scope: 'woocommerce-tasks',
	render: () => (
		<WooOnboardingTask id="products_import">
			<ProductsImport />
		</WooOnboardingTask>
	),
} );
