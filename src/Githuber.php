<?php
/**
 * Class Githuber
 *
 * @author Terry Lin
 * @link https://terryl.in/
 *
 * @package Githuber
 * @since 1.0.0
 * @version 1.13.1
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
		add_action( 'wp_enqueue_scripts', array( $this, 'front_enqueue_styles' ) );
		add_action( 'wp_print_footer_scripts', array( $this, 'front_print_footer_scripts' ) );

		if ( ! isset( $_SERVER['HTTP_HOST'] ) || ! isset( $_SERVER['REQUEST_URI'] ) ) {
			$_SERVER['HTTP_HOST']   = '127.0.0.1';
			$_SERVER['REQUEST_URI'] = '/';
		}

		$this->current_url = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

		// Only use it in DEBUG mode.
		githuber_logger( 'Hello, Githuber MD.', array(
			'wp_version'  => $GLOBALS['wp_version'],
			'php_version' => phpversion(),
		) );

		// If in Admin Panel and WordPress > 5.0, load Class editor and disable Gutenberg editor.
		if ( $GLOBALS['wp_version'] > '5.0' && is_admin() ) {
			add_filter('use_block_editor_for_post', '__return_false', 5);
		}

		// Load TOC widget. // 
		if ( 'yes' == githuber_get_option( 'support_toc', 'githuber_modules' ) ) {
			if ( 'yes' == githuber_get_option( 'is_toc_widget', 'githuber_modules' ) ) {
				add_action( 'widgets_init', function() {
					register_widget( 'Githuber_Widget_Toc' );
				} );
			}
		}

		// Load core functions when `wp_loaded` is ready.
		add_action( 'wp_loaded', array( $this, 'init' ) );

		// Only use it in DEBUG mode.
		githuber_logger( 'Hook: wp_loaded', array( 'url' => $this->current_url ) );
	}

	/**
	 * Initialize everything the Githuber plugin needs.
	 * 
	 * @return void
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

			if ( 'yes' === githuber_get_option( 'editor_spell_check', 'githuber_markdown' ) ) {
				$spellCheck = new Controller\SpellCheck();
				$spellCheck->init();
			}

			if ( 'yes' === githuber_get_option( 'keyword_suggestion_tool', 'githuber_markdown' ) ) {
				$keywordSuggestion = new Controller\KeywordSuggestion();
				$keywordSuggestion->init();
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

		// Module Name: Highlight
		if ( 'yes' === githuber_get_option( 'support_highlight', 'githuber_modules' ) ) {
			$module_highlight = new Module\Highlight();
			$module_highlight->init();
		}

		// Module Name: MathJax
		if ( 'yes' === githuber_get_option( 'support_mathjax', 'githuber_modules' ) ) {
			$module_mathjax = new Module\MathJax();
			$module_mathjax->init();
		}

		// Replace `&amp;` to `&` in URLs in post content.
		if ( 'yes' == githuber_get_option( 'support_toc', 'githuber_modules' ) ) {
			$module_toc = new Module\Toc();
			$module_toc->init();
		}

		// Copy to Clipboard
		if ( 'yes' === githuber_get_option( 'support_clipboard', 'githuber_modules' ) ) {
			$module_clipboard = new Module\Clipboard();
			$module_clipboard->init();
		}

		// Emojify
		if ( 'yes' === githuber_get_option( 'support_emojify', 'githuber_modules' ) ) {
			$module_emojify = new Module\Emojify();
			$module_emojify->init();
		}

		/**
		 * Let's start setting user's perferences...
		 */

		if ( 'yes' !== githuber_get_option( 'smart_quotes', 'githuber_preferences' ) ) {
			remove_filter( 'the_content', 'wptexturize' );
		}

		// Replace `&amp;` to `&` in URLs in post content.
		if ( 'yes' === githuber_get_option( 'restore_ampersands', 'githuber_preferences' ) ) {
			add_filter( 'the_content', function( $string ) {
				return preg_replace_callback( '|<a\b([^>]*)>(.*?)</a>|', function( $matches ) {
					return '<a' . str_replace( '&amp;', '&', $matches[1] ) . '>' . $matches[2] . '</a>';
				}, $string );
			}, 10, 1 );
		}
	}

	/**
	 * Load plugin textdomain.
	 * 
	 * @return void
	 */
	public function load_textdomain() {
		load_plugin_textdomain( GITHUBER_PLUGIN_TEXT_DOMAIN, false, GITHUBER_PLUGIN_LANGUAGE_PACK ); 
	}

	/**
	 * Register CSS style files for frontend use.
	 * 
	 * @return void
	 */
	public function front_enqueue_styles() {
		wp_register_style( 'md-style', false );
		wp_enqueue_style( 'md-style' );
		wp_add_inline_style( 'md-style', $this->get_front_enqueue_styles() );
	}

	/**
	 * Get CSS code.
	 *
	 * @return string
	 */
	public function get_front_enqueue_styles() {
		$custom_css = githuber_load_view( 'assets/css' );
		return preg_replace( '/\s+/', ' ', $custom_css );
	}

	/**
	 * Print Javascript plaintext in page footer.
	 * 
	 * @return void
	 */
	public function front_print_footer_scripts() {
		$script = githuber_load_view( 'assets/js' );
		$script = preg_replace( '/\s+/', ' ', $script );
		echo $script;
	}
}

