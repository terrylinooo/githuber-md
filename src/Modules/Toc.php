<?php
/**
 * Module Name: Table of Content
 * Module Description: Display table of content in article section.
 *
 * @author Terry Lin
 * @link https://terryl.in/
 *
 * @package Githuber
 * @since 1.0.0
 * @version 1.0.0
 */

namespace Githuber\Module;

class Toc extends ModuleAbstract {

	/**
	 * Constructer.
	 */
	public function __construct() {
		parent::__construct();
	}

	public function init() {
		// No ideas about Toc right now.
	}
 
	/**
	 * Register CSS style files for frontend use.
	 * 
	 * @return void
	 */
	public function front_enqueue_styles() {

	}

	/**
	 * Register JS files for frontend use.
	 * 
	 * @return void
	 */
	public function front_enqueue_scripts() {

	}

	/**
	 * Print Javascript plaintext in page footer.
	 */
	public function front_print_footer_scripts() {
		$script = '

		';
		echo $script;
	}
}
