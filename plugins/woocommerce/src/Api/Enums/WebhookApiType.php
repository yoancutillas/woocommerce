<?php

namespace Automattic\WooCommerce\Api\Enums;

#[Name('WebhookApiVersion')]
class WebhookApiType {
	public const API_V1 = 'wp_api_v1';
	public const API_V2 = 'wp_api_v2';
	public const API_V3 = 'wp_api_v3';
	public const LEGACY_V3 = 'legacy_v3';
}
