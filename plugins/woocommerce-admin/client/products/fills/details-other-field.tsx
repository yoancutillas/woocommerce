/**
 * External dependencies
 */
import { __ } from '@wordpress/i18n';
import { __experimentalWooProductFieldItem as WooProductFieldItem } from '@woocommerce/components';
import { TextControl } from '@wordpress/components';
import interpolateComponents from '@automattic/interpolate-components';
import { registerPlugin } from '@wordpress/plugins';
import { useState } from '@wordpress/element';

const PRODUCT_DETAILS_SLUG = 'product-details';

const DetailsOtherField = () => {
	const [ otherValue, setOtherValue ] = useState( '' );
	return (
		<TextControl
			label={ interpolateComponents( {
				mixedString: __( 'Other {{required/}}', 'woocommerce' ),
				components: {
					required: (
						<span className="woocommerce-product-form__optional-input">
							{ __( '(required)', 'woocommerce' ) }
						</span>
					),
				},
			} ) }
			name={ `${ PRODUCT_DETAILS_SLUG }-name` }
			placeholder={ __( 'e.g. 12 oz Coffee Mug', 'woocommerce' ) }
			value={ otherValue }
			onChange={ setOtherValue }
		/>
	);
};

registerPlugin( 'wc-admin-product-editor-details-other', {
	// @ts-expect-error 'scope' does exist. @types/wordpress__plugins is outdated.
	scope: 'woocommerce-product-editor',
	render: () => (
		<WooProductFieldItem
			id="test-other-field"
			section="details"
			pluginId="test-plugin"
			order={ 1 }
		>
			{ () => <DetailsOtherField /> }
		</WooProductFieldItem>
	),
} );
