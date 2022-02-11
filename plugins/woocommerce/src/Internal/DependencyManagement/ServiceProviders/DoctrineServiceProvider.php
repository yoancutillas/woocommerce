<?php

namespace Automattic\WooCommerce\Internal\DependencyManagement\ServiceProviders;

use Automattic\WooCommerce\DataBase\WPDBCache;
use Automattic\WooCommerce\DataBase\WPDBDriver;
use Automattic\WooCommerce\DataBase\WPDBDriverConnection;
use Automattic\WooCommerce\Internal\DataStores\OrderMapping;
use Automattic\WooCommerce\Internal\DependencyManagement\AbstractServiceProvider;
use Cassandra\Set;
use Doctrine\Common\Cache\Cache;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Setup;

class DoctrineServiceProvider extends AbstractServiceProvider {
	protected $provides = array(
		WPDBDriver::class,
		WPDBDriverConnection::class,
		EntityManager::class,
		Cache::class
	);

	public function register() {
		$db_driver = new WPDBDriver();
		$db_connection = $db_driver->connect();
		$cache = new WPDBCache();
		$configuration = Setup::createConfiguration(
			WP_DEBUG,
			null,
			$cache
		);
		$configuration->setMetadataDriverImpl( new OrderMapping() );
		$entity_manager = EntityManager::create( $db_connection, $configuration );

		$this->share( WPDBDriver::class, $db_driver );
		$this->share( WPDBDriverConnection::class, $db_connection );
		$this->share( EntityManager::class, $entity_manager );
		$this->share( Cache::class, $cache );
	}
}

