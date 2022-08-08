<?php

namespace Automattic\WooCommerce\Internal;

use Automattic\WooCommerce\Container;

/**
 * HookRegistry
 */
class HookRegistry {

	/**
	 * An instance of WooCommerce Container
	 *
	 * @var Container
	 */
	private $container;

	/**
	 * We'll save hook callbacks here with the following array key pattern to make it removeable later.
	 *
	 * {action|filter}{hook_name}{callback_class}{callback_method}
	 *
	 * @var array $callbacks Callbacks
	 */
	private $callbacks;

	/**
	 * An instance of  WooCommerce Container
	 *
	 * @param Container $container An instance of  WooCommerce Container.
	 */
	public function __construct( Container $container ) {
		$this->container = $container;
	}

	/**
	 * Add an action with a global function.
	 *
	 * @param string      $action_name action name.
	 * @param string      $func_name function name.
	 * @param int         $priority (Optional) Used to specify the order in which the functions associated with a particular action are executed.
	 * @param int         $accepted_args (Optional) The number of arguments the function accepts.
	 * @param string|null $id (Optional) An I.D that can be used to remove the callback later.
	 *
	 * @return bool
	 */
	public function add_action_with_function( string $action_name, string $func_name, int $priority = 10, int $accepted_args = 1, ?string $id = null ): bool {
		$id                     = $id ?? "action-func-{$func_name}";
		$this->callbacks[ $id ] = function() use ( $func_name ) {
			call_user_func_array( $func_name, func_get_args() );
		};

		return add_action( $action_name, $this->callbacks[ $id ], $priority, $accepted_args );
	}

	/**
	 * Add an action with a method in a class.
	 *
	 * @param string      $action_name action name.
	 * @param string      $classname class name.
	 * @param string      $method_name method name.
	 * @param int         $priority (Optional) Used to specify the order in which the functions associated with a particular action are executed.
	 * @param int         $accepted_args (Optional) The number of arguments the function accepts.
	 * @param string|null $id (Optional) An I.D that can be used to remove the callback later.
	 *
	 * @return bool
	 */
	public function add_action_with_method( string $action_name, string $classname, string $method_name, int $priority = 10, int $accepted_args = 1, ?string $id = null ): bool {
		$id                     = $id ?? "action-method-{$classname}-{$method_name}";
		$this->callbacks[ $id ] = function() use ( $classname, $method_name ) {
			$class_instance = $this->container->get( $classname );
			$callback       = function( $args ) use ( $method_name ) {
				call_user_func_array( array( $this, $method_name ), $args );
			};
			// this allows us to call private methods without reflection.
			// works for php 7+.
			$callback->call( $class_instance, func_get_args() );
		};

		return add_action( $action_name, $this->callbacks[ $id ], $priority, $accepted_args );
	}
}
