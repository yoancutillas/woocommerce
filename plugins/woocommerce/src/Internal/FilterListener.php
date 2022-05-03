<?php

namespace Automattic\WooCommerce\Internal;

interface FilterListener {
	public function on_filter(string $filter_name, array $args);
}
