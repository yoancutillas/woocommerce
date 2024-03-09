<?php

namespace Automattic\WooCommerce\Api\Interfaces;

use Automattic\WooCommerce\Api\Enums\WebhookApiType;
use Automattic\WooCommerce\Api\Enums\WebhookStatus;

trait WebhookDefinition {
	public string $name;

	public string $topic;

	public string $delivery_url;

	public ?string $secret;

	#[EnumType(WebhookApiType::class)]
	public string $api_version;
}
