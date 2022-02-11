<?php

namespace Automattic\WooCommerce\DataBase;

use Doctrine\DBAL\Driver\Statement;
use Doctrine\DBAL\ParameterType;

class WPDBDriverConnection extends \Doctrine\DBAL\Connection {

	public function __construct( $driver ) {
		parent::__construct(
			array(),
			$driver
		);
	}

	/**
	 * Prepares a statement for execution and returns a Statement object.
	 *
	 * @param string $sql
	 *
	 * @return Statement
	 */
	public function prepare( $sql ) {
		return new WPDBDriverStatement( $this, $sql );
	}

	/**
	 * Executes an SQL statement, returning a result set as a Statement object.
	 *
	 * @return Statement
	 */
	public function execute() {
		$args = func_get_args();
		$sql = $args[0];
		$stmt = $this->prepare( $sql );
		$stmt->execute();
		return $stmt;
	}

	/**
	 * Quotes a string for use in a query.
	 *
	 * @param mixed $value
	 * @param int $type
	 *
	 * @return mixed
	 */
	public function quote( $value, $type = ParameterType::STRING ) {
		return $value;
	}

	/**
	 * Executes an SQL statement and return the number of affected rows.
	 *
	 * @param string $sql
	 *
	 * @return int
	 */
	public function exec( $sql ) {
		// Get current suppress error value, and set it back.
		$supress_errors = $this->wpdb->suppress_errors();
		$this->wpdb->suppress_errors( $supress_errors );

		if ( ! $supress_errors ) {
			ob_start();
		}

		$result = $this->wpdb->query( $sql );

		if ( ! $supress_errors && false === $result ) {
			$error = ob_get_clean();
			throw new WPDBException( $error );
		}
		return $result;
	}

	/**
	 * Returns the ID of the last inserted row or sequence value.
	 *
	 * @param string|null $name
	 *
	 * @return string|int|false
	 */
	public function lastInsertId( $name = null ) {
		return $this->wpdb->insert_id;
	}

	/**
	 * Initiates a transaction.
	 *
	 * @return bool TRUE on success or FALSE on failure.
	 */
	public function beginTransaction() {
		throw new WPDBException( __( 'Transactions are not supported', 'woocommerce' ) );
	}

	/**
	 * Commits a transaction.
	 *
	 * @return bool TRUE on success or FALSE on failure.
	 */
	public function commit() {
		throw new WPDBException( __( 'Transactions are not supported', 'woocommerce' ) );
	}

	/**
	 * Rolls back the current transaction, as initiated by beginTransaction().
	 *
	 * @return bool TRUE on success or FALSE on failure.
	 */
	public function rollBack() {
		throw new WPDBException( __( 'Transactions are not supported', 'woocommerce' ) );
	}

	/**
	 * Returns the error code associated with the last operation on the database handle.
	 *
	 * @return string|null The error code, or null if no operation has been run on the database handle.
	 * @deprecated The error information is available via exceptions.
	 *
	 */
	public function errorCode() {
		throw new WPDBException( 'Getting error code is not supported' );
	}

	/**
	 * Returns extended error information associated with the last operation on the database handle.
	 *
	 * @return mixed[]
	 * @deprecated The error information is available via exceptions.
	 *
	 */
	public function errorInfo() {
		return array( $this->wpdb->last_error );
	}
}
