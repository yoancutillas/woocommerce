/**
 * External dependencies
 */
import { useSelect } from '@wordpress/data';
import { useState } from '@wordpress/element';
import { __ } from '@wordpress/i18n';
import {
	Link,
	Spinner,
	useFormContext,
	__experimentalSelectControl as SelectControl,
} from '@woocommerce/components';
import {
	EXPERIMENTAL_PRODUCT_SHIPPING_CLASSES_STORE_NAME,
	Product,
	ProductShippingClass,
} from '@woocommerce/data';
import interpolateComponents from '@automattic/interpolate-components';

/**
 * Internal dependencies
 */
import { ProductSectionLayout } from '../layout/product-section-layout';
import { getTextControlProps } from './utils';
import { ADMIN_URL } from '../../utils/admin-settings';
import './product-shipping-section.scss';

const DEFAULT_SHIPPING_CLASS_OPTIONS = [
	{
		id: 0,
		name: __( 'No shipping class', 'woocommerce' ),
	},
];

export const ProductShippingSection: React.FC = () => {
	const { getInputProps } = useFormContext< Product >();
	const [ selected, setSelected ] = useState<
		Partial< ProductShippingClass >
	>( DEFAULT_SHIPPING_CLASS_OPTIONS[ 0 ] );

	const { shippingClasses, hasResolvedShippingClasses } = useSelect(
		( select ) => {
			const { getProductShippingClasses, hasFinishedResolution } = select(
				EXPERIMENTAL_PRODUCT_SHIPPING_CLASSES_STORE_NAME
			);
			return {
				hasResolvedShippingClasses: hasFinishedResolution(
					'getProductShippingClasses'
				),
				shippingClasses:
					getProductShippingClasses< ProductShippingClass[] >(),
			};
		},
		[]
	);

	return (
		<ProductSectionLayout
			title={ __( 'Shipping', 'woocommerce' ) }
			description={ __(
				'Set up shipping costs and enter dimensions used for accurate rate calculations.',
				'woocommerce'
			) }
		>
			{ hasResolvedShippingClasses ? (
				<div>
					<SelectControl< Partial< ProductShippingClass > >
						getFilteredItems={ ( allItems ) => allItems }
						getItemLabel={ ( item ) => item?.name || '' }
						getItemValue={ ( item ) => String( item?.id ) }
						label={ __( 'Shipping class', 'woocommerce' ) }
						{ ...getTextControlProps(
							getInputProps( 'shipping_class' )
						) }
						items={ [
							...DEFAULT_SHIPPING_CLASS_OPTIONS,
							...( shippingClasses ?? [] ),
						] }
						selected={ selected }
					/>
					<span className="woocommerce-product-form__secondary-text">
						{ interpolateComponents( {
							mixedString: __(
								'Manage shipping classes and rates in {{link}}global settings{{/link}}.',
								'woocommerce'
							),
							components: {
								link: (
									<Link
										href={ `${ ADMIN_URL }admin.php?page=wc-settings&tab=shipping&section=classes` }
										target="_blank"
										type="external"
									>
										<></>
									</Link>
								),
							},
						} ) }
					</span>
				</div>
			) : (
				<div className="product-shipping-section__spinner-wrapper">
					<Spinner />
				</div>
			) }
		</ProductSectionLayout>
	);
};
