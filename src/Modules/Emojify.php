<?php
/**
 * Module Name: Emoji
 * Module Description: Emoji are ideograms and smileys used in electronic messages and web pages.
 *
 * @author Terry Lin
 * @link https://terryl.in/
 *
 * @package Githuber
 * @since 1.0.0
 * @version 1.14.0
 */

namespace Githuber\Module;

/**
 * Emogify
 */
class Emojify extends ModuleAbstract {

	/**
	 * The version of Emojify we are using.
	 *
	 * @var string
	 */
	public $emojify_version = '1.1.0';

	/**
	 * The priority order to load CSS file, the value should be higher than theme's.
	 *
	 * @var integer
	 */
	public $css_priority = 1000;

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
		add_action( 'wp_enqueue_scripts', array( $this, 'front_enqueue_styles'), $this->css_priority );
		add_action( 'wp_enqueue_scripts', array( $this, 'front_enqueue_scripts' ) );
		add_action( 'wp_print_footer_scripts', array( $this, 'front_print_footer_scripts' ) );
	}

	/**
	 * Register CSS style files for frontend use.
	 * 
	 * @return void
	 */
	public function front_enqueue_styles() {

		$option = githuber_get_option( 'emojify_src', 'githuber_modules' );

		switch ( $option ) {
			case 'cloudflare':
				$style_url = 'https://cdnjs.cloudflare.com/ajax/libs/emojify.js/' . $this->emojify_version . '/css/basic/emojify.min.css';
				break;

			case 'jsdelivr':
				$style_url = 'https://cdn.jsdelivr.net/npm/emojify.js@' . $this->emojify_version . '/dist/css/basic/emojify.min.css';
				break;

			default:
				$style_url = $this->githuber_plugin_url . 'assets/vendor/emojify/css/emojify.min.css';
				break;
		}
		wp_enqueue_style( 'emojify', $style_url, array(), $this->emojify_version, 'all' );
	}

	/**
	 * Register JS files for frontend use.
	 * 
	 * @return void
	 */
	public function front_enqueue_scripts() {

		$option = githuber_get_option( 'emojify_src', 'githuber_modules' );

		switch ( $option ) {
			case 'cloudflare':
				$script_url = 'https://cdnjs.cloudflare.com/ajax/libs//emojify.js/' . $this->emojify_version . '/js/emojify.min.js';
				break;

			case 'jsdelivr':
				$script_url = 'https://cdn.jsdelivr.net/npm/emojify.js@' . $this->emojify_version . '/dist/js/emojify.min.js';
				break;

			default:
				$script_url = $this->githuber_plugin_url . 'assets/vendor/emojify/js/emojify.min.js';
				break;
		} 
		wp_enqueue_script( 'emojify', $script_url, array(), $this->emojify_version, true );
	}

	/**
	 * Print Javascript plaintext in page footer.
	 */
	public function front_print_footer_scripts() {

		$option = githuber_get_option( 'emojify_src', 'githuber_modules' );

		switch ( $option ) {
			case 'cloudflare':
				// https://cdnjs.cloudflare.com/ajax/libs/emojify.js/1.1.0/images/basic/+1.png
				$img_dir = 'https://cdnjs.cloudflare.com/ajax/libs//emojify.js/' . $this->emojify_version . '/images/basic';
				break;

			case 'jsdelivr':
				// https://cdn.jsdelivr.net/npm/emojify.js@1.1.0/dist/images/basic/+1.png
				$img_dir = 'https://cdn.jsdelivr.net/npm/emojify.js@' . $this->emojify_version . '/dist/images/basic';
				break;

			default:
				$img_dir = $this->githuber_plugin_url . 'assets/vendor/emojify/images';
				break;
		} 

		$script = '
			<script id="module-emojify">
				(function($) {
					$(function() {
						if (typeof emojify !== "undefined") {
							emojify.setConfig({
								img_dir: "' . $img_dir . '",
								blacklist: {
									"classes": ["no-emojify"],
									"elements": ["script", "textarea", "pre", "code"]
								}
							});
							emojify.run();
						} else {
							console.log("[wp-githuber-md] emogify is undefined.");
						}
					});
				})(jQuery);
			</script>
		';
		echo $script;
	}
}
