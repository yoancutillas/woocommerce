<?php

namespace Automattic\WooCommerce\Internal;

interface ActionListener {
	public function on_action(string $action_name, array $args);
}
