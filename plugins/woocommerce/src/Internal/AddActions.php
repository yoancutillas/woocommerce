<?php

namespace Automattic\WooCommerce\Internal;

use Psr\Container\ContainerInterface;

/**
 * Class to add actions and their callbacks defined in src/actions.php.
 */
class AddActions {
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
				add_action(
					$action_name,
					function() use ( $callback ) {
						$class_instance = $this->container->get( $callback[0] );
						call_user_func_array( array( $class_instance, $callback[1] ), func_get_args() );
					},
					$callback[2] ?? 10,
					$callback[3] ?? 1
				);
			}
		}
	}
}
