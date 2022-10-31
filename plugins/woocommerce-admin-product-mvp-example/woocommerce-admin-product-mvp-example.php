<?php
/**
 * Plugin Name: WooCommerce Admin Extend Product MVP Example
 *
 * @package WooCommerce\Admin
 */

use Automattic\WooCommerce\Internal\Admin\Onboarding;
use Automattic\WooCommerce\Admin\Features\OnboardingTasks\Task;
use Automattic\WooCommerce\Admin\Features\OnboardingTasks\TaskLists;

/**
 * Register the task.
 */
function add_field() {
	if ( function_exists( 'wc_add_product_form_field' ) ) {
		wc_add_product_form_section( 'new-section', 'New section');
		wc_add_product_form_field( 'test', 'Test', 'Product Details', 'categories', array(
			'type' => 'text'
		));
	}
}

add_action( 'init', 'add_field' );

/**
 * Register the scripts to fill the task content on the frontend.
 */
function add_task_register_script() {
	if (
		! class_exists( 'Automattic\WooCommerce\Internal\Admin\Loader' ) ||
		! \Automattic\WooCommerce\Admin\PageController::is_admin_or_embed_page()
	) {
		return;
	}

	$asset_file = require __DIR__ . '/build/app.asset.php';
	wp_register_script(
		'add-task',
		plugins_url( '/build/app.js', __FILE__ ),
		$asset_file['dependencies'],
		$asset_file['version'],
		true
	);

	wp_enqueue_script( 'add-task' );
}

add_action( 'admin_enqueue_scripts', 'add_task_register_script' );
