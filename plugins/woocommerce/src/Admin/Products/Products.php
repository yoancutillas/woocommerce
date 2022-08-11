<?php

namespace Automattic\WooCommerce\Admin\Products;

/**
 * Admin products class.
 */
class Products {
	/**
	 * Init.
	 */
	public function init() {
		add_action( 'init', array( __CLASS__, 'maybe_load_iso_editor' ) );
	}

	/**
	 * Maybe load the Isolated Editor styles and scripts.
	 */
	public function maybe_load_iso_editor() {
		$iso_editor = new IsoEditorGutenberg();
		$iso_editor->load();
	}
}
