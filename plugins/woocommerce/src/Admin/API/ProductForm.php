<?php
/**
 * REST API Onboarding Tasks Controller
 *
 * Handles requests to complete various onboarding tasks.
 */

namespace Automattic\WooCommerce\Admin\API;


defined('ABSPATH') || exit;

/**
 * Onboarding Tasks Controller.
 *
 * @internal
 * @extends WC_REST_Data_Controller
 */
class ProductForm extends \WC_REST_Data_Controller
{
	/**
	 * Endpoint namespace.
	 *
	 * @var string
	 */
	protected $namespace = 'wc-admin';

	/**
	 * Route base.
	 *
	 * @var string
	 */
	protected $rest_base = 'product-form';

	/**
	 * Register routes.
	 */
	public function register_routes()
	{

		register_rest_route(
			$this->namespace,
			'/' . $this->rest_base . '/fields',
			array(
				array(
					'methods' => \WP_REST_Server::READABLE,
					'callback' => array($this, 'get_fields'),
					'permission_callback' => array($this, 'get_tasks_permission_check'),
					'args' => array(
					),
				),
			)
		);
	}

	/**
	 * Get the onboarding tasks.
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 * @return WP_REST_Response|WP_Error
	 */
	public function get_fields($request)
	{
		global $wp_product_form_fields;

		$lists = is_array($wp_product_form_fields) ? $wp_product_form_fields : array();

		return rest_ensure_response(array_values(apply_filters('woocommerce_admin_onboarding_tasks', $lists)));
	}
}

