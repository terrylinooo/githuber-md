<?php
/**
 * Class Register
 *
 * @author Terry Lin
 * @link https://terryl.in/
 *
 * @package Githuber
 * @since 1.0.0
 * @version 1.7.0
 */

namespace Githuber\Controller;

class Register extends ControllerAbstract {

	/**
	 * Constructer.
	 */
	public function __construct() {
		parent::__construct();
	}

	/**
	 * Initialize.
	 */
	public function init() {

		add_action( 'admin_init', array( $this, 'admin_init' ) );

		if ( 'yes' === githuber_get_option( 'disable_revision', 'githuber_markdown' ) ) {
			add_action( 'admin_init', array( $this , 'remove_revisions' ), 999 );
		}

		if ( 'yes' === githuber_get_option( 'disable_autosave', 'githuber_markdown' ) ) {
			add_action( 'wp_print_scripts', array( $this , 'remove_autosave' ), 10 );
		}
	}

	/**
	 * Initalize to WP `admin_init` hook.
	 */
	function admin_init() {
		global $current_user;

		if ( user_can_richedit() ) {
			update_user_option( $current_user->ID, 'rich_editing', 'false', true );
		}
		add_filter( 'user_can_richedit' , '__return_false', 50 );

		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_styles' ) );
	}

	/**
	 * Remove revisions.
	 */
	public function remove_revisions() {
		foreach ( get_post_types() as $post_type ) {
			remove_post_type_support( $post_type, 'revisions' );
		}
	}

	/**
	 * Remove auto-save function.
	 */
	function remove_autosave() {
		wp_deregister_script('autosave');
	}

	/**
	 * Register CSS style files.
	 */
	public function admin_enqueue_styles( $hook_suffix ) {
		wp_enqueue_style( 'custom_wp_admin_css', $this->githuber_plugin_url . 'assets/css/admin-style.css', array(), $this->version, 'all' );
	}

	/**
	 * Register JS files.
	 */
	public function admin_enqueue_scripts( $hook_suffix ) {

	}

			
}
