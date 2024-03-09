<?php

namespace Automattic\WooCommerce\Internal\Api\DesignTime\Attributes;

#[\Attribute]
class WebQueryAttribute {
	public function __construct(
		public string $graphql_query_name,
		public ?string $rest_name = null
	)
	{}
}
