<?php

/**
 * Class ControllerAbstract
 * 
 * Controllers are specifically used for admin (backend) use.
 *
 * @author Terry Lin
 * @link https://terryl.in/
 *
 * @package Githuber
 * @since 1.0.0
 * @version 1.0.0
 */

namespace Githuber\Controller;

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
	public $githuber_plugin_url;

	/**
	 * The plugin directory.
	 *
	 * @var string
	 */
	public $githuber_plugin_dir;

	/**
	 * The plugin loader's path.
	 *
	 * @var string
	 */
	public $githuber_plugin_path;

	/**
	 * Plugin's name.
	 *
	 * @var string
	 */
	public $githuber_plugin_name;

	/**
	 * Constructer.
	 * 
	 * @return void
	 */
	public function __construct() {
		/**
		 * Basic plugin information. Mapping from the Constant in the plugin loader script.
		 */
		$this->githuber_plugin_name = GITHUBER_PLUGIN_NAME;
		$this->githuber_plugin_url  = GITHUBER_PLUGIN_URL;
		$this->githuber_plugin_dir  = GITHUBER_PLUGIN_DIR;
		$this->githuber_plugin_path = GITHUBER_PLUGIN_PATH;
		$this->version              = GITHUBER_PLUGIN_VERSION;
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
