<?php
/**
 * Class Githuber
 *
 * @author Terry Lin
 * @link https://terryl.in/
 *
 * @package Githuber
 * @since 1.0.0
 * @version 1.6.0
 */

use Githuber\Controller as Controller;
use Githuber\Module as Module;
use Githuber\Controller\Monolog as Monolog;

class Githuber {

	public $current_url;

	/**
	 * Constructer.
	 */
	public function __construct() {
		add_action( 'init', array( $this, 'load_textdomain' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'front_enqueue_styles' ), 998 );

		$this->current_url = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

		Monolog::logger( 'Hello, Githuber MD.', array(
			'wp_version'  => $GLOBALS['wp_version'],
			'php_version' => phpversion(),
		) );

		// If in Admin Panel and WordPress > 5.0, load Class editor and disable Gutenberg editor.
		if ( $GLOBALS['wp_version'] > '5.0' && is_admin() ) {
			add_filter('use_block_editor_for_post', '__return_false', 5);
			//githuber_load_utility('classic-editor');
		}

		$register = new Controller\Register();
		$register->init();

		add_action( 'wp_loaded', array( $this, 'init' ) );

		Monolog::logger( 'Hook: wp_loaded', array( 
			'url' => $this->current_url,
		) );
	}
	
	/**
	 * Initialize everything the Githuber plugin needs.
	 */
	public function init() {

		// Only load controllers in backend.
		if ( is_admin() ) {
			$setting = new Controller\Setting();
			$setting->init();
	
			if ( 'yes' === githuber_get_option( 'support_image_paste', 'githuber_markdown' ) ) {
				$image_paste = new Controller\ImagePaste();
				$image_paste->init();
			}

			if ( 'yes' === githuber_get_option( 'editor_html_decode', 'githuber_markdown' ) ) {
				$customMediaLibrary = new Controller\CustomMediaLibrary();
				$customMediaLibrary->init();
			}

			$markdown = new Controller\Markdown();
			$markdown->init();
		}

		/**
		 * Let's start loading frontend modules.
		 */ 

		// Module Name: FlowChart
		if ( 'yes' === githuber_get_option( 'support_flowchart', 'githuber_markdown' ) ) {
			$module_flowchart = new Module\FlowChart();
			$module_flowchart->init();
		}

		// Module Name: KaTeX
		if ( 'yes' === githuber_get_option( 'support_katex', 'githuber_markdown' ) ) {
			$module_katex = new Module\KaTeX();
			$module_katex->init();
		}

		// Module Name: Sequence Diagram
		if ( 'yes' === githuber_get_option( 'support_sequence_diagram', 'githuber_markdown' ) ) {
			$module_sequence = new Module\SequenceDiagram();
			$module_sequence->init();
		}

		// Module Name: Mermaid
		if ( 'yes' === githuber_get_option( 'support_mermaid', 'githuber_markdown' ) ) {
			$module_mermaid = new Module\Mermaid();
			$module_mermaid->init();
		}

		// Module Name: Prism
		if ( 'yes' === githuber_get_option( 'support_prism', 'githuber_markdown' ) ) {
			$module_prism = new Module\Prism();
			$module_prism->init();
		}
	}

	/**
	 * Register CSS style files for frontend use.
	 * 
	 * @return void
	 */
	public function front_enqueue_styles() {
		wp_enqueue_style( 'githuber-md-css', GITHUBER_PLUGIN_URL . 'assets/css/githuber-md.css', array(), GITHUBER_PLUGIN_VERSION, 'all' );
	}

	/**
	 * Load plugin textdomain.
	 */
	public function load_textdomain() {
		load_plugin_textdomain( GITHUBER_PLUGIN_TEXT_DOMAIN, false, GITHUBER_PLUGIN_LANGUAGE_PACK ); 
	}
}

