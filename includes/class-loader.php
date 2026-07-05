<?php
/**
 * Plugin Loader
 *
 * @package SmartQuoteBlockConverter
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class SQBC_Loader {

	/**
	 * Initialize plugin.
	 */
	public function init() {

		// Admin Area
		if ( is_admin() ) {
			require_once SQBC_PLUGIN_PATH . 'includes/class-admin.php';
			new SQBC_Admin();
		}

	}

}
