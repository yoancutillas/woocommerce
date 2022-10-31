/**
 * External dependencies
 */
import { recordEvent } from '@woocommerce/tracks';
import { useEffect, useState } from '@wordpress/element';
import { Form, FormContext } from '@woocommerce/components';
import { TextControl } from '@wordpress/components';
import { Product } from '@woocommerce/data';
import apiFetch from '@wordpress/api-fetch';

/**
 * Internal dependencies
 */
import { ProductFormHeader } from './layout/product-form-header';
import { ProductFormLayout } from './layout/product-form-layout';
import { ProductDetailsSection } from './sections/product-details-section';
import { ProductInventorySection } from './sections/product-inventory-section';
import { PricingSection } from './sections/pricing-section';
import { ProductShippingSection } from './sections/product-shipping-section';
import { ImagesSection } from './sections/images-section';
import './product-page.scss';
import { validate } from './product-validation';
import { AttributesSection } from './sections/attributes-section';
import { ProductFormFooter } from './layout/product-form-footer';
import { WooProductFieldItem } from './layout/woo-product-field-item';
import { ProductSectionLayout } from './layout/product-section-layout';

type Field = {
	id: string;
	title: string;
	section: string;
	field: string;
	args: {
		type?: string;
	};
};

type Section = {
	id: string;
	title: string;
	args: {
		type?: string;
	};
};

const AddProductPage: React.FC = () => {
	const [ fields, setFields ] = useState< Field[] >( [] );
	const [ sections, setSections ] = useState< Section[] >( [] );
	const getFields = async () => {
		const data: { fields: Field[]; sections: Section[] } = await apiFetch( {
			path: `/wc-admin/product-form/data`,
			method: 'GET',
		} );
		setFields( data.fields );
		setSections( data.sections );
	};
	useEffect( () => {
		recordEvent( 'view_new_product_management_experience' );
		getFields();
	}, [] );

	return (
		<div className="woocommerce-add-product">
			<Form< Partial< Product > >
				initialValues={ {
					reviews_allowed: true,
					name: '',
					sku: '',
					stock_quantity: 0,
					stock_status: 'instock',
				} }
				errors={ {} }
				validate={ validate }
			>
				<ProductFormHeader />
				{ ( { getInputProps }: FormContext< Partial< Product > > ) => (
					<>
						<ProductFormLayout>
							<ProductDetailsSection />
							<PricingSection />
							<ImagesSection />
							<ProductInventorySection />
							<ProductShippingSection />
							<AttributesSection />
							{ sections.map( ( section ) => (
								<ProductSectionLayout
									key={ section.id }
									title={ section.title }
									description=""
								></ProductSectionLayout>
							) ) }
						</ProductFormLayout>
						<ProductFormFooter />
						{ fields.map( ( field ) => (
							<WooProductFieldItem
								key={ field.id }
								fieldName={ field.field }
								categoryName={ field.section }
								location="after"
							>
								{ field.args.type === 'text' ? (
									<TextControl
										label={ field.title }
										placeholder="New field"
										{ ...getInputProps( field.id ) }
									/>
								) : null }
							</WooProductFieldItem>
						) ) }
					</>
				) }
			</Form>
		</div>
	);
};

export default AddProductPage;
