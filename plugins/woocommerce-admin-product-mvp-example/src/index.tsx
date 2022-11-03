/**
 * External dependencies
 */
// @ts-expect-error
import { __ } from '@wordpress/i18n';
import { createElement } from '@wordpress/element';
import { CheckboxControl } from '@wordpress/components';
import { Product } from '@woocommerce/data';
import { registerPlugin } from '@wordpress/plugins';
import { WooProductFieldItem, useFormContext } from '@woocommerce/components';

const NewField: React.FC = () => {
	const { getCheckboxControlProps } = useFormContext< Product >();

	return (
		<CheckboxControl
			label="Enable add-on"
			{ ...getCheckboxControlProps( 'enable_add_on' ) }
		/>
	);
};

registerPlugin( 'add-task-content', {
	render: () => {
		return (
			<WooProductFieldItem
				fieldName="name"
				categoryName="Product details"
				location="after"
			>
				<NewField />
			</WooProductFieldItem>
		);
	},
	// @ts-expect-error;
	scope: 'woocommerce-product-form',
} );
