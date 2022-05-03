<?php

namespace Automattic\WooCommerce\Internal;

use Psr\Container\ContainerInterface;

/**
 * Class to add actions and their callbacks defined in src/actions.php.
 */
class HookRegistry {
	/**
	 * System Container.
	 *
	 * @var ContainerInterface Container interface
	 */
	private $container;


	/**
	 * Callbacks.
	 *
	 * @var array $callbacks Callbacks
	 */
	private $callbacks;

	/**
	 * Construct.
	 *
	 * @param array              $actions array of actions and callbacks.
	 * @param array              $filters array of filters and callbacks.
	 * @param ContainerInterface $container system container.
	 */
	public function __construct( array $actions, array $filters, ContainerInterface $container ) {
		$this->container = $container;
		$this->register( $actions, 'action' );
		$this->register( $filters, 'filter' );
	}

	/**
	 * Add actiosn.
	 *
	 * @param array  $items $actions or $filters.
	 * @param string $type action or filter.
	 *
	 * @return void
	 */
	private function register( array $items, string $type ) {
		$func_name = 'action' === $type ? 'add_action' : 'add_filter';
		// Loop through each action.
		foreach ( $items as $action_name => $callbacks ) {
			// and register action's callbacks.
			foreach ( $callbacks as $callback ) {
				$callback_key = $type . $action_name . $callback[0] . $callback[1];

				$this->callbacks[ $callback_key ] = function() use ( $callback ) {
					$class_instance = $this->container->get( $callback[0] );
					if ($class_instance instanceof ActionListener) {
						$callback_func_name = "on_action";
					} else if ($class_instance instanceof  FilterListener) {
						$callback_func_name = "on_filter";
					} else {
						$callback_func_name = $callback[1];
					}
					call_user_func_array( array( $class_instance, $callback_func_name ), func_get_args() );
				};
				$func_name(
					$action_name,
					$this->callbacks[ $callback_key ],
					$callback[2] ?? 10,
					$callback[3] ?? 1
				);
			}
		}
	}

	/**
	 * Remove a hook.
	 *
	 * @param string $hook_name name of the hook to remove.
	 * @param string $type action or filter.
	 * @param string $classname classname.
	 * @param string $callback_name callback name.
	 *
	 * @return false
	 */
	private function remove( $hook_name, $type, $classname, $callback_name ) {
		$callback_key = $type . $hook_name . $classname . $callback_name;
		$func_name    = 'action' === $type ? 'remove_action' : 'remove_filter';
		if ( array_key_exists( $callback_key, $this->callbacks ) ) {
			return $func_name( $hook_name, $this->callbacks[ $callback_key ] );
		}
		return false;
	}

	/**
	 * Remove a filter.
	 *
	 * @param string $filter_name name of the filter to remove.
	 * @param string $classname filter callback class.
	 * @param string $callback_name filter callback method name.
	 *
	 * @return void
	 */
	public function remove_filter( $filter_name, $classname, $callback_name ) {
		$this->remove( $filter_name, 'filter', $classname, $callback_name );
	}

	/**
	 * Remove an action.
	 *
	 * @param string string $action_name name of the action to remove.
	 * @param string string $classname action class name.
	 * @param string string $callback_name action callback method name.
	 *
	 * @return void
	 */
	public function remove_action( $action_name, $classname, $callback_name ) {
		$this->remove( $action_name, 'action', $classname, $callback_name );
	}
}
