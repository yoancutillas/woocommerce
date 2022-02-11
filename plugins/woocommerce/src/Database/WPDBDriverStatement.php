<?php

namespace Automattic\WooCommerce\DataBase;

use Doctrine\DBAL\Driver\Exception;
use Doctrine\DBAL\Driver\Result;
use Doctrine\DBAL\Driver\Statement;
use Doctrine\DBAL\ParameterType;
use PDO;
use Traversable;

class WPDBDriverStatement implements \IteratorAggregate, Statement, Result {

	private $sql;
	private $conn;
	private $values;
	private $results;
	private $response;

	public function __construct( $conn, $sql ) {
		$this->sql = $sql;
		$this->conn = $conn;
		$this->values = [];
	}

	/**
	 * Retrieve an external iterator
	 * @link https://php.net/manual/en/iteratoraggregate.getiterator.php
	 * @return Traversable An instance of an object implementing <b>Iterator</b> or
	 * <b>Traversable</b>
	 * @throws Exception on failure.
	 */
	public function getIterator() {
		throw new WPDBException( 'Iterator not implemented' );
	}

	/**
	 * Returns the next row of the result as a numeric array or FALSE if there are no more rows.
	 *
	 * @return array<int,mixed>|false
	 *
	 * @throws Exception
	 */
	public function fetchNumeric() {
	}

	/**
	 * Returns the next row of the result as an associative array or FALSE if there are no more rows.
	 *
	 * @return array<string,mixed>|false
	 *
	 * @throws Exception
	 */
	public function fetchAssociative() {
		// TODO: Implement fetchAssociative() method.
	}

	/**
	 * Returns the first value of the next row of the result or FALSE if there are no more rows.
	 *
	 * @return mixed|false
	 *
	 * @throws Exception
	 */
	public function fetchOne() {
		// TODO: Implement fetchOne() method.
	}

	/**
	 * Returns an array containing all of the result rows represented as numeric arrays.
	 *
	 * @return array<int,array<int,mixed>>
	 *
	 * @throws Exception
	 */
	public function fetchAllNumeric(): array {
		// TODO: Implement fetchAllNumeric() method.
	}

	/**
	 * Returns an array containing all of the result rows represented as associative arrays.
	 *
	 * @return array<int,array<string,mixed>>
	 *
	 * @throws Exception
	 */
	public function fetchAllAssociative(): array {
		// TODO: Implement fetchAllAssociative() method.
	}

	/**
	 * Returns an array containing the values of the first column of the result.
	 *
	 * @return array<int,mixed>
	 *
	 * @throws Exception
	 */
	public function fetchFirstColumn(): array {
		// TODO: Implement fetchFirstColumn() method.
	}

	/**
	 * Discards the non-fetched portion of the result, enabling the originating statement to be executed again.
	 */
	public function free(): void {
		// TODO: Implement free() method.
	}/**
 * Closes the cursor, enabling the statement to be executed again.
 *
 * @return bool TRUE on success or FALSE on failure.
 * @deprecated Use Result::free() instead.
 *
 */
	public function closeCursor() {
		// TODO: Implement closeCursor() method.
	}

	/**
	 * Returns the number of columns in the result set
	 *
	 * @return int The number of columns in the result set represented
	 *                 by the PDOStatement object. If there is no result set,
	 *                 this method should return 0.
	 */
	public function columnCount() {
		// TODO: Implement columnCount() method.
	}

	/**
	 * Sets the fetch mode to use while iterating this statement.
	 *
	 * @param int $fetchMode The fetch mode must be one of the {@link FetchMode} constants.
	 * @param mixed $arg2
	 * @param mixed $arg3
	 *
	 * @return bool
	 * @deprecated Use one of the fetch- or iterate-related methods.
	 *
	 */
	public function setFetchMode( $fetchMode, $arg2 = null, $arg3 = null ) {
		// TODO: Implement setFetchMode() method.
	}

	/**
	 * Returns the next row of a result set.
	 *
	 * @param int|null $fetchMode Controls how the next row will be returned to the caller.
	 *                                    The value must be one of the {@link FetchMode} constants,
	 *                                    defaulting to {@link FetchMode::MIXED}.
	 * @param int $cursorOrientation For a ResultStatement object representing a scrollable cursor,
	 *                                    this value determines which row will be returned to the caller.
	 *                                    This value must be one of the \PDO::FETCH_ORI_* constants,
	 *                                    defaulting to \PDO::FETCH_ORI_NEXT. To request a scrollable
	 *                                    cursor for your ResultStatement object, you must set the \PDO::ATTR_CURSOR
	 *                                    attribute to \PDO::CURSOR_SCROLL when you prepare the SQL statement with
	 *                                    \PDO::prepare().
	 * @param int $cursorOffset For a ResultStatement object representing a scrollable cursor for which the
	 *                                    cursorOrientation parameter is set to \PDO::FETCH_ORI_ABS, this value
	 *                                    specifies the absolute number of the row in the result set that shall be
	 *                                    fetched.
	 *                                    For a ResultStatement object representing a scrollable cursor for which the
	 *                                    cursorOrientation parameter is set to \PDO::FETCH_ORI_REL, this value
	 *                                    specifies the row to fetch relative to the cursor position before
	 *                                    ResultStatement::fetch() was called.
	 *
	 * @return mixed The return value of this method on success depends on the fetch mode. In all cases, FALSE is
	 *               returned on failure.
	 * @deprecated Use fetchNumeric(), fetchAssociative() or fetchOne() instead.
	 *
	 */
	public function fetch( $fetchMode = null, $cursorOrientation = PDO::FETCH_ORI_NEXT, $cursorOffset = 0 ) {
		if ( is_array( $this->response ) && ! empty( $this->response ) ) {
			return (array) array_pop( $this->response );
		}
		return null;
	}

	/**
	 * Returns an array containing all of the result set rows.
	 *
	 * @param int|null $fetchMode Controls how the next row will be returned to the caller.
	 *                                       The value must be one of the {@link FetchMode} constants,
	 *                                       defaulting to {@link FetchMode::MIXED}.
	 * @param int|string|null $fetchArgument This argument has a different meaning depending on the value
	 *                                       of the $fetchMode parameter:
	 *                                       * {@link FetchMode::COLUMN}:
	 *                                         Returns the indicated 0-indexed column.
	 *                                       * {@link FetchMode::CUSTOM_OBJECT}:
	 *                                         Returns instances of the specified class, mapping the columns of each row
	 *                                         to named properties in the class.
	 *                                       * {@link PDO::FETCH_FUNC}: Returns the results of calling
	 *                                         the specified function, using each row's
	 *                                         columns as parameters in the call.
	 * @param mixed[]|null $ctorArgs Controls how the next row will be returned to the caller.
	 *                                       The value must be one of the {@link FetchMode} constants,
	 *                                       defaulting to {@link FetchMode::MIXED}.
	 *
	 * @return mixed[]
	 * @deprecated Use fetchAllNumeric(), fetchAllAssociative() or fetchFirstColumn() instead.
	 *
	 */
	public function fetchAll( $fetchMode = null, $fetchArgument = null, $ctorArgs = null ) {
		throw new WPDBException('Not implemented');
	}

	/**
	 * Returns a single column from the next row of a result set or FALSE if there are no more rows.
	 *
	 * @param int $columnIndex 0-indexed number of the column you wish to retrieve from the row.
	 *                         If no value is supplied, fetches the first column.
	 *
	 * @return mixed|false A single column in the next row of a result set, or FALSE if there are no more rows.
	 * @deprecated Use fetchOne() instead.
	 *
	 */
	public function fetchColumn( $columnIndex = 0 ) {
		throw new WPDBException('Not implemented');
	}

	/**
	 * Binds a value to a corresponding named (not supported by mysqli driver, see comment below) or positional
	 * placeholder in the SQL statement that was used to prepare the statement.
	 *
	 * As mentioned above, the named parameters are not natively supported by the mysqli driver, use executeQuery(),
	 * fetchAll(), fetchArray(), fetchColumn(), fetchAssoc() methods to have the named parameter emulated by doctrine.
	 *
	 * @param int|string $param Parameter identifier. For a prepared statement using named placeholders,
	 *                          this will be a parameter name of the form :name. For a prepared statement
	 *                          using question mark placeholders, this will be the 1-indexed position of the parameter.
	 * @param mixed $value The value to bind to the parameter.
	 * @param int $type Explicit data type for the parameter using the {@link ParameterType}
	 *                          constants.
	 *
	 * @return bool TRUE on success or FALSE on failure.
	 */
	public function bindValue( $param, $value, $type = ParameterType::STRING ) {
		$replacement = '%s';
		switch( $type ) {
			case ParameterType::STRING:
				$replacement = '%s';
				break;
			case ParameterType::INTEGER:
				$replacement = '%d';
				break;
		}

		$pos = strpos( $this->sql, '?' );
		$this->sql = substr_replace( $this->sql, $replacement, $pos, 1 );
		$this->values[] = $value;
		return true;
	}

	/**
	 * Binds a PHP variable to a corresponding named (not supported by mysqli driver, see comment below) or question
	 * mark placeholder in the SQL statement that was use to prepare the statement. Unlike PDOStatement->bindValue(),
	 * the variable is bound as a reference and will only be evaluated at the time
	 * that PDOStatement->execute() is called.
	 *
	 * As mentioned above, the named parameters are not natively supported by the mysqli driver, use executeQuery(),
	 * fetchAll(), fetchArray(), fetchColumn(), fetchAssoc() methods to have the named parameter emulated by doctrine.
	 *
	 * Most parameters are input parameters, that is, parameters that are
	 * used in a read-only fashion to build up the query. Some drivers support the invocation
	 * of stored procedures that return data as output parameters, and some also as input/output
	 * parameters that both send in data and are updated to receive it.
	 *
	 * @param int|string $param Parameter identifier. For a prepared statement using named placeholders,
	 *                             this will be a parameter name of the form :name. For a prepared statement using
	 *                             question mark placeholders, this will be the 1-indexed position of the parameter.
	 * @param mixed $variable Name of the PHP variable to bind to the SQL statement parameter.
	 * @param int $type Explicit data type for the parameter using the {@link ParameterType}
	 *                             constants. To return an INOUT parameter from a stored procedure, use the bitwise
	 *                             OR operator to set the PDO::PARAM_INPUT_OUTPUT bits for the data_type parameter.
	 * @param int|null $length You must specify maxlength when using an OUT bind
	 *                             so that PHP allocates enough memory to hold the returned value.
	 *
	 * @return bool TRUE on success or FALSE on failure.
	 */
	public function bindParam( $param, &$variable, $type = ParameterType::STRING, $length = null ) {
		// TODO: Implement bindParam() method.
	}

	/**
	 * Fetches the SQLSTATE associated with the last operation on the statement handle.
	 *
	 * @return string|int|bool The error code string.
	 * @see Doctrine_Adapter_Interface::errorCode()
	 *
	 * @deprecated The error information is available via exceptions.
	 *
	 */
	public function errorCode() {
		// TODO: Implement errorCode() method.
	}

	/**
	 * Fetches extended error information associated with the last operation on the statement handle.
	 *
	 * @return mixed[] The error info array.
	 * @deprecated The error information is available via exceptions.
	 *
	 */
	public function errorInfo() {
		// TODO: Implement errorInfo() method.
	}

	/**
	 * Executes a prepared statement
	 *
	 * If the prepared statement included parameter markers, you must either:
	 * call PDOStatement->bindParam() to bind PHP variables to the parameter markers:
	 * bound variables pass their value as input and receive the output value,
	 * if any, of their associated parameter markers or pass an array of input-only
	 * parameter values.
	 *
	 * @param mixed[]|null $params An array of values with as many elements as there are
	 *                             bound parameters in the SQL statement being executed.
	 *
	 * @return bool TRUE on success or FALSE on failure.
	 */
	public function execute( $params = null ) {
		global $wpdb;
		$this->sql = $this->wpdb_escape( $this->sql, $this->values );
		$this->results = $wpdb->query( $this->sql );
		$this->response = $wpdb->last_result;
		return $this->results;
	}

	/**
	 * Escape the query and replace placeholders with escaped values.
	 *
	 * @param $sql
	 *
	 * @param $values
	 */
	private function wpdb_escape( $sql, $values ) {
		global $wpdb;
		return $wpdb->prepare( $sql, $values );
	}

	/**
	 * Returns the number of rows affected by the last DELETE, INSERT, or UPDATE statement
	 * executed by the corresponding object.
	 *
	 * If the last SQL statement executed by the associated Statement object was a SELECT statement,
	 * some databases may return the number of rows returned by that statement. However,
	 * this behaviour is not guaranteed for all databases and should not be
	 * relied on for portable applications.
	 *
	 * @return int The number of rows.
	 */
	public function rowCount() {
		// TODO: Implement rowCount() method.
	}
}
