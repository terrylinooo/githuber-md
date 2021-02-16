<?php
/**
 * Module Name: Sequence Diagram
 * Module Description: Turn text into vector UML sequence diagrams.
 * 
 * JavaScript package: https://github.com/bramp/js-sequence-diagrams
 *
 * @author Terry Lin
 * @link https://terryl.in/
 *
 * @package Githuber
 * @since 1.0.0
 * @version 1.0.0
 */

namespace Githuber\Module;

class SequenceDiagram extends ModuleAbstract {

	/**
	 * The version of js-sequence-diagrams.js we are using.
	 *
	 * @var string
	 */
    public $sequence_diagram_version = '1.0.6';

	/**
	 * The version of raphael.js we are using.
	 *
	 * @var string
	 */
	public $raphael_version = '2.2.27';
	
	/**
	 * The version of underscore.js we are using.
	 *
	 * @var string
	 */
    public $underscore_version = '2.2.27';

	/**
	 * Constructer.
	 */
	public function __construct() {
		parent::__construct();
	}

	/**
	 * Constants.
	 */
	const MD_POST_META_SEQUENCE = '_is_githuber_sequence';

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
		if ( $this->is_module_should_be_loaded( self::MD_POST_META_SEQUENCE ) ) {
			$option = githuber_get_option( 'flowchart_src', 'githuber_modules' );

			switch ( $option ) {
				case 'cloudflare':
					$script_url[0] = 'https://cdnjs.cloudflare.com/ajax/libs/raphael/' . $this->raphael_version . '/raphael.min.js';
					$script_url[1] = 'https://cdnjs.cloudflare.com/ajax/libs/underscore.js/' . $this->underscore_version . '/underscore-min.js';
					$script_url[2] = 'https://cdnjs.cloudflare.com/ajax/libs/js-sequence-diagrams/' . $this->sequence_diagram_version . '/js-sequence-diagram.min.js';
					break;

				case 'jsdelivr':
					$script_url[0] = 'https://cdn.jsdelivr.net/npm/raphael@' . $this->raphael_version . '/raphael.min.js';
					$script_url[1] = 'https://cdn.jsdelivr.net/npm/underscore@' . $this->underscore_version . '/underscore.min.js';
					$script_url[2] = 'https://cdn.jsdelivr.net/gh/bramp/js-sequence-diagrams@v' . $this->sequence_diagram_version . '/build/sequence-diagram-min.js';
					break;

				default:
					$script_url[0] = $this->githuber_plugin_url . 'assets/vendor/raphael/raphael.min.js';
					$script_url[1] = $this->githuber_plugin_url . 'assets/vendor/underscore/underscore.min.js';
					$script_url[2] = $this->githuber_plugin_url . 'assets/vendor/js-sequence-diagrams/sequence-diagram.min.js';
					break;
			} 

			wp_enqueue_script( 'raphael', $script_url[0], array(), $this->raphael_version, true );
			wp_enqueue_script( 'underscore', $script_url[1], array(), $this->underscore_version, true );
			wp_enqueue_script( 'sequence-diagrams', $script_url[2], array(), $this->sequence_diagram_version, true );
		}
	}

	/**
	 * Print Javascript plaintext in page footer.
	 */
	public function front_print_footer_scripts() {
		$script = '
			<script id="module-sequence-diagram">
				(function($) {
					$(function() {
						if (typeof $.fn.sequenceDiagram !== "undefined") {
							$(".language-sequence").parent("pre").attr("style", "text-align: center; background: none;");
							$(".language-seq").parent("pre").attr("style", "text-align: center; background: none;");
							$(".language-sequence").addClass("sequence-diagram").removeClass("language-sequence");
							$(".language-seq").addClass("sequence-diagram").removeClass("language-seq");
							$(".sequence-diagram").sequenceDiagram({
								theme: "simple"
							});
						}
					});
                })(jQuery);
			</script>
		';
		echo preg_replace( '/\s+/', ' ', $script );
	}
}
