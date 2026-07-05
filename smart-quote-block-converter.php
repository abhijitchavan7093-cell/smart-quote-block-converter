<?php
/**
 * Plugin Name: Smart Quote Block Converter
 * Plugin URI: https://github.com/abhijitchavan7093-cell/smart-quote-block-converter
 * Description: Automatically converts Quote, Wishes, Shayari and Caption paragraphs into Gutenberg Quote Blocks.
 * Version: 1.0.0
 * Author: Abhijit Chavan
 * License: GPL2+
 * Text Domain: smart-quote-block-converter
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'SQBC_VERSION', '1.0.0' );
define( 'SQBC_PLUGIN_FILE', __FILE__ );
define( 'SQBC_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
define( 'SQBC_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

/**
 * Main Plugin Class
 */
final class SQBC_Plugin {

	/**
	 * Constructor
	 */
	public function __construct() {

		register_activation_hook( __FILE__, array( $this, 'activate' ) );
		register_deactivation_hook( __FILE__, array( $this, 'deactivate' ) );

		add_action( 'plugins_loaded', array( $this, 'load_plugin' ) );

	}

	/**
	 * Activation
	 */
	public function activate() {

		update_option( 'sqbc_version', SQBC_VERSION );

	}

	/**
	 * Deactivation
	 */
	public function deactivate() {

		// Reserved for future use.

	}

	/**
	 * Load Plugin
	 */
	public function load_plugin() {

		require_once SQBC_PLUGIN_PATH . 'includes/class-loader.php';

		$loader = new SQBC_Loader();
		$loader->init();

	}

}

/**
 * Start Plugin
 */
new SQBC_Plugin();
