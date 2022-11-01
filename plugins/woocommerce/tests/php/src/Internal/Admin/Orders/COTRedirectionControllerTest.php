<?php

use Automattic\WooCommerce\Internal\Admin\Orders\COTRedirectionController;

/**
 * Describes our redirection logic covering HPOS admin screens when Custom Order Tables are not authoritative.
 */
class COTRedirectionControllerTest extends WC_Unit_Test_Case {
	/**
	 * @var COTRedirectionController
	 */
	private $sut;

	private $redirected_to = '';

	public function setUp(): void {
		parent::setUp();

		$this->sut = new COTRedirectionController();
		$this->redirected_to = '';

		add_filter( 'wp_redirect', array( $this, 'watch_and_anull_redirects' ) );
	}

	public function tearDown(): void {
		parent::tearDown();
		remove_filter( 'wp_redirect', array( $this, 'watch_and_anull_redirects' ) );
	}

	public function watch_and_anull_redirects( $url ) {
		$this->redirected_to = $url;
		return null;
	}

	public function test_redirects_only_impact_hpos_admin_requests() {
		$this->sut->handle_hpos_admin_requests( array( 'page' => 'wc-orders' ) );
		$this->assertNotEmpty( $this->redirected_to, 'A redirect was attempted in relation to an HPOS admin request.' );

		$this->sut->handle_hpos_admin_requests( array( 'page' => 'foo' ) );
		$this->assertEmpty( $this->redirected_to, 'A redirect was not attempted in relation to a non-HPOS admin request.' );
	}
}
