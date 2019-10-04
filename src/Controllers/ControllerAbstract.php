<?php

/**
 * Class ControllerAbstract
 * 
 * Controllers are specifically used for admin (backend) use.
 *
 * @author Terry Lin
 * @link https://terryl.in/
 *
 * @package Future
 * @since 1.0.0
 * @version 1.0.0
 */

namespace Future\Controller;

abstract class ControllerAbstract {

	/**
	 * Version.
	 *
	 * @var string
	 */
	public $version;

	/**
	 * Text domain for transation.
	 *
	 * @var string
	 */
	public $text_domain;

	/**
	 * The plugin url.
	 *
	 * @var string
	 */
	public $future_plugin_url;

	/**
	 * The plugin directory.
	 *
	 * @var string
	 */
	public $future_plugin_dir;

	/**
	 * The plugin loader's path.
	 *
	 * @var string
	 */
	public $future_plugin_path;

	/**
	 * Plugin's name.
	 *
	 * @var string
	 */
	public $future_plugin_name;

	/**
	 * Constructer.
	 * 
	 * @return void
	 */
	public function __construct() {
		/**
		 * Basic plugin information. Mapping from the Constant in the plugin loader script.
		 */
		$this->future_plugin_name = FUTURE_PLUGIN_NAME;
		$this->future_plugin_url  = FUTURE_PLUGIN_URL;
		$this->future_plugin_dir  = FUTURE_PLUGIN_DIR;
		$this->future_plugin_path = FUTURE_PLUGIN_PATH;
		$this->version              = FUTURE_PLUGIN_VERSION;
	}

	/**
	 * Initialize.
	 *
	 * @return void
	 */
	abstract public function init();
	
	/**
	 * Register CSS style files.
	 * 
	 * @param string Hook suffix string.
	 * @return void
	 */
	abstract public function admin_enqueue_styles( $hook_suffix );

	/**
	 * Register JS files.
	 * 
	 * @param string Hook suffix string.
	 * @return void
	 */
	abstract public function admin_enqueue_scripts( $hook_suffix );
}
