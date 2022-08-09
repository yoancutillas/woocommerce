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
		$this->load_configs( $this->action_config_files, 'action' );
		$this->load_configs( $this->action_config_files, 'filter' );
	}

	/**
	 * Load an action config and register the actions.
	 *
	 * @param string $filepath filepath for a config.
	 * @param string $type action or filter.
	 * @return void
	 */
	public function load_config( string $filepath, string $type ) {
		$method_prefix = $type === 'action' ? 'add_action' : 'add_filter';
		foreach ( require $filepath as $action_name => $callbacks ) {

			foreach ( $callbacks as $callback ) {

				$priority      = $callback[1] ?? 1;
				$accpeted_args = $callback[2] ?? 1;
				$callable      = $callback[0];

				if ( is_string( $callable ) ) {
					$this->hook_registry->{$method_prefix . '_with_function'}( $action_name, $callable, $priority, $accpeted_args );
				} elseif ( is_array( $callable ) && count( $callable ) === 2 ) {
					$this->hook_registry->{$method_prefix . 'add_action_with_method'}(
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
	 * @param array  $files list of config files to load.
	 * @param string $type action or filter.
	 * @return void
	 */
	private function load_configs( array $files, string $type ) {
		foreach ( $files as $file ) {
			$this->load_config( $file, $type );
		}
	}
}
