/**
 * External dependencies
 */
import React from 'react';
import { Slot, Fill } from '@wordpress/components';
import { createElement } from '@wordpress/element';
import { applyFilters } from '@wordpress/hooks';

/**
 * Internal dependencies
 */
import { createOrderedChildren, sortFillsByOrder } from './slot-fill-ordering';

// TODO: move this to a published JS package once ready.

/**
 * Create a Fill for extensions to add items to the Product edit page.
 *
 * @slotFill WooProductFieldItem
 * @scope woocommerce-admin
 * @example
 * const MyProductDetailsFieldItem = () => (
 * <WooProductFieldItem fieldName="name" categoryName="Product details" location="after">My header item</WooProductFieldItem>
 * );
 *
 * registerPlugin( 'my-extension', {
 * render: MyProductDetailsFieldItem,
 * scope: 'woocommerce-admin',
 * } );
 * @param {Object}  param0
 * @param {Array}   param0.children  - Node children.
 * @param {string}  param0.fieldName - Field name.
 * @param {string}  param0.categoryName - Category name.
 * @param {number}  param0.order - Order of Fill component.
 * @param {string}  param0.location  - Location before or after.
 */
export const WooProductFieldItem: React.FC< {
	id: string;
	order?: number;
} > & {
	Slot: React.FC<
		Slot.Props & {
			id: string;
		}
	>;
} = ( { children, id, order = 1 } ) => {
	let fillName = `woocommerce_product_field`;

	fillName += '_' + id;

	return (
		<Fill name={ fillName }>
			{ ( fillProps: Fill.Props ) => {
				return createOrderedChildren( children, order, fillProps );
			} }
		</Fill>
	);
};

WooProductFieldItem.Slot = ( { fillProps, id } ) => {
	let fillName = `woocommerce_product_field`;

	fillName += '_' + id;
	return (
		<Slot name={ fillName } fillProps={ fillProps }>
			{ ( fills ) => {
				const filteredFills = applyFilters(
					'woo_product_field_fills',
					fills,
					fillName
				) as JSX.Element[][];
				if ( sortFillsByOrder ) {
					return sortFillsByOrder( filteredFills );
				}
				return null;
			} }
		</Slot>
	);
};
