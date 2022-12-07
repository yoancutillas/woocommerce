/**
 * External dependencies
 */
import React from 'react';
import { Slot, Fill } from '@wordpress/components';
import { createElement } from '@wordpress/element';
import { snakeCase } from 'lodash';

/**
 * Internal dependencies
 */
import {
	createOrderedChildren,
	sortFillsByOrder,
} from '../woo-product-field-item/slot-fill-ordering';

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
 * @param {number}  param0.order - Order of Fill component.
 */
export const WooProductSectionItem: React.FC< {
	id: string;
	order?: number;
} > & {
	Slot: React.FC<
		Slot.Props & {
			id: string;
		}
	>;
} = ( { id, children, order = 1 } ) => {
	let fillName = `woocommerce_product_section_${ id }`;

	return (
		<Fill name={ fillName }>
			{ ( fillProps: Fill.Props ) => {
				return createOrderedChildren( children, order, fillProps );
			} }
		</Fill>
	);
};

WooProductSectionItem.Slot = ( { fillProps, id } ) => {
	let fillName = `woocommerce_product_section_${ id }`;

	return (
		<Slot name={ fillName } fillProps={ fillProps }>
			{ sortFillsByOrder }
		</Slot>
	);
};
