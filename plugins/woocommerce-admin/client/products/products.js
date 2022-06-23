/**
 * External dependencies
 */

import { Card, CardBody, TextControl } from '@wordpress/components';
import { TextControlWithAffixes, Pill } from '@woocommerce/components';
import { Icon, check } from '@wordpress/icons';
import { useState } from '@wordpress/element';
import { BlockPreview } from '@wordpress/block-editor';
import IsolatedBlockEditor from '@automattic/isolated-block-editor';

/**
 * Internal dependencies
 */
import { ProductsSection } from './index';

import './products.scss';
import '@automattic/isolated-block-editor/build-browser/core.css';

console.debug( 'BlockPreview', BlockPreview );

export const ManageProducts = () => {
	const [ blocks, updateBlocks ] = useState( [] );

	return (
		<>
			<section className="woo-products">
				<ProductsSection
					title="Images"
					description="This is the images description, lorem and ipsum of course"
				>
					<>
						<Card>
							<CardBody>
								Images card form
								<TextControl
									className="card-readers-support-address-line1-input"
									label="Address line 1"
									value={ '' }
									onChange={ ( value ) =>
										console.debug( 'onchange' )
									}
								/>
							</CardBody>
						</Card>
					</>
				</ProductsSection>
				<ProductsSection
					title="Product Details"
					description="This is the product details description, lorem and ipsum of course"
				>
					<>
						<Card>
							<CardBody>
								Product details card form
								<TextControlWithAffixes
									suffix={ <Icon icon={ check } /> }
									className="woocommerce-filters-advanced__input"
									type="text"
									value={ '' }
									onChange={ () =>
										console.debug( 'affixChange' )
									}
								/>
								<Pill>Menswear > Tops, Polos and whatever</Pill>{ ' ' }
								<Pill>Menswear > Shirts</Pill>
								{ /* <BlockPreview
									viewportWidth={ 100 }
									blocks={ blocks }
								/> */ }
							</CardBody>
						</Card>
					</>
				</ProductsSection>
				<ProductsSection
					title="Product Description"
					description="Testing Gutenberg editor"
				>
					<IsolatedBlockEditor
						settings={ {} }
						onSaveBlocks={ ( blocks ) => {
							updateBlocks( blocks );
						} }
						onLoad={ ( parse ) =>
							console.debug( 'load content', parse )
						}
						onError={ ( e ) => console.error( e ) }
					/>
				</ProductsSection>
			</section>
		</>
	);
};
