/**
 * External dependencies
 */
// @ts-expect-error
import { __ } from '@wordpress/i18n';
import { createElement, useEffect } from '@wordpress/element';
import { CheckboxControl } from '@wordpress/components';
import { Product } from '@woocommerce/data';
import { registerPlugin } from '@wordpress/plugins';
import { WooProductFieldItem, useFormContext } from '@woocommerce/components';
import { addFilter, removeFilter } from '@wordpress/hooks';

const NewField: React.FC = () => {
	const { getCheckboxControlProps, values } = useFormContext<
		Product & { enable_add_on: boolean }
	>();

	useEffect( () => {
		addFilter(
			'woo_product_field_fills',
			'woocommerce-admin',
			( fills: JSX.Element[][], fillName ) => {
				if (
					values[ 'enable_add_on' ] &&
					fillName === 'woocommerce_product_field_product-details'
				) {
					console.log( fills );
					return [ ...fills ].filter( ( fill ) =>
						fill[ 0 ] && fill[ 0 ].props
							? fill[ 0 ].props.order !== 1
							: true
					);
				}
				return fills;
			}
		);
		return () => {
			removeFilter( 'woo_product_field_fills', 'woocommerce-admin' );
		};
	}, [ values ] );

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
			<WooProductFieldItem id="product-details" order={ 2 }>
				<NewField />
			</WooProductFieldItem>
		);
	},
	// @ts-expect-error;
	scope: 'woocommerce-product-form',
} );
