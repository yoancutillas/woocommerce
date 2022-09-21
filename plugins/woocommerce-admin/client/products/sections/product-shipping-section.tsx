/**
 * External dependencies
 */
import { useState, useEffect } from '@wordpress/element';
import { useSelect } from '@wordpress/data';
import { __ } from '@wordpress/i18n';
import {
	Link,
	useFormContext,
	__experimentalSelectControl as SelectControl,
} from '@woocommerce/components';
import {
	EXPERIMENTAL_PRODUCT_SHIPPING_CLASSES_STORE_NAME,
	Product,
	ProductShippingClass,
} from '@woocommerce/data';
import interpolateComponents from '@automattic/interpolate-components';
import debounce from 'lodash/debounce';

/**
 * Internal dependencies
 */
import { ProductSectionLayout } from '../layout/product-section-layout';
import { getTextControlProps } from './utils';
import { ADMIN_URL } from '../../utils/admin-settings';

type ShippingClassOption = {
	value: string;
	label: string;
};

const DEFAULT_SHIPPING_CLASS_OPTIONS: ShippingClassOption[] = [
	{ value: '', label: __( 'No shipping class', 'woocommerce' ) },
];

function mapShippingClassToSelectOption(
	shippingClasses: ProductShippingClass[]
): ShippingClassOption[] {
	return shippingClasses.map( ( { slug, name } ) => ( {
		value: slug,
		label: name,
	} ) );
}

export const ProductShippingSection: React.FC = () => {
	const [ shippingClassSearch, setShippingClassSearch ] = useState<
		string | undefined
	>();
	const [ selectedShippingClass, setSelectedShippingClass ] =
		useState< ShippingClassOption | null >( null );
	const [ shippingClassOptions, setShippingClassOptions ] = useState<
		ShippingClassOption[]
	>( [] );
	const { getInputProps } = useFormContext< Product >();

	const { shippingClasses } = useSelect(
		( select ) => {
			const { getProductShippingClasses } = select(
				EXPERIMENTAL_PRODUCT_SHIPPING_CLASSES_STORE_NAME
			);
			return {
				shippingClasses: getProductShippingClasses<
					ProductShippingClass[]
				>( {
					search: shippingClassSearch,
					per_page: 5,
				} ),
			};
		},
		[ shippingClassSearch ]
	);

	const shippingClassControlProps = getTextControlProps(
		getInputProps( 'shipping_class' )
	);

	const handleShippingClassInputChange = debounce( ( search ) => {
		setShippingClassSearch( search );
	}, 300 );

	function handleShippingClassSelect( selected: ShippingClassOption ) {
		shippingClassControlProps.onChange( selected );
	}

	function getFilteredItems(
		allItems: ShippingClassOption[],
		inputValue: string
	) {
		const pattern =
			'.*' + inputValue.toLowerCase().split( '' ).join( '.*' ) + '.*';
		const expression = new RegExp( pattern );

		return allItems.filter( ( item ) => {
			if ( item === DEFAULT_SHIPPING_CLASS_OPTIONS[ 0 ] )
				return expression.test( item.label.toLowerCase() );
			return true;
		} );
	}

	useEffect( () => {
		if ( shippingClassControlProps.value ) {
			setShippingClassSearch( shippingClassControlProps.value );
		}
	}, [ shippingClassControlProps.value ] );

	useEffect( () => {
		const items = [
			...DEFAULT_SHIPPING_CLASS_OPTIONS,
			...mapShippingClassToSelectOption( shippingClasses || [] ),
		];
		const selected = items.find(
			( { value } ) => value === ( shippingClassControlProps.value ?? '' )
		);

		setShippingClassOptions( items );
		setSelectedShippingClass( selected ?? null );
	}, [ shippingClasses, shippingClassControlProps.value ] );

	return (
		<ProductSectionLayout
			title={ __( 'Shipping', 'woocommerce' ) }
			description={ __(
				'Set up shipping costs and enter dimensions used for accurate rate calculations.',
				'woocommerce'
			) }
		>
			<SelectControl
				label={ __( 'Shipping class', 'woocommerce' ) }
				items={ shippingClassOptions }
				selected={ selectedShippingClass }
				onInputChange={ handleShippingClassInputChange }
				onSelect={ handleShippingClassSelect }
				getFilteredItems={ getFilteredItems }
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
		</ProductSectionLayout>
	);
};
