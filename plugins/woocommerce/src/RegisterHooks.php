<?php

namespace Automattic\WooCommerce;

use Automattic\WooCommerce\Internal\HookRegistry;

/**
 * A service class to load action/filter config files and register actions/filters via HookRegistry.
 */
class RegisterHooks {

	/**
	 * An instance of HookRegistry
	 *
	 * @var HookRegistry
	 */
	private $hook_registry;

	/**
	 * A list of config files for actions.
	 *
	 * @var string[]
	 */
	private $action_config_files = array(
		__DIR__ . '/actions.php',
	);

	/**
	 * A list of config files for filters.
	 *
	 * @var array
	 */
	private $filter_config_files = array();

	/**
	 * Constructor.
	 *
	 * @param HookRegistry $hook_registry an instance of HookRegistry.
	 */
	public function __construct( HookRegistry $hook_registry ) {
		$this->hook_registry = $hook_registry;
		$this->load_action_configs();
	}

	/**
	 * Load an action config and register the actions.
	 *
	 * @param string $filepath filepath for a config.
	 * @return void
	 */
	public function load_action_config( string $filepath ) {
		foreach ( require $filepath as $action_name => $callbacks ) {

			foreach ( $callbacks as $callback ) {

				$priority      = $callback[1] ?? 1;
				$accpeted_args = $callback[2] ?? 1;
				$callable      = $callback[0];

				if ( is_string( $callable ) ) {
					$this->hook_registry->add_action_with_function( $action_name, $callable, $priority, $accpeted_args );
				} elseif ( is_array( $callable ) && count( $callable ) === 2 ) {
					$this->hook_registry->add_action_with_method(
						$action_name,
						$callable[0],
						$callable[1],
						$priority,
						$accpeted_args
					);
				}
			}
		}
	}

	/**
	 * Load config files for actions.
	 *
	 * @return void
	 */
	private function load_action_configs() {
		foreach ( $this->action_config_files as $action_config_file ) {
			$this->load_action_config( $action_config_file );
		}
	}
}
