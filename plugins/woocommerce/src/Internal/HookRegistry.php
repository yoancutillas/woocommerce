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
	 * Add an action or a filter
	 *
	 * @param string       $hook_name The name of the hook to add the callback to.
	 * @param string|array $callable The callback to be run when the action is called. It can be a global function name or
	 *                      [classname, method] array.
	 * @param string       $type action or filter.
	 * @param int          $priority Used to specify the order in which the functions associated with a particular action are executed.
	 * @param int          $accepted_args  The number of arguments the function accepts.
	 * @param string|null  $id an optional I.D. This can be used to remove an action later.
	 *
	 * @return bool
	 */
	private function add( string $hook_name, $callable, string $type, int $priority = 10, int $accepted_args = 1, ?string $id = null ):bool {

		if ( is_string( $callable ) ) {
			$id                     = $id ?? "{$type}-func-{$callable}";
			$this->callbacks[ $id ] = function() use ( $callable ) {
				return call_user_func_array( $callable, func_get_args() );
			};

		} elseif ( is_array( $callable ) && count( $callable ) === 2 ) {
			$id                     = $id ?? "{$type}-method-{$callable[0]}-{$callable[1]}";
			$this->callbacks[ $id ] = function() use ( $callable ) {
				list($classname, $method_name) = $callable;
				$class_instance                = $this->container->get( $classname );
				$callback                      = function( $args ) use ( $method_name ) {
					return call_user_func_array( array( $this, $method_name ), $args );
				};
				// this allows us to call private methods without reflection.
				// works for php 7+.
				return $callback->call( $class_instance, func_get_args() );
			};
		} else {
			return false;
		}

		$func_name = $type === 'action' ? 'add_action' : 'add_filter';

		return $func_name( $hook_name, $this->callbacks[ $id ], $priority, $accepted_args );
	}

	/**
	 * Add an action
	 *
	 * @param string       $action_name The name of the action to add the callback to.
	 * @param string|array $callable The callback to be run when the action is called. It can be a global function name or
	 *                      [classname, method] array.
	 * @param int          $priority Used to specify the order in which the functions associated with a particular action are executed.
	 * @param int          $accepted_args The number of arguments the function accepts.
	 * @param string|null  $id an optional I.D. This can be used to remove an action later.
	 *
	 * @return bool
	 */
	public function add_action( string $action_name, $callable, int $priority = 10, int $accepted_args = 1, ?string $id = null ): bool {
		return $this->add( $action_name, $callable, 'action', $priority, $accepted_args, $id );
	}

	/**
	 * Add a filter
	 *
	 * @param string       $filter_name The name of the filter to add the callback to.
	 * @param string|array $callable The callback to be run when the action is called. It can be a global function name or
	 *                      [classname, method] array.
	 * @param int          $priority Used to specify the order in which the functions associated with a particular action are executed.
	 * @param int          $accepted_args The number of arguments the function accepts.
	 * @param string|null  $id an optional I.D. This can be used to remove an action later.
	 *
	 * @return bool
	 */
	public function add_filter( string $filter_name, $callable, int $priority = 10, int $accepted_args = 1, ?string $id = null ): bool {
		return $this->add( $filter_name, $callable, 'filter', $priority, $accepted_args, $id );
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
		return $this->remove_by_id( $action_name, $id, 'action' );
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
		return $this->remove( $action_name, $callable, 'action' );
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
		return $this->remove_by_id( $filter_name, $id, 'filter' );
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
		return $this->remove( $filter_name, $callable, 'filter' );
	}

	/**
	 * Remove an action or a filter by an I.D
	 *
	 * @param string $hook_name The name of the hook to add the callback to.
	 * @param string $id I.D of the hook to remove.
	 * @param string $type action or filter.
	 *
	 * @return bool
	 */
	private function remove_by_id( string $hook_name, string $id, string $type ): bool {
		if ( ! isset( $this->callbacks[ $id ] ) ) {
			return false;
		}
		$func_name = $type === 'action' ? 'remove_action' : 'remove_filter';

		return $func_name( $hook_name, $this->callbacks[ $id ] );
	}

	/**
	 * Remove an action or a filter
	 *
	 * @param string       $hook_name The name of the hook to add the callback to.
	 * @param string|array $callable The callback to be run when the action is called. It can be a global function name or
	 *                      [classname, method] array.
	 * @param string       $type action or filter.
	 *
	 * @return bool
	 */
	private function remove( string $hook_name, $callable, string $type ): bool {
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
