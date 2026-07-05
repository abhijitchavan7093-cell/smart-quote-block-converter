<?php
/**
 * Smart Detector
 *
 * @package SmartQuoteBlockConverter
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class SQBC_Detector {

	/**
	 * Check if paragraph is already blockquote.
	 */
	public function is_blockquote( $content ) {

		return ( strpos( $content, '<blockquote' ) !== false );

	}

	/**
	 * Check empty paragraph.
	 */
	public function is_empty( $text ) {

		$text = trim( wp_strip_all_tags( html_entity_decode( $text ) ) );

		return empty( $text );

	}

	/**
	 * Detect if paragraph looks like a Quote/Wish/Shayari.
	 */
	public function is_quote( $text ) {

		$text = trim( wp_strip_all_tags( $text ) );

		// Too short
		if ( strlen( $text ) < 15 ) {
			return false;
		}

		// Too long (probably description)
		if ( strlen( $text ) > 350 ) {
			return false;
		}

		// Contains link
		if ( stripos( $text, 'http' ) !== false ) {
			return false;
		}

		// Existing HTML
		if ( strpos( $text, '<a' ) !== false ) {
			return false;
		}

		return true;

	}

}
