<?php
/**
 * Class RichEditing
 *
 * @author Terry Lin
 * @link https://terryl.in/
 *
 * @package Githuber
 * @since 1.6.0
 * @version 1.6.0
 */

namespace Githuber\Controller;
use Githuber\Controller\Monolog as Monolog;

class RichEditing {

	const MD_POST_META_ENABLED  = '_is_githuber_markdown_enabled';

	/**
	 * Constructer.
	 */
	public function __construct() {

	}

	/**
	 * Enable rich editor.
	 */
	public function enable() {
		add_action( 'admin_init', array( $this, '_rich_editing_true' ) );
	}
		
	/**
	 * Enable rich editor.
	 */
	public function disable() {
		add_action( 'admin_init', array( $this, '_rich_editing_false' ) );
	}
		
	/**
	 * Apply hook for enabling rich editor.
	 */
	public function _rich_editing_true() {
		global $current_user;

		if ( ! user_can_richedit() ) {
			update_user_option( $current_user->ID, 'rich_editing', 'true', true );
		}
		add_filter( 'user_can_richedit' , '__return_true', 50 );
	}

		/**
	 * Apply hook for disabling rich editor.
	 */
	public function _rich_editing_false() {
		global $current_user;

		if ( user_can_richedit() ) {
			update_user_option( $current_user->ID, 'rich_editing', 'false', true );
		}
		add_filter( 'user_can_richedit' , '__return_false', 50 );
	}

	/**
	 * Enable Gutenberg.
	 */
	public function enable_gutenberg() {
		if ( $GLOBALS['wp_version'] > '5.0' ) {
			add_filter('use_block_editor_for_post', '__return_true', 5);
		}
	}

	/**
	 * Disable Gutenberg.
	 */
	public function disable_gutenberg() {
		if ( $GLOBALS['wp_version'] > '5.0' ) {
			add_filter('use_block_editor_for_post', '__return_false', 5);
		}
	}
}
