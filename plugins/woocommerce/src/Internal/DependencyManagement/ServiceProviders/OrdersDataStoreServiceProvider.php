<?php
/**
 * OrdersDataStoreServiceProvider class file.
 */

namespace Automattic\WooCommerce\Internal\DependencyManagement\ServiceProviders;

use Automattic\WooCommerce\Internal\DataStores\OrderMapping;
use Automattic\WooCommerce\Internal\DependencyManagement\AbstractServiceProvider;
use Automattic\WooCommerce\Internal\DataStores\Orders\DataSynchronizer;
use Automattic\WooCommerce\Internal\DataStores\Orders\CustomOrdersTableController;
use Automattic\WooCommerce\Internal\DataStores\Orders\OrdersTableDataStore;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\ClassMetadata;

/**
 * Service provider for the ProductAttributesLookupServiceProvider namespace.
 */
class OrdersDataStoreServiceProvider extends AbstractServiceProvider {

	/**
	 * The classes/interfaces that are serviced by this service provider.
	 *
	 * @var array
	 */
	protected $provides = array(
		DataSynchronizer::class,
		CustomOrdersTableController::class,
		OrdersTableDataStore::class,
		OrderMapping::class
	);

	/**
	 * Register the classes.
	 */
	public function register() {
		$this->share( DataSynchronizer::class )->addArgument( OrdersTableDataStore::class );
		$this->share( CustomOrdersTableController::class )->addArguments( array( OrdersTableDataStore::class, DataSynchronizer::class ) );
		$this->share( OrdersTableDataStore::class )->addArgument( EntityManager::class );
		$this->share( OrderMapping::class );
	}
}
