<?php

namespace Automattic\WooCommerce\Internal\DataStores;

use Automattic\WooCommerce\Admin\Overrides\Order;
use Automattic\WooCommerce\Entity\OrderEntity;
use Doctrine\ORM\Mapping\Builder\ClassMetadataBuilder;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Mapping\ClassMetadataInfo;
use Doctrine\Persistence\Mapping\Driver\MappingDriver;

class OrderMapping implements MappingDriver {


	/**
	 * Loads the metadata for the specified class into the provided container.
	 *
	 * @param string $className
	 *
	 * @psalm-param class-string<T> $className
	 * @psalm-param \Doctrine\Persistence\Mapping\ClassMetadata $metadata
	 *
	 * @return void
	 *
	 * @template T of object
	 */
	public function loadMetadataForClass( $className, \Doctrine\Persistence\Mapping\ClassMetadata $metadata ) {
		global $wpdb;
		switch ( $className ) {
			case OrderEntity::class:
				$metadata->mapField( array( 'id' => true, 'fieldName' => 'id', 'type' => 'integer' ) );
				$metadata->mapField( array( 'fieldName' => 'post_id', 'type' => 'integer' ) );
				$metadata->mapField( array( 'fieldName' => 'status', 'columnName' => 'status', 'type' => 'string' ) );
				$metadata->mapField( array( 'fieldName' => 'currency', 'type' => 'string' ) );
				$metadata->mapField( array( 'fieldName' => 'tax_amount', 'type' => 'decimal' ) );
				$metadata->mapField( array( 'fieldName' => 'total_amount', 'type' => 'decimal' ) );
				$metadata->mapField( array( 'fieldName' => 'customer_id', 'type' => 'integer' ) );
				$metadata->mapField( array( 'fieldName' => 'billing_email', 'type' => 'string' ) );
				$metadata->mapField( array( 'fieldName' => 'date_created_gmt', 'type' => 'datetime' ) );
				$metadata->mapField( array( 'fieldName' => 'date_updated_gmt', 'type' => 'datetime' ) );
				$metadata->mapField( array( 'fieldName' => 'parent_order_id', 'type' => 'integer' ) );
				$metadata->mapField( array( 'fieldName' => 'payment_method', 'type' => 'string' ) );
				$metadata->mapField( array( 'fieldName' => 'payment_method_title', 'type' => 'string' ) );
				$metadata->mapField( array( 'fieldName' => 'transaction_id', 'type' => 'string' ) );
				$metadata->mapField( array( 'fieldName' => 'ip_address', 'type' => 'string' ) );
				$metadata->mapField( array( 'fieldName' => 'user_agent', 'type' => 'string' ) );
				$metadata->setIdentifier( array( 'id' ) );
				$metadata->setPrimaryTable( array( 'name' => $wpdb->prefix . 'wc_orders' ) );
				break;
		}
	}

	/**
	 * Gets the names of all mapped classes known to this driver.
	 *
	 * @return string[] The names of all mapped classes known to this driver.
	 * @psalm-return list<class-string>
	 */
	public function getAllClassNames() {
		return array(
			OrderEntity::class
		);
	}

	/**
	 * Returns whether the class with the specified name should have its metadata loaded.
	 * This is only the case if it is either mapped as an Entity or a MappedSuperclass.
	 *
	 * @param string $className
	 *
	 * @psalm-param class-string $className
	 *
	 * @return bool
	 */
	public function isTransient( $className ) {
		return true;
	}
}
