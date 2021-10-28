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
	 * The version of MathJax we are using.
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
					$script_url = 'https://cdn.jsdelivr.net/npm/mathjax@' . $this->mathjax_version . '/MathJax.js';
					break;

				default:
					$script_url = $this->githuber_plugin_url . 'assets/vendor/mathjax/MathJax.js';
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
							var c = $(".language-mathjax").length;

                            if (c > 0) {
								$(".language-mathjax").each(function(i) {
									var content = $(this).html();
									if ($(this).hasClass("mathjax-inline")) {
										$(this).html("$ " + content + " $");
									} else {
										$(this).html("$$" + "\n" + content + "\n" + "$$");
									}

									if (i + 1 === c) {
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
												skipTags: [
													"script",
													"noscript",
													"style",
													"textarea"
												],
												inlineMath: [
													[\'$\', \'$\']
												],
												displayMath: [
													[\'$$\', \'$$\']
												],
												processClass: "language-mathjax"
											}
										});

										MathJax.Hub.Queue(["Typeset", MathJax.Hub]);

										$(".language-mathjax").attr("style", "background: transparent; border: 0;");
										$(".language-mathjax").closest("pre").attr("style", "background: transparent; border: 0;");
									}
								});
                            } else {
                                console.log("[wp-githuber-md] MathJax code blocks not found.");
                            }
                        } else {
                            console.log("[wp-githuber-md] MathJax is not loadded.");
                        }  
                    });
                })(jQuery);
            </script>
		';
		echo preg_replace( '/\s+/', ' ', $script );
	}

	/**
	 * MathJax Inline Markup
	 * 
	 * Ex.
	 * `$ x_{1,2} = {-b\pm\sqrt{b^2 - 4ac} \over 2a}. $`
	 *
	 * @param string  $content HTML or Markdown content.
	 * @return void
	 */
	public static function mathjax_inline_markup( $content ) {

		$regex = '%<code>\$((?:[^$]+ |(?<=(?<!\\\\)\\\\)\$ )+)(?<!\\\\)\$<\/code>%ix';
		$result = preg_replace_callback( $regex, function() {
			$matches = func_get_arg(0);

			if ( ! empty( $matches[1] ) ) {
				$mathjax = $matches[1];
				$mathjax = str_replace( array( '&lt;', '&gt;', '&quot;', '&#039;', '&#038;', '&amp;', "\n", "\r" ), array( '<', '>', '"', "'", '&', '&', ' ', ' ' ), $mathjax );
				return '<code class="mathjax-inline language-mathjax">' . trim( $mathjax ) . '</code>';
			}
		}, $content );

		if ( ! empty( $result ) ) {
			$content = $result;
		}

		return $content;
	}
}
