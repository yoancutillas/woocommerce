<?php

namespace Automattic\WooCommerce\Internal\Api;

use Automattic\WooCommerce\Internal\Api\GraphQL\GraphQLController;

class Main {
	public static function initialize(): void {
		GraphQLController::initialize();
	}
}
