<?php

namespace Automattic\WooCommerce\DataBase;

use Automattic\Jetpack\Constants;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Driver;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Platforms\MySqlPlatform;
use Doctrine\DBAL\Schema\AbstractSchemaManager;

class WPDBDriver implements Driver {

	/**
	 * Attempts to create a connection with the database.
	 *
	 * The usage of NULL to indicate empty username or password is deprecated. Use an empty string instead.
	 *
	 * @param mixed[] $params All connection parameters passed by the user.
	 * @param string|null $username The username to use when connecting.
	 * @param string|null $password The password to use when connecting.
	 * @param mixed[] $driverOptions The driver options to use when connecting.
	 *
	 * @return \Doctrine\DBAL\Driver\Connection The database connection.
	 */
	public function connect( array $params = array(), $username = null, $password = null, array $driverOptions = [] ) {
		return new WPDBDriverConnection( $this );
	}

	/**
	 * Gets the name of the driver.
	 *
	 * @return string The name of the driver.
	 * @deprecated
	 *
	 */
	public function getName() {
		return 'wpdb';
	}

	/**
	 * Gets the DatabasePlatform instance that provides all the metadata about
	 * the platform this driver connects to.
	 *
	 * @return AbstractPlatform The database platform.
	 */
	public function getDatabasePlatform() {
		return new MySqlPlatform();
	}

	/**
	 * Gets the SchemaManager that can be used to inspect and change the underlying
	 * database schema of the platform this driver connects to.
	 *
	 * @return AbstractSchemaManager
	 */
	public function getSchemaManager( Connection $conn ) {
		throw new WPDBException( 'Use native queries to manage schema' );
	}

	/**
	 * Gets the name of the database connected to for this driver.
	 *
	 * @return string The name of the database.
	 * @deprecated Use Connection::getDatabase() instead.
	 *
	 */
	public function getDatabase( Connection $conn ) {
		return Constants::get_constant( 'DB_NAME' );
	}
}
