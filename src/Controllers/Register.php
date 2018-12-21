<?php
/**
 * Class Register
 *
 * @author Terry Lin
 * @link https://terryl.in/
 *
 * @package Githuber
 * @since 1.0.0
 * @version 1.0.0
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

		$this->register_hooks();
		$this->add_post_types();
		$this->add_walker();
		$this->add_widgets();
	}

	/**
	 * Register CSS style files.
	 */
	public function admin_enqueue_styles( $hook_suffix ) {

	}

	/**
	 * Register JS files.
	 */
	public function admin_enqueue_scripts( $hook_suffix ) {

	}
	
	/**
	 * Activate Githuber plugin.
	 */
	public function activate_plugin() {
		global $current_user;
		// Turn off Rich-text editor.
		update_user_option( $current_user->ID, 'rich_editing', 'false', true );
	}

	/**
	 * Deactivate Githuber plugin.
	 */
	public function deactivate_plugin() {
		global $current_user;
		// Turn on Rich-text editor.
		update_user_option( $current_user->ID, 'rich_editing', 'true', true );
		delete_user_option( $current_user->ID, 'dismissed_wp_pointers', true );
	}

	/**
	 * Initialize Githuber widgets.
	 */
	public function add_widgets() {
		if ( 'yes' === githuber_get_option( 'githuber_theme_bootstrap_toc', 'githuber_options' ) ) {
			add_action( 'widgets_init', array( $this, 'register_widgets' ) );
		}
	}

	/**
	 * Register post typees.
	 */
	public function add_post_types() {
		if ( 'yes' === githuber_get_option( 'githuber_theme_repository', 'githuber_options' ) ) {
			new \Githuber_Post_Type_Repository();
		}
	}

	/**
	 * Register Walker for Bootstrap 4 header menu.
	 */
	public function add_walker() {
		if ( 'yes' === githuber_get_option( 'githuber_theme_bootstrap_menu', 'githuber_options' ) ) {
			new \Githuber_Walker();
		}
	}

	/**
	 * Register hooks.
	 */
	public function register_hooks() {
		register_activation_hook( $this->githuber_plugin_path, array( $this , 'activate_plugin' ) );
		register_deactivation_hook( $this->githuber_plugin_path, array( $this , 'deactivate_plugin' ) );
	}

	/**
	 * Register Githuber widgets. (Triggered by $this->add_widgets).
	 */
	public function register_widgets() {
		register_widget( 'Githuber_Widget_Toc' );
	}
}