<?php
/**
 * Admin Class
 *
 * @package SmartQuoteBlockConverter
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class SQBC_Admin {

	/**
	 * Constructor
	 */
	public function __construct() {

		add_action( 'admin_menu', array( $this, 'register_menu' ) );

	}

	/**
	 * Register Admin Menu
	 */
	public function register_menu() {

		add_menu_page(
			'Smart Quote Block Converter',
			'Quote Converter',
			'manage_options',
			'sqbc-dashboard',
			array( $this, 'dashboard_page' ),
			'dashicons-format-quote',
			30
		);

	}

	/**
	 * Dashboard Page
	 */
	public function dashboard_page() {

		?>

		<div class="wrap">

			<h1>🚀 Smart Quote Block Converter</h1>

			<hr>

			<div style="background:#fff;padding:20px;border:1px solid #ddd;border-radius:8px;max-width:900px;">

				<h2>Plugin Installed Successfully ✅</h2>

				<p><strong>Version:</strong> 1.0.0</p>

				<p>Welcome to Smart Quote Block Converter.</p>

				<p>In the next step we will build the Scanner Engine.</p>

				<hr>

				<a href="#" class="button button-primary button-large">
					Analyze Posts
				</a>

			</div>

		</div>

		<?php

	}

}
