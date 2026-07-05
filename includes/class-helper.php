<?php
/**
 * Helper Functions
 *
 * @package SmartQuoteBlockConverter
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class SQBC_Helper {

	/**
	 * Clean text
	 */
	public static function clean_text( $text ) {

		$text = wp_strip_all_tags( $text );

		$text = html_entity_decode( $text );

		$text = trim( $text );

		return $text;
	}


	/**
	 * Check Gutenberg content
	 */
	public static function is_gutenberg( $content ) {

		if ( strpos( $content, '<!-- wp:' ) !== false ) {
			return true;
		}

		return false;
	}


	/**
	 * Debug logger
	 */
	public static function log( $message ) {

		if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {

			error_log(
				'[SQBC] ' . print_r( $message, true )
			);

		}
	}

}
