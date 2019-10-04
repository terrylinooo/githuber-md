<?php
/**
 * Class Register
 *
 * @author Terry Lin
 * @link https://terryl.in/
 *
 * @package Future
 * @since 1.0.0
 * @version 1.7.0
 */

namespace Future\Controller;

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

		if ( 'yes' === future_get_option( 'disable_revision', 'future_markdown' ) ) {
			add_action( 'admin_init', array( $this , 'remove_revisions' ), 999 );
		}

		if ( 'yes' === future_get_option( 'disable_autosave', 'future_markdown' ) ) {
			add_action( 'wp_print_scripts', array( $this , 'remove_autosave' ), 10 );
		}

		$this->version_migration();
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
	 * Migration.
	 */
	public function version_migration() {

		$migration_v162 = get_option( 'future_migration_v162');

		if ( empty( $migration_v162 ) ) {

			$future_modules = array(
				'support_prism'            => future_get_option( 'support_prism', 'future_markdown' ),
				'support_katex'            => future_get_option( 'support_katex', 'future_markdown' ),
				'support_flowchart'        => future_get_option( 'support_flowchart', 'future_markdown' ),
				'support_sequence_diagram' => future_get_option( 'support_sequence_diagram', 'future_markdown' ),
				'support_mermaid'          => future_get_option( 'support_mermaid', 'future_markdown' ),
				'support_image_paste'      => future_get_option( 'support_image_paste', 'future_markdown' ),
			);

			$future_extensions = array(
				'support_task_list' => future_get_option( 'support_task_list', 'future_markdown' ),
			);

			update_option( 'future_modules', $future_modules, '', 'yes' );
			update_option( 'future_extensions', $future_extensions, '', 'yes' );
			update_option( 'future_migration_v162', 'yes', '', 'yes' );
		}
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
		wp_enqueue_style( 'custom_wp_admin_css', $this->future_plugin_url . 'assets/css/admin-style.css', array(), $this->version, 'all' );
	}

	/**
	 * Register JS files.
	 */
	public function admin_enqueue_scripts( $hook_suffix ) {

	}

			
}
