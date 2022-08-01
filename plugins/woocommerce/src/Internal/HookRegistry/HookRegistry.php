<?php

namespace Automattic\WooCommerce\Internal\HookRegistry;

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
	 * We'll save hook callbacks here with the following array key pattern to make it removeable later.
	 *
	 * {action|filter}{hook_name}{callback_class}{callback_method}
	 *
	 * @var array $callbacks Callbacks
	 */
	private $callbacks;

	/**
	 * Construct.
	 *
	 * @param ContainerInterface $container system container.
	 */
	public function __construct(ContainerInterface $container )
	{
		$this->container = $container;
	}

	public function add_action_with_function($action_name, $func_name, ?string $id = null)
	{
		$id = $id ?? "action-func-{$func_name}";
		$this->callbacks[$id] = function() use ($func_name) {
			call_user_func_array($func_name, func_get_args());
		};

		return add_action($action_name, $this->callbacks[$id]);
	}

	public function add_action_with_method($action_name, $classname, $method_name, ?string $id = null)
	{
		$id = $id ?? "action-method-{$classname}-{$method_name}";
		$this->callbacks[$id] = function() use($classname, $method_name) {
			$class_instance = $this->container->get( $classname );
			call_user_func_array(array($class_instance, $method_name), func_get_args());
		};

		return add_action($action_name, $this->callbacks[$id]);
	}

	/**
	 * Registers an action callback that implements HandleActions interface.
	 * @return void
	 */
	public function add_action_with_handler(string $action_name, string $action_handler_classname, ?string $id = null)
	{
		$callback = function() use ($action_handler_classname) {
			$class_instance = $this->container->get( $action_handler_classname );
			call_user_func_array( array( $class_instance, 'on' ), func_get_args() );
		};

		add_action($action_name, $callback);
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
				// Class + Method config
				if ( is_array( $callback ) ) {
					$callback_key = $type . $action_name . $callback[0] . $callback[1];
					$this->callbacks[$callback_key] = function() use ($callback) {
						$class_instance = $this->container->get( $callback[0] );
						call_user_func_array(array($class_instance, $callback[1]), func_get_args());
					};

				} else { // global function or a class that implements HandleActions or HandleFilters
					$callback_key = $type.$action_name.$callback;
					$this->callbacks[$callback_key] = function() use ($callback) {

						if (function_exists($callback)) {
							call_user_func_array($callback, func_get_args());
						} else {
							$class_instance = $this->container->get( $callback );
							if ( $class_instance instanceof HandleActions) {
								$callback_func_name = "on_action";
							} else if ( $class_instance instanceof HandleFilters) {
								$callback_func_name = "on_filter";
							} else {
								// error
								throw new \Exception("Callback must implement HandleActions or HandleFilters interface.");
							}
							call_user_func_array( array( $class_instance, $callback_func_name ), func_get_args() );
						}

					};
				}

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
