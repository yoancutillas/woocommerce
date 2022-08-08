<?php

use Automattic\WooCommerce\Internal\Admin\CategoryLookup;

return array(
	'edit_product_cat'               => array(
		array(
			array( CategoryLookup::class, 'before_edit' ),
			99,
		),
	),

	'generate_category_lookup_table' => array(
		array(
			array( CategoryLookup::class, 'regenerate' ),
		),
	),

	'edited_product_cat'             => array(
		array(
			array( CategoryLookup::class, 'on_edit' ),
			99,
		),
	),

	'created_product_cat'            => array(
		array(
			array( CategoryLookup::class, 'on_create' ),
			99,
		),
	),

	'init'                           => array(
		array(
			array( CategoryLookup::class, 'define_category_lookup_tables_in_wpdb' ),
		),
	),
);
