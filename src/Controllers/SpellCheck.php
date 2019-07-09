<?php
/**
 * Class SpellCheck
 *
 * @author Terry Lin
 * @link https://terryl.in/
 *
 * @package Githuber
 * @since 1.10.2
 * @version 1.10.2
 * 
 * Hunspell dictories source:
 * https://spellcheck-dictionaries.github.io/
 * 
 */

namespace Githuber\Controller;

class SpellCheck extends ControllerAbstract {

	/**
	 * We use a JavaScript library that is called `codemirror-spell-checker`, and this is its version number.
	 *
	 * @link https://github.com/sparksuite/codemirror-spell-checker
	 *
	 * @var string
	 */
	public $spellcheck_varsion = '1.1.2';

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
    }
    
	/**
	 * Initalize to WP `admin_init` hook.
	 */
	public function admin_init() {
        add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
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
        wp_enqueue_script( 'githuber-md-typo-check', $this->githuber_plugin_url . 'assets/vendor/editor.md/lib/codemirror/addon/spellcheck/typo.js', array( 'editormd' ), $this->spellcheck_varsion, true );
        wp_enqueue_script( 'githuber-md-spell-check', $this->githuber_plugin_url . 'assets/vendor/editor.md/lib/codemirror/addon/spellcheck/spell-checker.js', array( 'editormd' ), $this->spellcheck_varsion, true );
	}
}