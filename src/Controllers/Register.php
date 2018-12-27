<?php
/**
 * Class Register
 *
 * @author Terry Lin
 * @link https://terryl.in/
 *
 * @package Githuber
 * @since 1.0.0
 * @version 1.3.0
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

		if ( 'yes' === githuber_get_option( 'githuber_theme_shortcode_social_icons', 'githuber_options' ) ) {
			githuber_load_utility('functions');
			githuber_load_utility('shortcode');
		}

		if ( 'yes' === githuber_get_option( 'githuber_theme_adjustment_head_output', 'githuber_options' ) ) {
			githuber_load_utility('theme-adjustment');
		}

		if ( 'yes' === githuber_get_option( 'disable_revision', 'githuber_markdown' ) ) {
			add_action( 'init', array( $this , 'remove_revisions' ), 10 );
		}

		if ( 'yes' === githuber_get_option( 'disable_autosave', 'githuber_markdown' ) ) {
			add_action( 'wp_print_scripts', array( $this , 'remove_autosave' ), 10 );
		}
	}

	/**
	 * Remove revisions.
	 */
	public function remove_revisions() {
		remove_post_type_support( 'post', 'revisions' );
		remove_post_type_support( 'page', 'revisions' );
		remove_post_type_support( 'repository', 'revisions' );
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
		//update_user_option( $current_user->ID, 'rich_editing', 'false', true );

		$githuber_markdown = array(
			'enable_markdown_for'   => array( 'posting' => 'posting' ),
			'disable_revision'      => 'no',
			'disable_autosave'      => 'yes',
			'html_to_markdown'      => 'yes',
			'editor_live_preview'   => 'yes',
			'editor_sync_scrolling' => 'yes',
			'editor_html_decode'    => 'yes',
		);

		// Add default setting. Only execute this action at the first time activation.
		if ( false === get_option( 'githuber_markdown' ) ) {
			update_option( 'githuber_markdown', $githuber_markdown, '', 'yes' );
		}
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
