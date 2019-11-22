<?php
/**
 * Class Githuber
 *
 * @author Terry Lin
 * @link https://terryl.in/
 *
 * @package Githuber
 * @since 1.0.0
 * @version 1.12.0
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

		/**
		 * Let's start setting user's perferences...
		 */
		add_action( 'wp_print_footer_scripts', array( $this, 'front_print_footer_scripts' ) );

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

	public function get_front_enqueue_styles() {

		$custom_css = '';

		if ( 'yes' === githuber_get_option( 'support_task_list', 'githuber_extensions' ) ) {
	
			$custom_css .= '
				.gfm-task-list {
					border: 1px solid transparent;
					list-style-type: none;
				}
				.gfm-task-list input {
					margin-right: 10px !important;
				}
			';
		}

		if ( 'yes' === githuber_get_option( 'support_katex', 'githuber_modules' ) ) {
		
			$custom_css .= '
				.katex-container {
					margin: 25px !important;
					text-align: center;
				}
				.katex-container.katex-inline {
					display: inline-block !important;
					background: none !important;
					margin: 0 !important;
					padding: 0 !important;
				}
				pre .katex-container {
					font-size: 1.4em !important;
				}
				.katex-inline {
					background: none !important;
					margin: 0 3px;
				}
			';
		}

		if ( '_blank' === githuber_get_option( 'post_link_target_attribute', 'githuber_preferences' ) ) {
		  
			$custom_css .= '
				code.kb-btn {
					display: inline-block;
					color: #666;
					font: bold 9pt arial;
					text-decoration: none;
					text-align: center;
					padding: 2px 5px;
					margin: 0 5px;
					background: #eff0f2;
					-moz-border-radius: 4px;
					border-radius: 4px;
					border-top: 1px solid #f5f5f5;
					-webkit-box-shadow: inset 0 0 20px #e8e8e8, 0 1px 0 #c3c3c3, 0 1px 0 #c9c9c9, 0 1px 2px #333;
					-moz-box-shadow: inset 0 0 20px #e8e8e8, 0 1px 0 #c3c3c3, 0 1px 0 #c9c9c9, 0 1px 2px #333;
					box-shadow: inset 0 0 20px #e8e8e8, 0 1px 0 #c3c3c3, 0 1px 0 #c9c9c9, 0 1px 2px #333;
					text-shadow: 0px 1px 0px #f5f5f5;
				}
			';
		}

		if ( 'yes' === githuber_get_option( 'support_clipboard', 'githuber_modules' ) ) {

			$svg = "data:image/svg+xml,%3Csvg version='1.1' xmlns='http://www.w3.org/2000/svg' xmlns:xlink='http://www.w3.org/1999/xlink' x='0px' y='0px' width='16px' height='16px' viewBox='888 888 16 16' enable-background='new 888 888 16 16' xml:space='preserve'%3E %3Cpath fill='%23333333' d='M903.143,891.429c0.238,0,0.44,0.083,0.607,0.25c0.167,0.167,0.25,0.369,0.25,0.607v10.857 c0,0.238-0.083,0.44-0.25,0.607s-0.369,0.25-0.607,0.25h-8.571c-0.238,0-0.44-0.083-0.607-0.25s-0.25-0.369-0.25-0.607v-2.571 h-4.857c-0.238,0-0.44-0.083-0.607-0.25s-0.25-0.369-0.25-0.607v-6c0-0.238,0.06-0.5,0.179-0.786s0.262-0.512,0.428-0.679 l3.643-3.643c0.167-0.167,0.393-0.309,0.679-0.428s0.547-0.179,0.786-0.179h3.714c0.238,0,0.44,0.083,0.607,0.25 c0.166,0.167,0.25,0.369,0.25,0.607v2.929c0.404-0.238,0.785-0.357,1.143-0.357H903.143z M898.286,893.331l-2.67,2.669h2.67V893.331 z M892.571,889.902l-2.669,2.669h2.669V889.902z M894.321,895.679l2.821-2.822v-3.714h-3.428v3.714c0,0.238-0.083,0.441-0.25,0.607 s-0.369,0.25-0.607,0.25h-3.714v5.714h4.571v-2.286c0-0.238,0.06-0.5,0.179-0.786C894.012,896.071,894.155,895.845,894.321,895.679z M902.857,902.857v-10.286h-3.429v3.714c0,0.238-0.083,0.441-0.25,0.607c-0.167,0.167-0.369,0.25-0.607,0.25h-3.714v5.715H902.857z' /%3E %3C/svg%3E";
			$svg = addslashes($svg);

			$custom_css .= '
				.copy-button {
					cursor: pointer;
					border: 0;
					font-size: 12px;
					text-transform: uppercase;
					font-weight: 500;
					padding: 3px 6px 3px 6px;
					background-color: rgba(255, 255, 255, 0.6);
					position: absolute;
					overflow: hidden;
					top: 5px;
					right: 5px;
					border-radius: 3px;
				}
				.copy-button:before {
					content: "";
					display: inline-block;
					width: 16px;
					height: 16px;
					margin-right: 3px;
					background-size: contain;
					background-image: url("' . $svg . '");
					background-repeat: no-repeat;
					position: relative;
					top: 3px;
				}
				pre {
					position: relative;
				}
				pre:hover .copy-button {
					background-color: rgba(255, 255, 255, 0.9);
				}
			';
		}

		if ( 'yes' == githuber_get_option( 'support_toc', 'githuber_modules' ) ) {
			$custom_css .= '
				.md-widget-toc {
					padding: 15px;
				}
				.md-widget-toc a {
					color: #333333;
				}
				.post-toc-header {
					font-weight: 600;
					margin-bottom: 10px;
				}
				.md-post-toc {
					font-size: 0.9em;
				}
				.post h2 {
					overflow: hidden;
				}
				.post-toc-block {
					margin: 0 10px 20px 10px;
					overflow: hidden;
				}
				.post-toc-block.with-border {
					border: 1px #dddddd solid;
					padding: 10px;
				}
				.post-toc-block.float-right {
					max-width: 320px;
					float: right;
				}
				.post-toc-block.float-left {
					max-width: 320px;
					float: left;
				}
				.md-widget-toc ul, .md-widget-toc ol, .md-post-toc ul, .md-post-toc ol {
					padding-left: 15px;
					margin: 0;
				}
				.md-widget-toc ul ul, .md-widget-toc ul ol, .md-widget-toc ol ul, .md-widget-toc ol ol, .md-post-toc ul ul, .md-post-toc ul ol, .md-post-toc ol ul, .md-post-toc ol ol {
					padding-left: 2em;
				}
				.md-widget-toc ul ol, .md-post-toc ul ol {
					list-style-type: lower-roman;
				}
				.md-widget-toc ul ul ol, .md-widget-toc ul ol ol, .md-post-toc ul ul ol, .md-post-toc ul ol ol {
					list-style-type: lower-alpha;
				}
				.md-widget-toc ol ul, .md-widget-toc ol ol, .md-post-toc ol ul, .md-post-toc ol ol {
					padding-left: 2em;
				}
				.md-widget-toc ol ol, .md-post-toc ol ol {
					list-style-type: lower-roman;
				}
				.md-widget-toc ol ul ol, .md-widget-toc ol ol ol, .md-post-toc ol ul ol, .md-post-toc ol ol ol {
					list-style-type: lower-alpha;
				}
			';
		}

		return preg_replace( '/\s+/', ' ', $custom_css );
	}

	/**
	 * Print Javascript plaintext in page footer.
	 */
	public function front_print_footer_scripts() {
		$script = '';

		if ( '_blank' === githuber_get_option( 'post_link_target_attribute', 'githuber_preferences' ) ) {
			$script = '
				<script id="preference-link-target">
					(function($) {
						$(function() {
							$(".post a").each(function() {
								var link_href = $(this).attr("href");
								if (link_href.indexOf("#") == -1) {
									$(this).attr("target", "_blank");
								}
							});
						});
					})(jQuery);
				</script>
			';

			return preg_replace( '/\s+/', ' ', $script );
		}

		return $script;
	}
}

