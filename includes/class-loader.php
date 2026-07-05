<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class SQBC_Loader {

	public function init() {

		require_once SQBC_PLUGIN_PATH . 'includes/class-admin.php';
		require_once SQBC_PLUGIN_PATH . 'includes/class-scanner.php';

		if ( is_admin() ) {
			new SQBC_Admin();
		}

	}

}
