<?php

namespace Automattic\WooCommerce;

use Automattic\WooCommerce\Internal\HookRegistry;
use InvalidArgumentException;

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
		$this->load_configs( $this->filter_config_files, 'filter' );
	}

	/**
	 * Load an action config and register the actions.
	 *
	 * @param string $filepath filepath for a config.
	 * @param string $type action or filter.
	 * @throws InvalidArgumentException When a callback has invalid definition.
	 * @return void
	 */
	private function load_config( string $filepath, string $type ): void {
		$method_prefix = $type === 'action' ? 'add_action' : 'add_filter';
		foreach ( require $filepath as $action_name => $callbacks ) {

			foreach ( $callbacks as $callback_index => $callback ) {

				$priority      = $callback[1] ?? 1;
				$accpeted_args = $callback[2] ?? 1;
				$callable      = $callback[0];
				$id            = $callback[3] ?? null;

				if ( ! is_string( $callable ) && ! ( is_array( $callable ) && count( $callable ) === 2 ) ) {
					throw new InvalidArgumentException( "Invalid callback definition for {$action_name}[{$callback_index}]." );
				}

				$this->hook_registry->$method_prefix( $action_name, $callable, $priority, $accpeted_args, $id );
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
