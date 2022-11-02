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

function rest_api_prepare_product( $response, $post ) {
	$post_id = is_callable( array( $post, 'get_id' ) ) ? $post->get_id() : ( ! empty( $post->ID ) ? $post->ID : null );

	if ( empty( $response->data['test'] ) ) {
		$data = get_option( 'woocommerce_product_mvp_example_' . strval( $post_id ), array() );
		error_log( print_r( $data, true ) );
		if ( ! empty( $data ) ) {
			$response->data['test'] = $data['test'];
			$response->data['new_field'] = $data['new_field'];
		}
	}

	return $response;
}

add_filter( 'woocommerce_rest_prepare_product_object', 'rest_api_prepare_product', 10, 2 );

function rest_api_add_to_product( $product, $request, $creating = true ) {
	$product_id = is_callable( array( $product, 'get_id' ) ) ? $product->get_id() : ( ! empty( $product->ID ) ? $product->ID : null );
	$params     = $request->get_params();
	$test      = isset( $params['test'] ) ? $params['test'] : null;
	$new_field = isset( $params['new_field'] ) ? $params['new-field'] : null;

	error_log( 'saving: ' . $test . $new_field );
	if ( $test !== null || $new_field !== null ) {
		update_option( 'woocommerce_product_mvp_example_' . strval( $product_id ), array(
			'test' => $test,
			'new_field' => $new_field
		));
	}
}

add_action( 'woocommerce_rest_insert_product_object', 'rest_api_add_to_product', 10, 3 );