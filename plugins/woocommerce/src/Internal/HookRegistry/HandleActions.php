<?php

namespace Automattic\WooCommerce\Internal\HookRegistry;

interface HandleActions {
	public function handle_actions(string $action_name, array $args);
}
