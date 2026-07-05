<?php
/**
 * Scanner Class
 *
 * @package SmartQuoteBlockConverter
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class SQBC_Scanner {

	/**
	 * Get all published posts.
	 *
	 * @return array
	 */
	public function get_posts() {

		$args = array(
			'post_type'      => 'post',
			'post_status'    => 'publish',
			'posts_per_page' => -1,
			'fields'         => 'ids',
		);

		return get_posts( $args );

	}

	/**
	 * Get post content.
	 *
	 * @param int $post_id
	 * @return string
	 */
	public function get_content( $post_id ) {

		$post = get_post( $post_id );

		if ( ! $post ) {
			return '';
		}

		return $post->post_content;

	}

	/**
	 * Count paragraph blocks.
	 *
	 * @param string $content
	 * @return int
	 */
	public function count_paragraphs( $content ) {

		preg_match_all( '/<!-- wp:paragraph -->/', $content, $matches );

		return count( $matches[0] );

	}

}
