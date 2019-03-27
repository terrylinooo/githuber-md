<?php
/**
 * Class Githuber
 *
 * @author Terry Lin
 * @link https://terryl.in/
 *
 * @package Githuber
 * @since 1.0.0
 * @version 1.7.1
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
		add_action( 'wp_enqueue_scripts', array( $this, 'front_enqueue_scripts' ), 999 );

		$this->current_url = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

		// Only use it in DEBUG mode.
		Monolog::logger( 'Hello, Githuber MD.', array(
			'wp_version'  => $GLOBALS['wp_version'],
			'php_version' => phpversion(),
		) );

		// If in Admin Panel and WordPress > 5.0, load Class editor and disable Gutenberg editor.
		if ( $GLOBALS['wp_version'] > '5.0' && is_admin() ) {
			add_filter('use_block_editor_for_post', '__return_false', 5);
		}

		// Load core functions when `wp_loaded` is ready.
		add_action( 'wp_loaded', array( $this, 'init' ) );

		// Only use it in DEBUG mode.
		Monolog::logger( 'Hook: wp_loaded', array( 'url' => $this->current_url ) );
	}
	
	/**
	 * Initialize everything the Githuber plugin needs.
	 */
	public function init() {

		// Only load controllers in backend.
		if ( is_admin() ) {

			$register = new Controller\Register();
			$register->init();

			$setting = new Controller\Setting();
			$setting->init();
	
			if ( 'yes' === githuber_get_option( 'support_image_paste', 'githuber_modules' ) ) {
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
		if ( 'yes' === githuber_get_option( 'support_flowchart', 'githuber_modules' ) ) {
			$module_flowchart = new Module\FlowChart();
			$module_flowchart->init();
		}

		// Module Name: KaTeX
		if ( 'yes' === githuber_get_option( 'support_katex', 'githuber_modules' ) ) {
			$module_katex = new Module\KaTeX();
			$module_katex->init();
		}

		// Module Name: Sequence Diagram
		if ( 'yes' === githuber_get_option( 'support_sequence_diagram', 'githuber_modules' ) ) {
			$module_sequence = new Module\SequenceDiagram();
			$module_sequence->init();
		}

		// Module Name: Mermaid
		if ( 'yes' === githuber_get_option( 'support_mermaid', 'githuber_modules' ) ) {
			$module_mermaid = new Module\Mermaid();
			$module_mermaid->init();
		}

		// Module Name: Prism
		if ( 'yes' === githuber_get_option( 'support_prism', 'githuber_modules' ) ) {
			$module_prism = new Module\Prism();
			$module_prism->init();
		}

		/**
		 * Let's start setting user's perferences...
		 */
		add_action( 'wp_print_footer_scripts', array( $this, 'front_print_footer_scripts' ) );

		if ( 'yes' !== githuber_get_option( 'smart_quotes', 'githuber_preferences' ) ) {
			remove_filter( 'the_content', 'wptexturize' );
		}
	}

	/**
	 * Register CSS style files for frontend use.
	 */
	public function front_enqueue_styles() {
		wp_enqueue_style( 'githuber-md-css', GITHUBER_PLUGIN_URL . 'assets/css/githuber-md.css', array(), GITHUBER_PLUGIN_VERSION, 'all' );
	}

	/**
	 * Register JS files for frontend use.
	 */
	public function front_enqueue_scripts() {

		if ( '_blank' === githuber_get_option( 'post_link_target_attribute', 'githuber_preferences' ) ) {
			$frontend_settings['link_opening_method'] = '_blank';
		} else {
			$frontend_settings['link_opening_method'] = '_top';
		}

		// Register JS variables for the Editormd library uses.
		wp_enqueue_script( 'githuber-md-js', GITHUBER_PLUGIN_URL . 'assets/js/githuber-md-frontend.js', array( 'jquery' ), GITHUBER_PLUGIN_VERSION, 'all' );
		wp_localize_script( 'githuber-md-js', 'md_frontend_settings', $frontend_settings );
	}
	

	/**
	 * Load plugin textdomain.
	 */
	public function load_textdomain() {
		load_plugin_textdomain( GITHUBER_PLUGIN_TEXT_DOMAIN, false, GITHUBER_PLUGIN_LANGUAGE_PACK ); 
	}

	/**
	 * Print Javascript plaintext in page footer.
	 */
	public function front_print_footer_scripts() {

	}
}

