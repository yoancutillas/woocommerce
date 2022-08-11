<?php

namespace Automattic\WooCommerce\Tests\Internal;

use Automattic\WooCommerce\Container;
use Automattic\WooCommerce\Internal\DependencyManagement\ExtendedContainer;
use Automattic\WooCommerce\Internal\HookRegistry;
use ReflectionException;

/**
 * HookRegistryTest
 */
class HookRegistryTest extends \WC_Unit_Test_Case {
	/**
	 * @var HookRegistry $hook_registry
	 */
	private $hook_registry;

	/**
	 * @var ExtendedContainer
	 */
	private $extended_container;


	/**
	 * Set up.
	 *
	 * @return void
	 */
	public function setUp(): void {
		$container = new Container();

		$extended = ( new \ReflectionClass( $container ) )->getProperty( 'container' );
		$extended->setAccessible( true );

		$this->extended_container = $extended->getValue( $container );
		$this->hook_registry      = new HookRegistry( $container );
		parent::setUp();
	}

	/**
	 * Allow the given classname from the container.
	 *
	 * @param $classname
	 * @return void
	 */
	private function whitelist_classname( $classname ) {
		$reflection = new \ReflectionProperty( $this->extended_container, 'registration_whitelist' );
		$reflection->setAccessible( true );
		$whitelist   = $reflection->getValue( $this->extended_container );
		$whitelist[] = $classname;
		$reflection->setValue( $this->extended_container, $whitelist );
	}

	/**
	 * Test add_action returns false when an invalid callable is given.
	 *
	 * @return void
	 */
	public function test_it_returns_false_when_callable_is_invalid() {
		// the 2nd argument of add_action must be a string (global func) or a callable [classname, method].
		$result = $this->hook_registry->add_action( 'test', array( 'classname', 'method', 'invalid' ) );
		$this->assertFalse( $result );
	}

	/**
	 * Test adding and removing an action with a global function.
	 *
	 * @return void
	 */
	public function test_it_can_add_and_remove_action_with_function() {
		// Register.
		$action_name = 'hookRegistryTest-action-func';
		$this->hook_registry->add_action( $action_name, 'print_r' );

		// Call and confirm.
		ob_start();
		do_action( $action_name, 'test' );
		$content = ob_get_contents();
		ob_end_clean();
		$this->assertEquals( $content, 'test' );

		// Remove and confirm.
		$this->hook_registry->remove_action( $action_name, 'print_r' );
		ob_start();
		do_action( $action_name, 'test' );
		$content = ob_get_contents();
		ob_end_clean();
		$this->assertEmpty( $content );
	}

	/**
	 * Test adding and removing an action with a method.
	 *
	 * @return void
	 */
	public function test_it_can_add_and_remove_action_with_method() {
		$sut = new class() {
			public $count = 0;
			public function increase_count() {
				  $this->count++;
			}
		};

		$this->whitelist_classname( 'HookRegistryTest\Test' );

		$this->extended_container->share(
			'HookRegistryTest\Test',
			function() use ( $sut ) {
				return $sut;
			}
		);

		$action_name = 'hookRegistryTest-action-method';
		$callable    = array( 'HookRegistryTest\Test', 'increase_count' );
		$this->hook_registry->add_action( $action_name, $callable );
		do_action( $action_name );
		$this->assertSame( 1, $sut->count );
		$this->hook_registry->remove_action( $action_name, $callable );
		do_action( $action_name );
		$this->assertSame( 1, $sut->count );
	}

	/**
	 * Test adding and removing a filter with a function.
	 *
	 * @return void
	 */
	public function test_it_can_add_and_remove_filter_with_function() {
		// Register.
		$filter_name = 'hookRegistryTest-filter-func';
		$this->hook_registry->add_filter( $filter_name, '__return_true' );

		// Call and confirm.
		$result = apply_filters( $filter_name, false );
		$this->assertTrue( $result );

		// Remove and confirm.
		$this->hook_registry->remove_filter( $filter_name, '__return_true' );
		$result = apply_filters( $filter_name, false );
		$this->assertFalse( $result );
	}

	/**
	 * Test adding and removing a filter with a method.
	 *
	 * @return void
	 */
	public function test_it_can_add_and_remove_filter_with_method() {
		$sut = new class() {
			public $count = 0;
			public function add_efg( $arg ) {
				  return $arg . 'efg';
			}
		};

		$filter_name = 'hookRegistryTest-filter-method';
		$callable    = array( 'HookRegistryTest\Test2', 'add_efg' );

		$this->whitelist_classname( 'HookRegistryTest\Test2' );
		$this->extended_container->share(
			'HookRegistryTest\Test2',
			function() use ( $sut ) {
				return $sut;
			}
		);

		$this->hook_registry->add_filter( $filter_name, $callable );
		$result = apply_filters( $filter_name, 'abcd' );
		$this->assertEquals( 'abcdefg', $result );
		$this->hook_registry->remove_filter( $filter_name, $callable );
		$result = apply_filters( $filter_name, 'abcd' );
		$this->assertEquals( 'abcd', $result );
	}

	/**
	 * Test removing an action by I.D.
	 *
	 * @return void
	 */
	public function test_it_can_remove_action_by_id() {
		// Register.
		$action_name = 'hookRegistryTest-action-func-remove';
		$this->hook_registry->add_action( $action_name, 'print_r', 10, 1, 'action-id' );

		// Call.
		ob_start();
		do_action( $action_name, 'test' );
		$content = ob_get_contents();
		ob_end_clean();

		// Confirm.
		$this->assertEquals( $content, 'test' );

		// Remove the action.
		$this->hook_registry->remove_action_by_id( $action_name, 'action-id' );
		// Confirm the action has been removed.
		ob_start();
		do_action( $action_name, 'test' );
		$content = ob_get_contents();
		ob_end_clean();
		$this->assertEmpty( $content );
	}

	/**
	 * Test removing a filter by I.D.
	 *
	 * @return void
	 */
	public function test_it_can_remove_filter_by_id() {
		$filter_name = 'hookRegistryTest-filter-func-remove';
		$this->hook_registry->add_filter( $filter_name, '__return_true', 10, 1, 'filter-id' );
		$result = apply_filters( $filter_name, false );
		$this->assertTrue( $result );
		// Remove the filter.
		$this->hook_registry->remove_filter_by_id( $filter_name, 'filter-id' );
		// Confirm the filter has been removed.
		$result = apply_filters( $filter_name, false );
		$this->assertFalse( $result );
	}
}
