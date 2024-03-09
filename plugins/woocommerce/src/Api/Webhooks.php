<?php

namespace Automattic\WooCommerce\Api;

use Automattic\WooCommerce\Api\Enums\WebhookApiType;
use Automattic\WooCommerce\Api\Enums\WebhookStatus;
use Automattic\WooCommerce\Api\InputTypes\CreateWebhookInput;
use Automattic\WooCommerce\Api\InputTypes\User;
use Automattic\WooCommerce\Api\ObjectTypes\Webhook;
use  Automattic\WooCommerce\Internal\Api\Attributes\WebMutationAttribute as WebMutation;
use  Automattic\WooCommerce\Internal\Api\Attributes\WebQueryAttribute as WebQuery;
use Automattic\WooCommerce\Internal\GraphQL\ApiException;

class Webhooks {
	#[WebMutation('CreateWebhook')]
	public function create_webhook(CreateWebhookInput $input): Webhook {
		$w = new Webhook();

		$w->api_version = $input->api_version;
		$w->date_created = '2023-12-09 12:34:56';
		$w->id = 1234;
		$w->delivery_url = $input->delivery_url;
		$w->name = $input->name;
		$w->failure_count = 7;
		$w->pending_delivery = true;
		$w->secret = $input->secret;
		$w->status = $input->status;
		$w->topic = $input->topic;
		$w->user = new User();
		$w->user->id = $input->user_id;
		$w->user->email = "konamiman@konamiman.com";
		$w->user->login = "konamiman";

		return $w;
	}

	#[WebQuery('Webhooks')]
	public function get_webhooks(): array {
		return [$this->get_webhook(111), $this->get_webhook(222)];
	}

	#[WebQuery('Webhook')]
	public function get_webhook(int $id, array $_fields_info): ?Webhook {
		$w = new Webhook();
        throw new \InvalidArgumentException("Whomp whomp!");
		$w->api_version = WebhookApiType::API_V3;
		$w->date_created = '2023-12-09 12:34:56';
		$w->id = $id;
		$w->delivery_url = "http://www.konamiman.com";
		$w->name = "Newhook";
		$w->failure_count = 7;
		$w->pending_delivery = true;
		$w->secret = "Ssssh!!";
		$w->status = WebhookStatus::PAUSED;
		$w->topic = "Topicazo";
		$w->user = new User();
		$w->user->id = 5678;
		$w->user->email = "konamiman@konamiman.com";
		$w->user->login = "konamiman";

		return $w;
	}
}
