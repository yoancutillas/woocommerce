<?php

namespace Automattic\WooCommerce\Entity;

class OrderEntity {
	private $id;
	private $post_id;
	private $status;
	private $currency;
	private $tax_amount;
	private $total_amount;
	private $customer_id;
	private $billing_email;
	private $date_created_gmt;
	private $date_updated_gmt;
	private $parent_order_id;
	private $payment_method;
	private $payment_method_title;
	private $transaction_id;
	private $ip_address;
	private $user_agent;
}

