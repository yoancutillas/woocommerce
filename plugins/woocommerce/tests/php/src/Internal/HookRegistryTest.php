<?php

namespace Automattic\WooCommerce\Tests\Internal;
use Automattic\WooCommerce\Container;
use Automattic\WooCommerce\Internal\HookRegistry;

class HookRegistryTest extends \WC_Unit_Test_Case
{
	/**
	 * @var HookRegistry $hook_registry
	 */
	private $hook_registry;

	public function setUp(): void
	{
	    $this->hook_registry = new HookRegistry(new Container());
		parent::setUp();
	}
	/**
	 * add_action or add_filter should return false when callable is not string or [classname, method]
	 */
	public function test_it_returns_false_when_callable_is_invalid()
	{
		$result = $this->hook_registry->add_action('test', ['classname', 'method', 'invalid']);
		$this->assertFalse($result);
	}

	public function test_it_adds_action_with_function()
	{
		$this->hook_registry->add_action('hookRegistryTest-action-func', 'print_r');
		ob_start();
		do_action('hookRegistryTest-action', 'test');
		$content = ob_get_contents();
		ob_end_clean();
		$this->assertEquals($content, 'test');
	}

	public function test_it_adds_action_with_method()
	{
		$sut = new class() {
			public $count = 0;
			public function increase_count() {
				$this->count++;
			}
		};

//		$this->hook_registry->add_action('hookRegistryTest-action-method', [])
	}
//
//	public function test_it_adds_filter_with_action()
//	{
//
//	}
//
//	public function test_it_adds_filter_with_method()
//	{
//
//	}
//
//	public function test_it_removes_action_with_func()
//	{
//
//	}
//
//	public function test_it_removes_action_with_method()
//	{
//
//	}
//
//	public function test_it_removes_filter_with_func()
//	{
//
//	}
//
//	public function test_it_removes_filter_with_method()
//	{
//
//	}
//
//	public function test_it_removes_action_by_id()
//	{
//
//	}
//
//	public function test_it_removes_filter_by_id()
//	{
//
//	}
}
