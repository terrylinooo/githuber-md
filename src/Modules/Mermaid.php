<?php
/**
 * Module Name: Mermaid
 * Module Description: Generation of diagrams and flowcharts from text in a similar manner as markdown.
 *
 * @author Terry Lin
 * @link https://terryl.in/
 *
 * @package Githuber
 * @since 1.4.0
 * @version 1.4.0
 */

namespace Githuber\Module;

class Mermaid extends ModuleAbstract {

	/**
	 * The version of flowchart.js we are using.
	 *
	 * @var string
	 */
    public $mermaid_version = '8.9.0';

	/**
	 * Constructer.
	 */
	public function __construct() {
		parent::__construct();
	}

	/**
	 * Constants.
	 */
	const MD_POST_META_MERMAID = '_is_githuber_mermaid';

	/**
	 * Initialize.
	 *
	 * @return void
	 */
	public function init() {
		add_action( 'wp_enqueue_scripts', array( $this, 'front_enqueue_scripts' ) );
		add_action( 'wp_print_footer_scripts', array( $this, 'front_print_footer_scripts' ) );
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
		if ( $this->is_module_should_be_loaded( self::MD_POST_META_MERMAID ) ) {
			$option = githuber_get_option( 'mermaid_src', 'githuber_modules' );

			switch ( $option ) {
                case 'cloudflare':
                    $script_url = 'https://cdnjs.cloudflare.com/ajax/libs/mermaid/' . $this->mermaid_version . '/mermaid.min.js';
                    break;

				case 'jsdelivr':
                    $script_url = 'https://cdn.jsdelivr.net/npm/mermaid@' . $this->mermaid_version . '/dist/mermaid.min.js';
					break;

				default:
					$script_url = $this->githuber_plugin_url . 'assets/vendor/mermaid/mermaid.min.js';
					break;
			} 

			wp_enqueue_script( 'mermaid', $script_url, array(), $this->mermaid_version, true );
		}
	}

	/**
	 * Print Javascript plaintext in page footer.
	 */
	public function front_print_footer_scripts() {
		$script = '
			<script id="module-mermaid">
				(function($) {
					$(function() {
						if (typeof mermaid !== "undefined") {
                            if ($(".language-mermaid").length > 0) {
								$(".language-mermaid").parent("pre").attr("style", "text-align: center; background: none;");
								$(".language-mermaid").addClass("mermaid").removeClass("language-mermaid");
								mermaid.init();
                            }
						}
					});
                })(jQuery);
			</script>
		';
		echo preg_replace( '/\s+/', ' ', $script );
	}
}
