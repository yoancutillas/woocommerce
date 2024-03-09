<?php

namespace Automattic\WooCommerce\Internal\Api\DesignTime\Attributes;

#[\Attribute]
class DescriptionAttribute {
	public function __construct(
		public string $description
	)
	{}
}
