<?php

namespace Automattic\WooCommerce\Internal\Api\DesignTime\Attributes;

#[\Attribute]
class NameAttribute {
	public function __construct(
		public string $name
	)
	{}
}
