<?php
/**
 * Converter Class
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class SQBC_Converter {

	public function convert_post( $post_id, $dry_run = true ) {

		$post = get_post( $post_id );

		if ( ! $post || empty( $post->post_content ) ) {
			return array(
				'success' => false,
				'message' => 'Post not found.',
				'converted' => 0,
			);
		}

		$content = $post->post_content;
		$result  = $this->convert_content( $content );

		if ( ! $dry_run && $result['converted'] > 0 ) {
			wp_update_post(
				array(
					'ID'           => $post_id,
					'post_content' => $result['content'],
				)
			);
		}

		return array(
			'success'   => true,
			'post_id'   => $post_id,
			'title'     => get_the_title( $post_id ),
			'converted' => $result['converted'],
			'dry_run'   => $dry_run,
		);
	}

	public function convert_content( $content ) {

		$blocks = parse_blocks( $content );

		if ( empty( $blocks ) ) {
			return array(
				'content'   => $content,
				'converted' => 0,
			);
		}

		$converted = 0;
		$new_blocks = array();
		$quote_mode = false;

		foreach ( $blocks as $block ) {

			$block_name = isset( $block['blockName'] ) ? $block['blockName'] : '';

			if ( 'core/heading' === $block_name ) {
				$quote_mode = true;
				$new_blocks[] = $block;
				continue;
			}

			if ( 'core/quote' === $block_name ) {
				$new_blocks[] = $block;
				continue;
			}

			if ( 'core/paragraph' === $block_name ) {

				$text = isset( $block['innerHTML'] ) ? $block['innerHTML'] : '';

				if ( $this->is_empty_paragraph( $text ) ) {
					$new_blocks[] = $block;
					continue;
				}

				if ( $this->is_description_paragraph( $text ) ) {
					$quote_mode = false;
					$new_blocks[] = $block;
					continue;
				}

				if ( $quote_mode && $this->is_quote_paragraph( $text ) ) {
					$new_blocks[] = $this->make_quote_block( $block );
					$converted++;
					continue;
				}
			}

			$new_blocks[] = $block;
		}

		return array(
			'content'   => serialize_blocks( $new_blocks ),
			'converted' => $converted,
		);
	}

	private function make_quote_block( $paragraph_block ) {

		$inner_html = isset( $paragraph_block['innerHTML'] ) ? $paragraph_block['innerHTML'] : '';
		$inner_content = isset( $paragraph_block['innerContent'] ) ? $paragraph_block['innerContent'] : array( $inner_html );

		return array(
			'blockName'    => 'core/quote',
			'attrs'        => array(),
			'innerBlocks'  => array(
				array(
					'blockName'    => 'core/paragraph',
					'attrs'        => array(),
					'innerBlocks'  => array(),
					'innerHTML'    => $inner_html,
					'innerContent' => $inner_content,
				),
			),
			'innerHTML'    => '<blockquote class="wp-block-quote">' . $inner_html . '</blockquote>',
			'innerContent' => array(
				'<blockquote class="wp-block-quote">',
				null,
				'</blockquote>',
			),
		);
	}

	private function is_empty_paragraph( $html ) {

		$text = trim( wp_strip_all_tags( html_entity_decode( $html ) ) );
		$text = str_replace( array( '&nbsp;', "\xc2\xa0" ), '', $text );

		return '' === trim( $text );
	}

	private function is_description_paragraph( $html ) {

		$plain = trim( wp_strip_all_tags( $html ) );

		if ( false !== stripos( $html, '<a ' ) ) {
			return true;
		}

		if ( mb_strlen( $plain ) > 260 ) {
			return true;
		}

		$stop_words = array(
			'Conclusion',
			'FAQ',
			'FAQs',
			'Frequently Asked',
			'WhatsApp',
			'Instagram',
			'Facebook',
			'share',
			'comment',
			'উপসংহার',
			'প্রশ্ন',
			'সোশ্যাল মিডিয়া',
			'ক্যাপশন আপনার',
			'সাহায্য করে',
			'এখানে রয়েছে',
			'আপনি যদি',
			'নমस्कार',
			'दोस्तों',
			'शेयर',
			'निष्कर्ष',
			'अक्सर पूछे',
			'वाचा',
			'निष्कर्ष',
			'प्रश्न',
		);

		foreach ( $stop_words as $word ) {
			if ( false !== mb_stripos( $plain, $word ) ) {
				return true;
			}
		}

		return false;
	}

	private function is_quote_paragraph( $html ) {

		$plain = trim( wp_strip_all_tags( $html ) );

		if ( mb_strlen( $plain ) < 8 ) {
			return false;
		}

		if ( mb_strlen( $plain ) > 260 ) {
			return false;
		}

		if ( false !== stripos( $html, '<a ' ) ) {
			return false;
		}

		if ( preg_match( '/^["“‘\'].*["”’\']?/u', $plain ) ) {
			return true;
		}

		if ( preg_match( '/[\x{1F300}-\x{1FAFF}]/u', $plain ) ) {
			return true;
		}

		return true;
	}
}
