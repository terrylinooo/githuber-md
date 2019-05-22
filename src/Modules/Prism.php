<?php
/**
 * Module Name: Prism
 * Module Description: A syntax highlighter.
 *
 * @author Terry Lin
 * @link https://terryl.in/
 *
 * @package Githuber
 * @since 1.0.0
 * @version 1.4.0
 * 
 */

namespace Githuber\Module;

class Prism extends ModuleAbstract {

	/**
	 * The version of Prism we are using.
	 *
	 * @var string
	 */
	public $prism_version = '1.15.0';

	/**
	 * The priority order to load CSS file, the value should be higher than theme's.
	 * Overwrite the theme's style to make sure that it's safe to display the correct syntax highlight.
	 *
	 * @var integer
	 */
	public $css_priority = 999;

	/**
	 * Constant. Should be same as `Markdown::MD_POST_META_PRISM`.
	 */
	const MD_POST_META_PRISM = '_githuber_prismjs';
	
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
		add_action( 'wp_enqueue_scripts', array( $this, 'front_enqueue_styles' ), $this->css_priority );
		add_action( 'wp_print_footer_scripts', array( $this, 'auto_loader_config_scripts' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'front_enqueue_scripts' ) );
		add_action( 'wp_print_footer_scripts', array( $this, 'front_print_footer_scripts' ) );
	}
 
	/**
	 * Register CSS style files for frontend use.
	 * 
	 * @return void
	 */
	public function front_enqueue_styles() {

		if ( $this->is_module_should_be_loaded( self::MD_POST_META_PRISM ) ) {
			$prism_src         = githuber_get_option( 'prism_src', 'githuber_modules' );
			$prism_theme       = githuber_get_option( 'prism_theme', 'githuber_modules' );
			$prism_line_number = githuber_get_option( 'prism_line_number', 'githuber_modules' );
			$theme             = ( 'default' === $prism_theme || empty( $prism_theme ) ) ? 'prism' : 'prism-' . $prism_theme;

			switch ( $prism_src ) {
				case 'cloudflare':
					$style_url[] = 'https://cdnjs.cloudflare.com/ajax/libs/prism/' . $this->prism_version . '/themes/' . $theme . '.min.css';
					if ( 'yes' === $prism_line_number ) {
						$style_url[] = 'https://cdnjs.cloudflare.com/ajax/libs/prism/' . $this->prism_version . '/plugins/line-numbers/prism-line-numbers.min.css';
					}
					break;

				case 'jsdelivr':
					$style_url[] = 'https://cdn.jsdelivr.net/npm/prismjs@' . $this->prism_version . '/themes/' . $theme . '.css';
					if ( 'yes' === $prism_line_number ) {
						$style_url[] = 'https://cdnjs.cloudflare.com/ajax/libs/prism/' . $this->prism_version . '/plugins/line-numbers/prism-line-numbers.css';
					}
					break;

				default:
					$style_url[] = $this->githuber_plugin_url . 'assets/vendor/prism/themes/' . $theme . '.min.css';
					if ( 'yes' === $prism_line_number ) {
						$style_url[] = $this->githuber_plugin_url . 'assets/vendor/prism/plugins/line-numbers/prism-line-numbers.css';
					}
					break;
			}

			foreach ( $style_url as $key => $url ) {
				wp_enqueue_style( 'prism-css-' . $key, $url, array(), $this->prism_version, 'all' );
			}
		}
	}

	/**
	 * Register JS files for frontend use.
	 * 
	 * @return void
	 */
	public function front_enqueue_scripts() {
		if ( $this->is_module_should_be_loaded( self::MD_POST_META_PRISM ) ) {
			$prism_src         = githuber_get_option( 'prism_src', 'githuber_modules' );
			$prism_line_number = githuber_get_option( 'prism_line_number', 'githuber_modules' );
			$post_id           = githuber_get_current_post_id();
			$prism_meta_string = get_metadata( 'post', $post_id, self::MD_POST_META_PRISM );
			$prism_meta_array  = explode( ',', $prism_meta_string[0] );

			switch ( $prism_src ) {
				case 'cloudflare':
					$script_url[] = 'https://cdnjs.cloudflare.com/ajax/libs/prism/' . $this->prism_version . '/components/prism-core.min.js';
					$script_url[] = 'https://cdnjs.cloudflare.com/ajax/libs/prism/' . $this->prism_version . '/prism.min.js';

					if ( 'yes' === $prism_line_number ) {
						$script_url[] = 'https://cdnjs.cloudflare.com/ajax/libs/prism/' . $this->prism_version . '/plugins/line-numbers/prism-line-numbers.min.js';
					}

					// AutoLoader plugin
					$script_url[] = 'https://cdnjs.cloudflare.com/ajax/libs/prism/' . $this->prism_version . '/plugins/autoloader/prism-autoloader.min.js';

					break;

				case 'jsdelivr':
					$script_url[] = 'https://cdn.jsdelivr.net/npm/prismjs@' . $this->prism_version . '/components/prism-core.min.js';
					$script_url[] = 'https://cdn.jsdelivr.net/npm/prismjs@' . $this->prism_version . '/prism.min.js';

					if ( 'yes' === $prism_line_number ) {
						$script_url[] = 'https://cdn.jsdelivr.net/npm/prismjs@' . $this->prism_version . '/plugins/line-numbers/prism-line-numbers.min.js';
					}

					// AutoLoader plugin (Add since 1.11.4)
					$script_url[] = 'https://cdn.jsdelivr.net/npm/prismjs@' . $this->prism_version . '/plugins/autoloader/prism-autoloader.min.js';

					break;

				default: 
					$script_url[] = $this->githuber_plugin_url . 'assets/vendor/prism/components/prism-core.min.js';
					$script_url[] = $this->githuber_plugin_url . 'assets/vendor/prism/prism.min.js';

					if ( 'yes' === $prism_line_number ) {
						$script_url[] = $this->githuber_plugin_url . 'assets/vendor/prism/plugins/line-numbers/prism-line-numbers.min.js';
					}

					// AutoLoader plugin (Add since 1.11.4)
					$script_url[] = $this->githuber_plugin_url . 'assets/vendor/prism/plugins/autoloader/prism-autoloader.min.js';

					/* (Deprecated since 1.11.4)

					if ( ! empty( $prism_meta_array ) ) {
						foreach ( array_reverse( $prism_meta_array ) as $component_name ) {

							// Those componets are already included in code.js
							if ( ! $this->is_component_already_loaded ( $component_name ) ) {
								$script_url[] = $this->githuber_plugin_url . 'assets/vendor/prism/components/prism-' . $component_name . '.min.js';
							}
						}
					} */

					break;
			}

			foreach ( $script_url as $key => $url ) {
				wp_enqueue_script( 'prism-js-' . $key, $url, array(), $this->prism_version, true );
			}
		}
	}

	/**
	 * Configure auto loader path.
	 * 
	 * @since 1.11.4
	 */
	public function auto_loader_config_scripts() {
		if ( $this->is_module_should_be_loaded( self::MD_POST_META_PRISM ) ) {
			$prism_src = githuber_get_option( 'prism_src', 'githuber_modules' );

			switch ( $prism_src ) {
				case 'cloudflare':
					$script_path = 'https://cdnjs.cloudflare.com/ajax/libs/prism/' . $this->prism_version . '/components/';

					break;

				case 'jsdelivr':
					$script_path = 'https://cdn.jsdelivr.net/npm/prismjs@' . $this->prism_version . '/components/';

					break;

				default: 
					$script_path = $this->githuber_plugin_url . 'assets/vendor/prism/components/';

					break;
			}

			$script = '
				<script id="auto_loader_config_scripts">
					Prism.plugins.autoloader.languages_path = "' . $script_path . '";
				</script>
			';

			echo preg_replace( '/\s+/', ' ', $script );
		}
	}

	/**
	 * Print Javascript plaintext in page footer.
	 */
	public function front_print_footer_scripts() {
		$prism_line_number = githuber_get_option( 'prism_line_number', 'githuber_modules' );

		if ( 'yes' === $prism_line_number ) {
			$script = '
				<script id="module-prism-line-number">
					(function($) {
						$(function() {
							$("code").each(function() {
								var parent_div = $(this).parent("pre");
								var pre_css = $(this).attr("class");
								if (typeof pre_css !== "undefined" && -1 !== pre_css.indexOf("language-")) {
									parent_div.addClass("line-numbers");
								}
							});
						});
					})(jQuery);
				</script>
			';
			echo preg_replace( '/\s+/', ' ', $script );
		}
	}

	/**
	 * (Deprecated since 1.11.4) (Use auto-loader instead)
	 * 
	 *
	 * Check if component is already loaded or not.
	 * Those scripts are already included in prism.js, so we do not need to load those scripts again.
	 *
	 * @param string $name Prism component name.
	 *
	 * @return boolean
	 */
	public function is_component_already_loaded( $name ) {
		switch ( $name ) {
			case 'markup':
			case 'xml':
			case 'html':
			case 'mathml':
			case 'svg':
			case 'clike':
			case 'javascript':
			case 'js':
				return true;
				break;
			default:
				return false;
		}
	}
}
