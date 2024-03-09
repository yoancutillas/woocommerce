<?php

namespace Automattic\WooCommerce\Internal\Api\DesignTime\Attributes;

#[\Attribute]
class ArrayTypeAttribute {
	public function __construct(
		public string $class_name
	)
	{}
}
