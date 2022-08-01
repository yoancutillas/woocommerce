<?php

namespace Automattic\WooCommerce\Internal\HookRegistry;

/**
 * A service class to register hooks from a config file that returns an array
 */
class RegisterHooksFromConfig
{
	/**
	 * @var HookRegistry
	 */
	private $hookRegistry;

	public function __construct(HookRegistry  $hookRegistry)
	{
		$this->hookRegistry = $hookRegistry;
	}

	public function register(string $actionsConfigPath, string $filtersConfigPath)
	{
	    $actions = require_once $actionsConfigPath;
		$filters = require_once $filtersConfigPath;
	}
}
