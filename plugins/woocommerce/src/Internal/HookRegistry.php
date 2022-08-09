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
	 * Remove an action by an I.D
	 *
	 * @param string $action_name The action hook to which the function to be removed is hooked.
	 * @param string $id I.D used to register an action.
	 *
	 * @return bool
	 */
	public function remove_action_by_id( string $action_name, string $id ): bool {
		return $this->remove_by_id($action_name, $id, 'action');
	}

	/**
	 * Remove an action
	 *
	 * @param string       $action_name The action hook to which the function to be removed is hooked.
	 * @param string|array $callable a function name or callable [class, method].
	 *
	 * @return bool
	 */
	public function remove_action( string $action_name, $callable ): bool {
		return $this->remove($action_name, $callable, 'action');
	}

	/**
	 * Add a filter with a global function.
	 *
	 * @param string      $filter_name action name.
	 * @param string      $func_name function name.
	 * @param int         $priority (Optional) Used to specify the order in which the functions associated with a particular action are executed.
	 * @param int         $accepted_args (Optional) The number of arguments the function accepts.
	 * @param string|null $id (Optional) An I.D that can be used to remove the callback later.
	 *
	 * @return bool
	 */
	public function add_filter_with_function( string $filter_name, string $func_name, int $priority = 10, int $accepted_args = 1, ?string $id = null ): bool {
		$id                     = $id ?? "filter-func-{$func_name}";
		$this->callbacks[ $id ] = function() use ( $func_name ) {
			call_user_func_array( $func_name, func_get_args() );
		};

		return add_filter( $filter_name, $this->callbacks[ $id ], $priority, $accepted_args );
	}

	/**
	 * Add a filter with a method in a class.
	 *
	 * @param string      $filter_name action name.
	 * @param string      $classname class name.
	 * @param string      $method_name method name.
	 * @param int         $priority (Optional) Used to specify the order in which the functions associated with a particular action are executed.
	 * @param int         $accepted_args (Optional) The number of arguments the function accepts.
	 * @param string|null $id (Optional) An I.D that can be used to remove the callback later.
	 *
	 * @return bool
	 */
	public function add_filter_with_method( string $filter_name, string $classname, string $method_name, int $priority = 10, int $accepted_args = 1, ?string $id = null ): bool {
		$id                     = $id ?? "filter-method-{$classname}-{$method_name}";
		$this->callbacks[ $id ] = function() use ( $classname, $method_name ) {
			$class_instance = $this->container->get( $classname );
			$callback       = function( $args ) use ( $method_name ) {
				call_user_func_array( array( $this, $method_name ), $args );
			};
			// this allows us to call private methods without reflection.
			// works for php 7+.
			$callback->call( $class_instance, func_get_args() );
		};

		return add_filter( $filter_name, $this->callbacks[ $id ], $priority, $accepted_args );
	}

	public function add_action($action_name, $callable, int $priority = 10, int $accepted_args = 1, ?string $id = null)
	{
	    if (is_string($callable)) {
			$id = "action-func-{$callable}";
			$this->callbacks[ $id ] = function() use ( $callable ) {
				call_user_func_array( $callable, func_get_args() );
			};

		} else if (is_array($callable) && count($callable) === 2) {
			$id = "action-method-{$callable[0]}-{$callable[1]}";
			$this->callbacks[ $id ] = function() use ( $callable ) {
				list($classname, $method_name) = $callable;
				$class_instance = $this->container->get( $classname );
				$callback       = function( $args ) use ( $method_name ) {
					call_user_func_array( array( $this, $method_name ), $args );
				};
				// this allows us to call private methods without reflection.
				// works for php 7+.
				$callback->call( $class_instance, func_get_args() );
			};
		} else {
			return false;
		}

		return add_action($action_name, $this->callbacks[$id], $priority, $accepted_args);
	}


	/**
	 * Remove an action by an I.D
	 *
	 * @param string $filter_name The action hook to which the function to be removed is hooked.
	 * @param string $id I.D used to register an action.
	 *
	 * @return bool
	 */
	public function remove_filter_by_id( string $filter_name, string $id ): bool {
		return $this->remove_by_id($filter_name, $id, 'filter');
	}

	/**
	 * Remove an action
	 *
	 * @param string       $filter_name The filter hook to which the function to be removed is hooked.
	 * @param string|array $callable a function name or callable [class, method].
	 *
	 * @return bool
	 */
	public function remove_filter( string $filter_name, $callable ): bool {
		return $this->remove($filter_name, $callable, 'filter');
	}

	private function remove_by_id($hook_name, string $id, $type): bool {
		if ( ! isset( $this->callbacks[ $id ] ) ) {
			return false;
		}
		$func_name = $type === 'action' ? 'remove_action' : 'remove_filter';

		return $func_name( $hook_name, $this->callbacks[ $id ] );
	}

	private function remove($hook_name, $callable, $type): bool {
		if ( is_string( $callable ) ) {
			$id = "{$type}-func-{$callable}";
		} elseif ( is_array( $callable ) && count( $callable ) === 2 ) {
			$id = "{$type}-method-{$callable[0]}-{$callable[1]}";
		} else {
			return false;
		}

		if ( ! isset( $this->callbacks[ $id ] ) ) {
			return false;
		}

		$func_name = $type === 'action' ? 'remove_action' : 'remove_filter';

		return $func_name( $hook_name, $this->callbacks[ $id ] );
	}

}
