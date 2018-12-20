<?php

/**
 * Class ModuleAbstract
 * 
 * Modules are specifically used for frontend.
 *
 * @author Terry Lin
 * @link https://terryl.in/
 *
 * @package Githuber
 * @since 1.0.0
 * @version 1.0.0
 */

namespace Githuber\Module;

abstract class ModuleAbstract {

	/**
	 * The plugin url.
	 *
	 * @var string
	 */
	public $githuber_plugin_url;

	/**
	 * Post Id.
	 *
	 * @var integer
	 */
	public static $front_post_id = 0;

	/**
	 * Constructer.
	 * 
	 * @return void
	 */
	public function __construct() {
		/**
		 * Basic plugin information. Mapping from the Constant in the plugin loader script.
		 */
		$this->githuber_plugin_url  = GITHUBER_PLUGIN_URL;
	}

	/**
	 * Initialize.
	 *
	 * @return void
	 */
	abstract public function init();
	
	/**
	 * Register CSS style files for frontend use.
	 *
	 * @return void
	 */
	abstract public function front_enqueue_styles();

	/**
	 * Register JS files for frontend use.
	 *
	 * @return void
	 */
	abstract public function front_enqueue_scripts();

	/**
	 * Print Javascript plaintext in page footer.
	 *
	 * @return void
	 */
	abstract public function front_print_footer_scripts();

	/**
	 * Check if this module should be loaded.
	 */
	public function is_module_should_be_loaded( $meta_name ) {
		if ( empty( self::$front_post_id ) ) {
			// Get current post ID if an user is viewing a post.
			self::$front_post_id = githuber_get_current_post_id();
		} 
		
		if ( ! empty( self::$front_post_id ) ) {
			$post_meta = get_metadata( 'post', self::$front_post_id, $meta_name );
			if ( empty( $post_meta[0] ) ) {
				return false;
			}
			return (bool) $post_meta[0];
		}
		return false;
	}
}
