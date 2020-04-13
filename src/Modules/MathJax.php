<?php
/**
 * Module Name: MathJax
 * Module Description: Use MathJax markup for complex equations and other geekery.
 *
 * @author Terry Lin
 * @link https://terryl.in/
 *
 * @package Githuber
 * @since 1.0.0
 * @version 1.7.0
 */

namespace Githuber\Module;

class MathJax extends ModuleAbstract {

	/**
	 * The version of KaTeX we are using.
	 *
	 * @var string
	 */
	public $mathjax_version = '2.7.7';

	/**
	 * The priority order to load CSS file, the value should be higher than theme's.
	 * Overwrite the theme's style it's safe to display the correct syntax highlight.
	 *
	 * @var integer
	 */
	public $css_priority = 1000;

	/**
	 * Constants.
	 */
	const MD_POST_META_MATHJAX = '_is_githuber_mathjax';

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
		//add_action( 'wp_enqueue_scripts', array( $this, 'front_enqueue_styles'), $this->css_priority );
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
		if ( $this->is_module_should_be_loaded( self::MD_POST_META_MATHJAX ) ) {

            $option = githuber_get_option( 'mathjax_src', 'githuber_modules' );

			switch ( $option ) {
                case 'cloudflare':
					$script_url = 'https://cdnjs.cloudflare.com/ajax/libs/mathjax/' . $this->mathjax_version . '/MathJax.js';
					break;

                case 'jsdelivr':
                default:
					$script_url = 'https://cdn.jsdelivr.net/npm/mathjax@' . $this->mathjax_version . '/MathJax.js';
					break;
			} 
			wp_enqueue_script( 'mathjax', $script_url, array(), $this->mathjax_version, true );
		}
	}

	/**
	 * Print Javascript plaintext in page footer.
	 */
	public function front_print_footer_scripts() {
		$script = '
            <script id="module-mathjax" >
                (function($) {
                    $(function() {
                        if (typeof MathJax !== "undefined") {
                            if ($(".language-mathjax").length > 0) {
                                MathJax.Hub.Config({
                                    showProcessingMessages: false,
                                    messageStyle: "none",
                                    extensions: [
                                        "tex2jax.js",
                                        "TeX/mediawiki-texvc.js",
                                        "TeX/noUndefined.js",
                                        "TeX/autoload-all.js",
                                        "TeX/AMSmath.js",
                                        "TeX/AMSsymbols.js"
                                    ],
                                    jax: [
                                        "input/TeX",
                                        "output/SVG"
                                    ],
                                    elements: document.getElementsByClassName("language-mathjax"),
                                    tex2jax: {
                                        processClass: "language-mathjax|inline-mathjax"
                                    }
                                });
                                MathJax.Hub.Queue(["Typeset", MathJax.Hub]);
                                $(".language-mathjax").closest("pre").attr("style", "background: transparent");
                            } else {
                                console.log("MathJax code blocks not found.");
                            }
                        } else {
                            console.log("MathJax is not loadded");
                        }  
                    });
                })(jQuery);
            </script>
		';
		echo preg_replace( '/\s+/', ' ', $script );
	}
}
