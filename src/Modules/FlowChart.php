<?php
/**
 * Module Name: Flow Chart
 * Module Description: Draws simple SVG flow chart diagrams from textual representation of the diagram.
 *
 * @author Terry Lin
 * @link https://terryl.in/
 *
 * @package Githuber
 * @since 1.0.0
 * @version 1.0.0
 */

namespace Githuber\Module;

class FlowChart extends ModuleAbstract {

	/**
	 * The version of flowchart.js we are using.
	 *
	 * @var string
	 */
    public $flowchart_version = '1.14.1'; // 1.11.3 => 1.14.1

	/**
	 * The version of raphael.js we are using.
	 *
	 * @var string
	 */
	public $raphael_version = '2.2.27';
	
	/**
	 * Constants.
	 */
	const MD_POST_META_FLOW = '_is_githuber_flow_chart';

	/**
	 * Constructer.
	 */
	public function __construct() {
		parent::__construct();
	}

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

		if ( $this->is_module_should_be_loaded( self::MD_POST_META_FLOW ) ) {

			$option  = githuber_get_option( 'flowchart_src', 'githuber_modules' );

			switch ( $option ) {
				case 'cloudflare':
					$script_url[0] = 'https://cdnjs.cloudflare.com/ajax/libs/raphael/' . $this->raphael_version . '/raphael.min.js';
					$script_url[1] = 'https://cdnjs.cloudflare.com/ajax/libs/flowchart/' . $this->flowchart_version . '/flowchart.min.js';
					break;

				case 'jsdelivr':
					$script_url[0] = 'https://cdn.jsdelivr.net/npm/raphael@' . $this->raphael_version . '/raphael.min.js';

					// It doesn't have the latest files in `release` folder on jsdelivr, rollback to 1.12.1
					$this->flowchart_version = '1.12.1';
					$script_url[1] = 'https://cdn.jsdelivr.net/npm/flowchart.js@' . $this->flowchart_version . '/release/flowchart.min.js';
					break;

				default:
					$script_url[0] = $this->githuber_plugin_url . 'assets/vendor/raphael/raphael.min.js';
					$script_url[1] = $this->githuber_plugin_url . 'assets/vendor/flowchart/flowchart.min.js';
					break;
			} 
			wp_enqueue_script( 'raphael', $script_url[0], array(), $this->raphael_version, true );
			wp_enqueue_script( 'flowchart', $script_url[1], array(), $this->flowchart_version, true );
		}
	}

	/**
	 * Print Javascript plaintext in page footer.
	 */
	public function front_print_footer_scripts() {
		$script = '
			<script id="module-flowchart">
				(function($) {
					$(function() {
						if (typeof $.fn.flowChart !== "undefined") {
							if ($(".language-flow").length > 0) {
								$(".language-flow").parent("pre").attr("style", "text-align: center; background: none;");
								$(".language-flow").addClass("flowchart").removeClass("language-flow");
								$(".flowchart").flowChart();
							}
						}
					});
				})(jQuery);
			</script>
		';
		echo preg_replace( '/\s+/', ' ', $script );
	}
}
