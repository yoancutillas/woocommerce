/**
 * External dependencies
 */
import { __ } from '@wordpress/i18n';
import {
	useFormContext,
	__experimentalWooProductFieldItem as WooProductFieldItem,
	SlotContextHelpersType,
} from '@woocommerce/components';
import { TextControl } from '@wordpress/components';
import interpolateComponents from '@automattic/interpolate-components';
import { Product } from '@woocommerce/data';
import { cleanForSlug } from '@wordpress/url';
import { registerPlugin } from '@wordpress/plugins';

const PRODUCT_DETAILS_SLUG = 'product-details';

const DetailsNameField = () => {
	const { getInputProps, values, setValue } = useFormContext< Product >();

	const setSkuIfEmpty = () => {
		if ( values.sku || ! values.name?.length ) {
			return;
		}
		setValue( 'sku', cleanForSlug( values.name ) );
	};

	return (
		<TextControl
			label={ interpolateComponents( {
				mixedString: __( 'Name {{required/}}', 'woocommerce' ),
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
			{ ...getInputProps( 'name', {
				onBlur: setSkuIfEmpty,
			} ) }
		/>
	);
};

registerPlugin( 'wc-admin-product-editor-name', {
	// @ts-expect-error 'scope' does exist. @types/wordpress__plugins is outdated.
	scope: 'woocommerce-product-editor',
	render: () => (
		<WooProductFieldItem
			id="test-name-field"
			section="details"
			pluginId="test-plugin"
		>
			{ ( { hideFill, showFill, getFills }: SlotContextHelpersType ) => {
				return (
					<>
						<DetailsNameField />
						<button
							onClick={ () => {
								hideFill( 'test-other-field' );
							} }
						>
							Hide other fill
						</button>
						&nbsp;
						<button
							onClick={ () => {
								showFill( 'test-other-field' );
							} }
						>
							Show other fill
						</button>
						&nbsp;
						<button
							onClick={ () => {
								Object.keys( getFills() ).forEach(
									( fillName ) => console.debug( fillName )
								);
							} }
						>
							Log all fills to console
						</button>
					</>
				);
			} }
		</WooProductFieldItem>
	),
} );
