<?php
/**
 * Module Name: Emoji
 * Module Description: Emoji are ideograms and smileys used in electronic messages and web pages.
 *
 * @author Terry Lin
 * @link https://terryl.in/
 *
 * @package Githuber
 * @since 1.0.0
 * @version 1.0.0
 */

namespace Githuber\Module;

class Emoji extends ModuleAbstract {

	/**
	 * Constructer.
	 */
	public function __construct() {
		parent::__construct();
	}

	public function init() {
		// No ideas about Emoji right now.
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
