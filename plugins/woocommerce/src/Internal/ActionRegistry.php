<?php

namespace Automattic\WooCommerce\Internal;

use Psr\Container\ContainerInterface;

/**
 * Class to add actions and their callbacks defined in src/actions.php.
 */
class ActionRegistry {
	/**
	 * Array of actions and callbacks.
	 *
	 * @var array $actions array of actions and callbacks.
	 */
	private $actions;

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
	 * @param ContainerInterface $container system container.
	 */
	public function __construct( $actions, ContainerInterface $container ) {
		$this->actions   = $actions;
		$this->container = $container;
	}

	/**
	 * Add actiosn.
	 *
	 * @return void
	 */
	public function add_all() {
		// Loop through each action.
		foreach ( $this->actions as $action_name => $callbacks ) {
			// and register action's callbacks.
			foreach ( $callbacks as $callback ) {
				$callback_key                     = $action_name . $callback[0] . $callback[1];
				$this->callbacks[ $callback_key ] = function() use ( $callback ) {
					$class_instance = $this->container->get( $callback[0] );
					call_user_func_array( array( $class_instance, $callback[1] ), func_get_args() );
				};
				add_action(
					$action_name,
					$this->callbacks[ $callback_key ],
					$callback[2] ?? 10,
					$callback[3] ?? 1
				);
			}
		}
	}

	/**
	 * Remove action.
	 *
	 * @param string $action_name action name.
	 * @param string $classname classname.
	 * @param string $callback_name callback name.
	 *
	 * @return false
	 */
	public function remove( $action_name, $classname, $callback_name ) {
		$callback_key = $action_name . $classname . $callback_name;
		if ( array_key_exists( $callback_key, $this->callbacks ) ) {
			return remove_action( $action_name, $this->callbacks[ $callback_key ] );
		}
		return false;
	}
}
