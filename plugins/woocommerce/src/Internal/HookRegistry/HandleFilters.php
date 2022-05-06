<?php

namespace Automattic\WooCommerce\Internal\HookRegistry;

interface HandleFilters {
	public function handle_filters(string $filter_name, array $args);
}
