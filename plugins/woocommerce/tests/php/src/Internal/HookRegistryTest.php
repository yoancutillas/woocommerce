<?php

namespace Automattic\WooCommerce\Tests\Internal;

use Automattic\WooCommerce\Container;
use Automattic\WooCommerce\Internal\DependencyManagement\ExtendedContainer;
use Automattic\WooCommerce\Internal\HookRegistry;

class HookRegistryTest extends \WC_Unit_Test_Case {

	/**
	 * @var HookRegistry $hook_registry
	 */
	private $hook_registry;

	/**
	 * @var ExtendedContainer
	 */
	private $extended_container;


	public function setUp(): void {
		$container = new Container();

		$reflection = new \ReflectionClass( $container );
		$extended   = $reflection->getProperty( 'container' );
		$extended->setAccessible( true );
		$extended = $extended->getValue( $container );

		$this->extended_container = $extended;
		$this->hook_registry      = new HookRegistry( $container );
		parent::setUp();
	}

	/**
	 * Allow the given classname from the container.
	 *
	 * @param $classname
	 * @return void
	 * @throws \ReflectionException
	 */
	private function whitelist_classname( $classname ) {
		$reflection = new \ReflectionProperty( $this->extended_container, 'registration_whitelist' );
		$reflection->setAccessible( true );
		$whitelist   = $reflection->getValue( $this->extended_container );
		$whitelist[] = $classname;
		$reflection->setValue( $this->extended_container, $whitelist );
	}

	public function test_it_returns_false_when_callable_is_invalid() {
		// the 2nd argument of add_action must be a string (global func) or a callable [classname, method].
		$result = $this->hook_registry->add_action( 'test', array( 'classname', 'method', 'invalid' ) );
		$this->assertFalse( $result );
	}

	public function test_it_can_add_and_remove_action_with_function() {
		 $action_name = 'hookRegistryTest-action-func';
		$this->hook_registry->add_action( $action_name, 'print_r' );
		ob_start();
		do_action( $action_name, 'test' );
		$content = ob_get_contents();
		ob_end_clean();
		$this->assertEquals( $content, 'test' );
		$this->hook_registry->remove_action( $action_name, 'print_r' );
		ob_start();
		do_action( $action_name, 'test' );
		$content = ob_get_contents();
		ob_end_clean();
		$this->assertEmpty( $content );
	}

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

	public function test_it_can_add_and_remove_filter_with_function() {
		 $filter_name = 'hookRegistryTest-filter-func';
		$this->hook_registry->add_filter( $filter_name, '__return_true' );
		$result = apply_filters( $filter_name, false );
		$this->assertTrue( $result );
		$this->hook_registry->remove_filter( $filter_name, '__return_true' );
		$result = apply_filters( $filter_name, false );
		$this->assertFalse( $result );
	}

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


	public function test_it_removes_action_by_id() {
		$action_name = 'hookRegistryTest-action-func-remove';
		$this->hook_registry->add_action( $action_name, 'print_r', 10, 1, 'action-id' );
		ob_start();
		do_action( $action_name, 'test' );
		$content = ob_get_contents();
		ob_end_clean();
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

	public function test_it_removes_filter_by_id() {
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
