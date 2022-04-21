<?php

use Automattic\WooCommerce\Admin\API\Init;
use Automattic\WooCommerce\Internal\Admin\CategoryLookup;
use Automattic\WooCommerce\Internal\Admin\Events;
use Automattic\WooCommerce\Internal\Admin\WCAdminUser;

return array(
	'edit_product_cat'               => array(
		array( CategoryLookup::class, 'before_edit', 99 ),
	),
	'generate_category_lookup_table' => array(
		array( CategoryLookup::class, 'regenerate' ),
	),
	'edited_product_cat'             => array(
		array( CategoryLookup::class, 'on_edit', 99 ),
	),
	'created_product_cat'            => array(
		array( CategoryLookup::class, 'on_create', 99 ),
	),
	'wc_admin_daily'                 => array(
		array( Events::class, 'do_wc_admin_daily' ),
	),
	'init'                           => array(
		array( CategoryLookup::class, 'define_category_lookup_tables_in_wpdb' ),
	),
	'rest_api_init'                  => array(
		array( WCAdminUser::class, 'register_user_data' ),
		array( Init::class, 'rest_api_init' ),
	),
);
