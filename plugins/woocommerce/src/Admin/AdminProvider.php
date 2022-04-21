<?php

namespace Automattic\WooCommerce\Admin;

use Automattic\WooCommerce\Internal\Admin\CategoryLookup;
use Automattic\WooCommerce\Internal\Admin\Events;
use Automattic\WooCommerce\Internal\DependencyManagement\AbstractServiceProvider;

/**
 * Admin Provider.
 */
class AdminProvider extends AbstractServiceProvider {

	/**
	 * Services provided by this provider.
	 *
	 * @var string[]
	 */
	protected $provides = array(
		CategoryLookup::class,
		Events::class,
	);

	/**
	 * Use the register method to register items with the container via the
	 * protected $this->leagueContainer property or the `getLeagueContainer` method
	 * from the ContainerAwareTrait.
	 *
	 * @return void
	 */
	public function register() {
		$this->share( CategoryLookup::class );
		$this->share( Events::class );
	}
}
